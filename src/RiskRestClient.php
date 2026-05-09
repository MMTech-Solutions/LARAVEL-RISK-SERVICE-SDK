<?php

declare(strict_types=1);

namespace MmtRiskSdk;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use JsonException;
use MmtRiskSdk\Api\AccountsApi;
use MmtRiskSdk\Api\BrokersApi;
use MmtRiskSdk\Api\InternalIngressApi;
use MmtRiskSdk\Api\RulesApi;
use MmtRiskSdk\Support\Envelope;

/**
 * HTTP client for the MMT Risk Management Service REST API (envelope JSON + plain /health).
 */
final class RiskRestClient
{
    public const VERSION = '1.0.0';

    public readonly InternalIngressApi $ingress;

    public readonly RulesApi $rules;

    public readonly AccountsApi $accounts;

    public readonly BrokersApi $brokers;

    public function __construct(
        private readonly Factory $http,
        private readonly string $baseUrl,
        private readonly float $defaultTimeout = 60.0,
        private readonly array $headers = [],
    ) {
        $this->ingress = new InternalIngressApi($this);
        $this->rules = new RulesApi($this);
        $this->accounts = new AccountsApi($this);
        $this->brokers = new BrokersApi($this);
    }

    /**
     * @param  array<string, mixed>  $config  Laravel config('mmt-risk-sdk')
     */
    public static function fromConfig(Factory $http, array $config): self
    {
        $headers = self::buildHeaders($config);

        return new self(
            $http,
            rtrim((string) ($config['base_url'] ?? 'http://127.0.0.1:6051'), '/'),
            (float) ($config['default_timeout'] ?? 60),
            $headers,
        );
    }

    /**
     * Standalone / CLI without Laravel config (getenv).
     */
    public static function fromEnvironment(Factory $http): self
    {
        $base = getenv('MMT_RISK_API_BASE_URL') ?: 'http://127.0.0.1:6051';
        $token = getenv('MMT_RISK_API_TOKEN');
        $timeout = (float) (getenv('MMT_RISK_HTTP_TIMEOUT') ?: 60);

        $headers = [];
        if (is_string($token) && trim($token) !== '') {
            $headers['Authorization'] = 'Bearer '.trim($token);
        }

        return new self($http, rtrim($base, '/'), $timeout, $headers);
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, string>
     */
    private static function buildHeaders(array $config): array
    {
        $headers = [];
        $token = $config['api_token'] ?? null;
        if (is_string($token) && trim($token) !== '') {
            $headers['Authorization'] = 'Bearer '.trim($token);
        }
        $extra = $config['headers'] ?? [];
        if (is_array($extra)) {
            foreach ($extra as $k => $v) {
                if (is_string($k) && (is_string($v) || is_int($v) || is_float($v))) {
                    $headers[$k] = (string) $v;
                }
            }
        }

        return $headers;
    }

    public function url(string $path): string
    {
        $base = rtrim($this->baseUrl, '/');
        if ($path === '' || $path[0] !== '/') {
            $path = '/'.$path;
        }

        return $base.$path;
    }

    /**
     * @param  array<string, mixed>|null  $params  query string (GET / HEAD)
     * @param  array<string, mixed>|list<mixed>|scalar|null  $jsonBody  JSON body (non-GET)
     * @return mixed  envelope "data" when code == OK
     */
    public function envelopeRequest(
        string $method,
        string $path,
        ?array $params = null,
        mixed $jsonBody = null,
        ?float $timeout = null,
    ): mixed {
        $url = $this->url($path);
        $effectiveTimeout = $timeout ?? $this->defaultTimeout;
        $req = $this->baseRequest($effectiveTimeout);
        $m = strtoupper($method);

        if (! in_array($m, ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            throw new \InvalidArgumentException("Unsupported HTTP method: {$method}");
        }

        $options = [];
        if (in_array($m, ['GET', 'HEAD'], true)) {
            if (($params ?? []) !== []) {
                $options['query'] = $params;
            }
        } elseif ($jsonBody !== null) {
            $options['json'] = $jsonBody;
        }

        $response = $req->send($m, $url, $options);

        try {
            $body = $response->json();
        } catch (JsonException) {
            self::raiseForHttp($response, $url);
            throw new RiskApiError("Invalid JSON from {$url}", httpStatus: $response->status());
        }

        return Envelope::dataOrRaise(
            $body,
            $response->successful(),
            $response->status(),
            $url,
            $response->reason() ?? '',
        );
    }

    /**
     * GET /health — response is plain JSON, not wrapped in the API envelope.
     *
     * @return array<string, mixed>|mixed
     */
    public function health(): mixed
    {
        $url = $this->url('/health');
        $response = $this->baseRequest($this->defaultTimeout)->get($url);
        self::raiseForHttp($response, $url);

        return $response->json();
    }

    private function baseRequest(float $timeoutSeconds): PendingRequest
    {
        $base = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'mmt-risk-sdk-php/'.self::VERSION,
        ];

        return $this->http->timeout($timeoutSeconds)
            ->withHeaders(array_merge($base, $this->headers))
            ->asJson();
    }

    private static function raiseForHttp(Response $response, string $url): void
    {
        if ($response->successful()) {
            return;
        }
        try {
            $detail = $response->json();
        } catch (JsonException) {
            $detail = $response->body();
        }
        $msg = sprintf(
            '%d %s for %s: %s',
            $response->status(),
            $response->reason() ?? '',
            $url,
            is_string($detail) ? $detail : json_encode($detail, JSON_UNESCAPED_UNICODE),
        );
        throw new RiskApiError($msg, httpStatus: $response->status(), payload: $detail);
    }
}
