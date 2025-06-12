<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Filament\Resources\ActionWatchAggregateResource\RelationManagers;

use Atendwa\ActionWatch\Utils\FormatMetrics;
use Atendwa\Filakit\Concerns\CustomizesResourceTable;
use Atendwa\Filakit\RelationManager;
use Filament\Tables\Table;
use Throwable;

class ActionWatchEntryRelationManager extends RelationManager
{
    use CustomizesResourceTable;

    protected static string $relationship = 'entries';

    protected static bool $hasViewAction = false;

    /**
     * @throws Throwable
     */
    public function table(Table $table): Table
    {
        $attribute = asString(config('action-watch.user_attribute'));

        self::$customTable = $table->columns([
            column('user.' . $attribute)->label("User's " . $attribute),
            column('formatted_metrics')->label('Metrics')->badge()
                ->color(fn ($record): string => FormatMetrics::colour($record->getAttribute('formatted_metrics'), $record->getAttribute('class'))),
            column('formatted_constraints')->label('Constraints')->badge(),
        ]);

        return self::customTable();
    }
}
