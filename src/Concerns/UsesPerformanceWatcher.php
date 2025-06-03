<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Concerns;

use Atendwa\ActionWatch\Events\ProcessCompleted;
use Illuminate\Support\Facades\DB;

trait UsesPerformanceWatcher
{
    protected ?int $maxDurationMilliseconds = null;

    protected ?int $maxQueriesCount = null;

    protected ?int $maxPeakMemoryMbs = null;

    protected float $actionStartTime;

    public function __construct()
    {
        $this->startWatch();
    }

    public function finish(): void
    {
        ProcessCompleted::dispatch(static::class, $this->performance());
    }

    /**
     * @return array<string, int|float>
     */
    public function performance(): array
    {
        return [
            'results' => [
                'duration' => (microtime(true) - $this->actionStartTime) * 1000,
                'peak_memory' => memory_get_peak_usage(true) / 1024 / 1024,
                'queries' => count(DB::getQueryLog()),
            ],
            'constraints' => [
                'duration' => $this->maxDurationMilliseconds(),
                'peak_memory' => $this->maxPeakMemoryMbs(),
                'queries' => $this->maxQueriesCount(),
            ],
        ];
    }

    public function constraints(?int $queries, ?int $duration, ?int $memory): self
    {
        $this->maxDurationMilliseconds = $duration;
        $this->maxQueriesCount = $queries;
        $this->maxPeakMemoryMbs = $memory;

        return $this;
    }

    protected function startWatch(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $this->actionStartTime = microtime(true);
    }

    protected function maxDurationMilliseconds(): int
    {
        $value = $this->maxDurationMilliseconds ?? config('action-watch.constraints.max_duration_milliseconds');

        return asInteger($value);
    }

    protected function maxQueriesCount(): int
    {
        $value = $this->maxQueriesCount ?? config('action-watch.constraints.max_queries_count');

        return asInteger($value);
    }

    protected function maxPeakMemoryMbs(): int
    {
        $value = $this->maxPeakMemoryMbs ?? config('action-watch.constraints.max_peak_memory_usage_mbs');

        return asInteger($value);
    }
}
