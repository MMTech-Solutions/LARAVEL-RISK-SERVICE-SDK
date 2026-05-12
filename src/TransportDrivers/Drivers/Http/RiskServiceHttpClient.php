<?php

declare(strict_types=1);

namespace MmtRiskSdk\TransportDrivers\Drivers\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use MmtRiskSdk\TransportDrivers\Contracts\ActionResultInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportPacket;

class RiskServiceHttpClient implements TransportInterface
{
    private readonly Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => rtrim((string) config('mmt-risk-sdk.base_url'), '/').'/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function send(TransportPacket $packet): ActionResultInterface
    {
        if (! isset($packet->metadata['method'])) {
            throw new \InvalidArgumentException('Method is required');
        }

        $metadata = $packet->metadata;

        if (($metadata['raw'] ?? false) === true) {
            return $this->rawGet($packet->endpoint, $metadata);
        }

        return match ($metadata['method']) {
            'get' => $this->get($packet->endpoint, $packet->payload, $metadata),
            'post' => $this->post($packet->endpoint, $packet->payload, $metadata),
            'patch' => $this->patch($packet->endpoint, $packet->payload, $metadata),
            'delete' => $this->delete($packet->endpoint, $metadata),
            default => throw new \InvalidArgumentException('Invalid method'),
        };
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @return array<string, mixed>
     */
    private function clientOptions(array $metadata, array $extra = []): array
    {
        if (isset($metadata['timeout'])) {
            $extra['timeout'] = (float) $metadata['timeout'];
        }

        return $extra;
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function rawGet(string $uri, array $metadata = []): ActionResultInterface
    {
        try {
            $options = $this->clientOptions($metadata);
            $response = $this->http->get(ltrim($uri, '/'), $options);

            return ResponseResult::fromPlainJsonSuccess($response->getBody()->getContents());
        } catch (RequestException|ClientException $e) {
            $body = $e->getResponse()?->getBody()?->getContents() ?? '';

            return $body !== '' ? ResponseResult::fromErrorResponse($body) : ResponseResult::fromFatalError($e->getMessage());
        } catch (ConnectException $e) {
            return ResponseResult::fromFatalError('Connection failed. Please check your internet connection and try again.');
        }
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function get(string $uri, array $query = [], array $metadata = []): ActionResultInterface
    {
        try {
            $options = $this->clientOptions(
                $metadata,
                $query !== [] ? ['query' => $this->queryParamSerializer($query)] : []
            );
            $response = $this->http->get(ltrim($uri, '/'), $options);

            return ResponseResult::fromSuccessResponse($response->getBody()->getContents());

        } catch (RequestException|ClientException $e) {
            $body = $e->getResponse()?->getBody()?->getContents() ?? '';

            return $body !== '' ? ResponseResult::fromErrorResponse($body) : ResponseResult::fromFatalError($e->getMessage());
        } catch (ConnectException $e) {
            return ResponseResult::fromFatalError('Connection failed. Please check your internet connection and try again.');
        }
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function post(string $uri, array $data = [], array $metadata = []): ActionResultInterface
    {
        try {
            $options = $this->clientOptions($metadata, ['json' => $data]);
            $response = $this->http->post(ltrim($uri, '/'), $options);

            return ResponseResult::fromSuccessResponse($response->getBody()->getContents());

        } catch (RequestException|ClientException $e) {
            $body = $e->getResponse()?->getBody()?->getContents() ?? '';

            return $body !== '' ? ResponseResult::fromErrorResponse($body) : ResponseResult::fromFatalError($e->getMessage());
        } catch (ConnectException $e) {
            return ResponseResult::fromFatalError('Connection failed. Please check your internet connection and try again.');
        }
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function patch(string $uri, array $data = [], array $metadata = []): ActionResultInterface
    {
        try {
            $options = $this->clientOptions($metadata, ['json' => $data]);
            $response = $this->http->patch(ltrim($uri, '/'), $options);

            return ResponseResult::fromSuccessResponse($response->getBody()->getContents());

        } catch (RequestException|ClientException $e) {
            $body = $e->getResponse()?->getBody()?->getContents() ?? '';

            return $body !== '' ? ResponseResult::fromErrorResponse($body) : ResponseResult::fromFatalError($e->getMessage());
        } catch (ConnectException $e) {
            return ResponseResult::fromFatalError('Connection failed. Please check your internet connection and try again.');
        }
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function delete(string $uri, array $metadata = []): ActionResultInterface
    {
        try {
            $options = $this->clientOptions($metadata);
            $response = $this->http->delete(ltrim($uri, '/'), $options);

            return ResponseResult::fromSuccessResponse($response->getBody()->getContents());

        } catch (RequestException|ClientException $e) {
            $body = $e->getResponse()?->getBody()?->getContents() ?? '';

            return $body !== '' ? ResponseResult::fromErrorResponse($body) : ResponseResult::fromFatalError($e->getMessage());
        } catch (ConnectException $e) {
            return ResponseResult::fromFatalError('Connection failed. Please check your internet connection and try again.');
        }
    }

    /**
     * @param  array<string, mixed>  $queryParams
     */
    private function queryParamSerializer(array $queryParams): string
    {
        $parts = [];
        foreach ($queryParams as $name => $value) {
            $this->appendQueryValue($parts, (string) $name, $value);
        }

        return implode('&', $parts);
    }

    /**
     * @param  list<string>  $parts
     */
    private function appendQueryValue(array &$parts, string $key, mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (is_array($value)) {
            if ($value === []) {
                return;
            }

            if (array_is_list($value)) {
                foreach ($value as $index => $item) {
                    if (is_array($item)) {
                        $this->appendQueryValue($parts, $key.'['.$index.']', $item);
                    } else {
                        $this->appendQueryValue($parts, $key, $item);
                    }
                }

                return;
            }

            $scalarSequence = $this->liftZeroBasedScalarSequence($value);
            if ($scalarSequence !== null) {
                foreach ($scalarSequence as $item) {
                    $this->appendQueryValue($parts, $key, $item);
                }

                return;
            }

            foreach ($value as $subKey => $item) {
                $this->appendQueryValue($parts, $key.'['.$subKey.']', $item);
            }

            return;
        }

        $parts[] = rawurlencode($key).'='.rawurlencode($this->queryScalarToString($value));
    }

    /**
     * @return list<mixed>|null
     */
    private function liftZeroBasedScalarSequence(array $value): ?array
    {
        $out = [];
        $i = 0;
        foreach ($value as $k => $item) {
            if (is_array($item)) {
                return null;
            }
            if (is_int($k)) {
                if ($k !== $i) {
                    return null;
                }
            } elseif (is_string($k) && ctype_digit($k)) {
                if ((int) $k !== $i) {
                    return null;
                }
            } else {
                return null;
            }
            $out[] = $item;
            $i++;
        }

        return $out;
    }

    private function queryScalarToString(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_float($value)) {
            if (is_nan($value) || is_infinite($value)) {
                throw new \InvalidArgumentException('Cannot serialize non-finite float in query string.');
            }

            return (string) $value;
        }

        if (is_int($value) || $value instanceof \Stringable || is_string($value)) {
            return (string) $value;
        }

        throw new \InvalidArgumentException('Unsupported type for query string: '.get_debug_type($value));
    }
}
