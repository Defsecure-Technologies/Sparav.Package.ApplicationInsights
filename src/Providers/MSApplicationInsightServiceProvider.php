<?php

namespace Sparav\ApplicationInsights\Providers;

use Illuminate\Support\ServiceProvider;
use Sparav\ApplicationInsights\Middleware\MSApplicationInsightsMiddleware;
use Sparav\ApplicationInsights\MSApplicationInsightsHelpers;
use Sparav\ApplicationInsights\MSApplicationInsightsServer;
use Sparav\ApplicationInsights\Telemetry_Client;

class MSApplicationInsightServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot() {
        $this->handleConfigs();
    }

    private function handleConfigs() {

        $configPath = __DIR__ . '/../MSApplicationInsightsLaravel.php';

        $this->publishes([$configPath => config_path('MSApplicationInsightsLaravel.php')]);

        $this->mergeConfigFrom($configPath, 'MSApplicationInsightsLaravel');
    }

    public function register()
    {
        $this->app->singleton('MSApplicationInsightsServer', function ($app) {
            $telemetryClient = new Telemetry_Client();
            return new MSApplicationInsightsServer($telemetryClient);
        });

        $this->app->singleton('MSApplicationInsightsMiddleware', function ($app) {
            $msApplicationInsightsHelpers = new MSApplicationInsightsHelpers($app['MSApplicationInsightsServer']);
            return new MSApplicationInsightsMiddleware($msApplicationInsightsHelpers);
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {

        return [
            'MSApplicationInsightsServer',
            'MSApplicationInsightsMiddleware'
        ];
    }

}
