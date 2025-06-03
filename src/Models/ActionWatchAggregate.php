<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Models;

use Atendwa\ActionWatch\Utils\FormatMetrics;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class ActionWatchAggregate extends Model
{
    protected $table = 'action_watch_aggregates';

    protected $guarded = ['id'];

    protected $casts = [
        'violation_counts' => 'collection',
        'averages' => 'collection',
        'peaks' => 'collection',
    ];

    /**
     * @return HasMany<ActionWatchEntry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(ActionWatchEntry::class, 'class', 'class');
    }

    /**
     * @return Attribute<string, Collection<string, string>>
     */
    protected function formattedAverages(): Attribute
    {
        return Attribute::make(fn (): Collection => FormatMetrics::format($this->getAttribute('averages')));
    }

    /**
     * @return Attribute<string, Collection<string, string>>
     */
    protected function formattedPeaks(): Attribute
    {
        return Attribute::make(fn (): Collection => FormatMetrics::format($this->getAttribute('peaks')));
    }

    /**
     * @return Attribute<string, Collection<string, string>>
     */
    protected function formattedViolationCounts(): Attribute
    {
        return Attribute::make(
            fn (): Collection => FormatMetrics::format($this->getAttribute('violation_counts'), false),
        );
    }
}
