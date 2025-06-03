<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Actions;

use Atendwa\ActionWatch\Events\ProcessCompleted;
use Atendwa\ActionWatch\Models\ActionWatchAggregate;
use Atendwa\ActionWatch\Models\ActionWatchEntry;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProcessPerformanceMetrics
{
    /**
     * @throws Throwable
     */
    public function execute(ProcessCompleted $processCompleted): void
    {
        DB::transaction(function () use ($processCompleted): void {
            $metrics = collect($processCompleted->metrics);
            $class = $processCompleted->class;

            $constraints = collect($metrics->get('constraints'));
            $metrics = collect($metrics->get('results'));

            ActionWatchEntry::query()->create([
                'constraints' => $constraints,
                'metrics' => $metrics,
                'class' => $class,
                'violations' => [
                    'peak_memory' => $metrics->get('peak_memory', 0) > $constraints->get('peak_memory', 0),
                    'duration' => $metrics->get('duration', 0) > $constraints->get('duration', 0),
                    'queries' => $metrics->get('queries', 0) > $constraints->get('queries', 0),
                ],
            ]);

            $entries = ActionWatchEntry::query()->where(['class' => $class])->get();

            ActionWatchAggregate::query()->updateOrCreate(['class' => $class], [
                'occurrences' => $entries->count(),
                'averages' => [
                    'peak_memory' => round((float) $entries->avg('metrics.peak_memory'), 2),
                    'duration' => round((float) $entries->avg('metrics.duration'), 2),
                    'queries' => round((float) $entries->avg('metrics.queries'), 2),
                ],
                'violation_counts' => [
                    'peak_memory' => $entries->where('violations.memory', true)->count(),
                    'duration' => $entries->where('violations.duration', true)->count(),
                    'queries' => $entries->where('violations.queries', true)->count(),
                ],
                'peaks' => [
                    'peak_memory' => $entries->max('metrics.peak_memory'),
                    'duration' => $entries->max('metrics.duration'),
                    'queries' => $entries->max('metrics.queries'),
                ],
            ]);
        });
    }
}
