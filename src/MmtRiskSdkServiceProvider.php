<?php

declare(strict_types=1);

namespace MmtRiskSdk;

use Illuminate\Support\ServiceProvider;
use MmtRiskSdk\Domains\Accounts\Contracts\AccountsService;
use MmtRiskSdk\Domains\Accounts\Contracts\AccountsServiceInterface;
use MmtRiskSdk\Domains\Brokers\Contracts\BrokersService;
use MmtRiskSdk\Domains\Brokers\Contracts\BrokersServiceInterface;
use MmtRiskSdk\Domains\Ingress\Contracts\IngressService;
use MmtRiskSdk\Domains\Ingress\Contracts\IngressServiceInterface;
use MmtRiskSdk\Domains\Rules\Contracts\RulesService;
use MmtRiskSdk\Domains\Rules\Contracts\RulesServiceInterface;
use MmtRiskSdk\TransportDrivers\Contracts\TransportInterface;
use MmtRiskSdk\TransportDrivers\Drivers\Http\RiskServiceHttpClient;

final class MmtRiskSdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mmt-risk-sdk.php', 'mmt-risk-sdk');

        $this->app->singleton(TransportInterface::class, RiskServiceHttpClient::class);
        $this->app->bind(AccountsServiceInterface::class, AccountsService::class);
        $this->app->bind(BrokersServiceInterface::class, BrokersService::class);
        $this->app->bind(RulesServiceInterface::class, RulesService::class);
        $this->app->bind(IngressServiceInterface::class, IngressService::class);
        $this->app->singleton(RiskService::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mmt-risk-sdk.php' => config_path('mmt-risk-sdk.php'),
            ], 'mmt-risk-sdk-config');
        }
    }
}
