<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Utils;

use Atendwa\ActionWatch\Models\ActionWatchAggregate;
use Atendwa\ActionWatch\Models\ActionWatchEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

class FormatMetrics
{
    /**
     * @param  Collection<string, int|float>  $collection
     *
     * @return Collection<int, string>
     */
    public static function format(Collection $collection, bool $useSuffixes = true): Collection
    {
        $texts = $useSuffixes ? self::suffixes() : self::prefixes();

        return $collection
            ->map(fn ($value, $key) => str($texts[$key])->replace('{x}', (string) round($value))->toString())
            ->values();
    }

    public static function colour(string $state, ?Model $model = null): string
    {
        try {
            if ($model instanceof ActionWatchAggregate) {
                $class = app($model->class);

                $duration = $class::maxDurationMilliseconds();
                $queries = $class::maxQueriesCount();
                $memory = $class::maxPeakMemoryMbs();
            }

            if ($model instanceof ActionWatchEntry) {
                $constraints = $model->constraints;

                $memory = $constraints->get('peak_memory');
                $duration = $constraints->get('duration');
                $queries = $constraints->get('queries');
            }
        } catch (Throwable) {
            $duration = config('action-watch.constraints.max_duration_milliseconds');
            $memory = config('action-watch.constraints.max_peak_memory_usage_mbs');
            $queries = config('action-watch.constraints.max_queries_count');
        }

        $colours = [true => 'warning', false => 'success'];
        $stringable = str($state);

        $integer = $stringable->toInteger();

        return match (true) {
            $stringable->contains(['Duration', 'Milliseconds']) => $colours[$integer >= $duration],
            $stringable->contains(['Memory', 'Mbs']) => $colours[$integer >= $memory],
            $stringable->contains(['Queries']) => $colours[$integer >= $queries],
            default => 'gray'
        };
    }

    /**
     * @return array<string, string>
     */
    private static function suffixes(): array
    {
        return [
            'duration' => '{x} Milliseconds',
            'peak_memory' => '{x} Mbs',
            'queries' => '{x} Queries',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function prefixes(): array
    {
        return [
            'peak_memory' => 'Memory: {x}',
            'duration' => 'Duration: {x}',
            'queries' => 'Queries: {x}',
        ];
    }
}
