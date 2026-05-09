<?php

declare(strict_types=1);

namespace MmtRiskSdk\Tests;

use Illuminate\Http\Client\Factory;
use MmtRiskSdk\RiskApiError;
use MmtRiskSdk\RiskRestClient;
use PHPUnit\Framework\TestCase;

final class RiskRestClientTest extends TestCase
{
    public function testEnvelopeRequestReturnsEnvelopeData(): void
    {
        $http = new Factory;
        $http->fake([
            'http://example.test/rules' => $http->response([
                'code' => 'OK',
                'data' => ['items' => []],
            ], 200),
        ]);

        $client = new RiskRestClient($http, 'http://example.test');

        $data = $client->envelopeRequest('GET', '/rules');

        self::assertSame(['items' => []], $data);
    }

    public function testEnvelopeRequestThrowsOnApiErrorEnvelope(): void
    {
        $http = new Factory;
        $http->fake([
            '*' => $http->response([
                'code' => 'NOT_FOUND',
                'message' => 'missing rule',
            ], 200),
        ]);

        $client = new RiskRestClient($http, 'http://example.test');

        $this->expectException(RiskApiError::class);
        $this->expectExceptionMessage('missing rule');

        $client->envelopeRequest('GET', '/rules/x');
    }

    public function testHealthReturnsPlainJsonWithoutEnvelope(): void
    {
        $http = new Factory;
        $http->fake([
            'http://example.test/health' => $http->response([
                'status' => 'ok',
                'service' => 'risk',
            ], 200),
        ]);

        $client = new RiskRestClient($http, 'http://example.test');

        $data = $client->health();

        self::assertSame(['status' => 'ok', 'service' => 'risk'], $data);
    }

    public function testHttpErrorThrowsRiskApiError(): void
    {
        $http = new Factory;
        $http->fake([
            '*' => $http->response('gateway timeout', 504),
        ]);

        $client = new RiskRestClient($http, 'http://example.test');

        $this->expectException(RiskApiError::class);

        $client->envelopeRequest('GET', '/rules');
    }

    public function testRulesApiListsViaExpectedPath(): void
    {
        $http = new Factory;
        $http->fake([
            'http://example.test/rules*' => $http->response([
                'code' => 'OK',
                'data' => [],
            ], 200),
        ]);

        $client = new RiskRestClient($http, 'http://example.test');
        $client->rules->listRules();

        $http->assertSent(function ($request) {
            return $request->url() === 'http://example.test/rules'
                && $request->method() === 'GET';
        });
    }
}
