<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Listeners;

use Atendwa\ActionWatch\Actions\ProcessPerformanceMetrics;
use Atendwa\ActionWatch\Events\ProcessCompleted;
use Atendwa\ActionWatch\Jobs\ProcessPerformanceMetricsJob;
use Illuminate\Events\Dispatcher;
use Throwable;

class AnalyseProcessMetrics
{
    /**
     * @throws Throwable
     */
    public function handle(ProcessCompleted $processCompleted): void
    {
        if (config('action-watch.process_metrics_in_queue')) {
            ProcessPerformanceMetricsJob::dispatch($processCompleted);

            return;
        }

        app(ProcessPerformanceMetrics::class)->execute($processCompleted);
    }

    /**
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $dispatcher): array
    {
        return match (filled($dispatcher)) {
            true => [ProcessCompleted::class => 'handle'],
            false => []
        };
    }
}
