<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Providers;

use Atendwa\ActionWatch\Console\Commands\InstallActionWatch;
use Atendwa\ActionWatch\Console\Commands\PruneActionWatchEntries;
use Atendwa\ActionWatch\Listeners\AnalyseProcessMetrics;
use Atendwa\ActionWatch\Models\ActionWatchAggregate;
use Atendwa\ActionWatch\Policies\ActionWatchAggregatePolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ActionWatchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/action-watch.php', 'action-watch');
        Gate::policy(ActionWatchAggregate::class, ActionWatchAggregatePolicy::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations')], 'migrations');
            $this->publishes([__DIR__ . '/../../config/action-watch.php' => config_path('action-watch.php')], 'config');

            $this->commands(InstallActionWatch::class);
        }

        $this->commands(PruneActionWatchEntries::class);
        Event::subscribe(AnalyseProcessMetrics::class);
    }
}
