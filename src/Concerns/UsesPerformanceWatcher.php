<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Concerns;

use Atendwa\ActionWatch\Events\ProcessCompleted;
use Illuminate\Support\Facades\DB;

trait UsesPerformanceWatcher
{
    protected static ?int $maxDurationMilliseconds = null;

    protected static ?int $maxQueriesCount = null;

    protected static ?int $maxPeakMemoryMbs = null;

    protected static float $actionStartTime;

    public function __construct()
    {
        self::startWatch();
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
        $results = [
            'results' => [
                'duration' => (microtime(true) - self::$actionStartTime) * 1000,
                'peak_memory' => memory_get_peak_usage(true) / 1024 / 1024,
                'queries' => count(DB::getQueryLog()),
            ],
            'constraints' => [
                'duration' => self::maxDurationMilliseconds(),
                'peak_memory' => self::maxPeakMemoryMbs(),
                'queries' => self::maxQueriesCount(),
            ],
        ];

        DB::disableQueryLog();

        return $results;
    }

    public static function maxDurationMilliseconds(): int
    {
        $value = self::$maxDurationMilliseconds ?? config('action-watch.constraints.max_duration_milliseconds');

        return asInteger($value);
    }

    public static function maxQueriesCount(): int
    {
        $value = self::$maxQueriesCount ?? config('action-watch.constraints.max_queries_count');

        return asInteger($value);
    }

    public static function maxPeakMemoryMbs(): int
    {
        $value = self::$maxPeakMemoryMbs ?? config('action-watch.constraints.max_peak_memory_usage_mbs');

        return asInteger($value);
    }

    //    public function constraints(?int $queries, ?int $duration, ?int $memory): self
    //    {
    //        $this->maxDurationMilliseconds = $duration;
    //        $this->maxQueriesCount = $queries;
    //        $this->maxPeakMemoryMbs = $memory;
    //
    //        return $this;
    //    }

    protected static function startWatch(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        self::$actionStartTime = microtime(true);
    }
}
