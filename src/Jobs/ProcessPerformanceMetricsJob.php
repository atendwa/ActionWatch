<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Jobs;

use Atendwa\ActionWatch\Actions\ProcessPerformanceMetrics;
use Atendwa\ActionWatch\Events\ProcessCompleted;
use Atendwa\Support\Job;
use Throwable;

class ProcessPerformanceMetricsJob extends Job
{
    public function __construct(private readonly ProcessCompleted $processCompleted) {}

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        app(ProcessPerformanceMetrics::class)->execute($this->processCompleted);
    }
}
