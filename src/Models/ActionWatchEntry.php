<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Models;

use Atendwa\ActionWatch\Utils\FormatMetrics;
use Atendwa\Support\Concerns\Models\Prunable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class ActionWatchEntry extends Model
{
    use Prunable;

    protected $casts = [
        'constraints' => 'collection',
        'violations' => 'collection',
        'metrics' => 'collection',
        'user_id' => 'int',
    ];

    protected $guarded = ['id'];

    /**
     * @return BelongsTo<Model, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(asString(config('auth.providers.users.model')));
    }

    protected static function booted(): void
    {
        parent::creating(fn (Model $model) => $model->setAttribute('user_id', auth()->id()));
    }

    /**
     * @return Attribute<string, Collection<string, string>>
     */
    protected function formattedMetrics(): Attribute
    {
        return Attribute::make(fn (): Collection => FormatMetrics::format($this->getAttribute('metrics')));
    }

    /**
     * @return Attribute<string, Collection<string, string>>
     */
    protected function formattedConstraints(): Attribute
    {
        return Attribute::make(fn (): Collection => FormatMetrics::format($this->getAttribute('constraints')));
    }

    protected function retentionMonths(): int
    {
        return asInteger(config('action-watch.retention_months'));
    }
}
