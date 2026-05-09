<?php

declare(strict_types=1);

namespace MmtRiskSdk\Laravel;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;
use MmtRiskSdk\RiskRestClient;

final class MmtRiskSdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2).'/config/mmt-risk-sdk.php',
            'mmt-risk-sdk',
        );

        $this->app->singleton(RiskRestClient::class, function ($app): RiskRestClient {
            /** @var Factory $http */
            $http = $app->make(Factory::class);

            return RiskRestClient::fromConfig($http, (array) $app['config']->get('mmt-risk-sdk', []));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__, 2).'/config/mmt-risk-sdk.php' => config_path('mmt-risk-sdk.php'),
        ], 'mmt-risk-sdk-config');
    }
}
