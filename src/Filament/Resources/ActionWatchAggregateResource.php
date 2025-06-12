<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Filament\Resources;

use Atendwa\ActionWatch\Filament\Resources\ActionWatchAggregateResource\Pages\ListActionWatchAggregates;
use Atendwa\ActionWatch\Filament\Resources\ActionWatchAggregateResource\Pages\ViewActionWatchAggregate;
use Atendwa\ActionWatch\Filament\Resources\ActionWatchAggregateResource\RelationManagers\ActionWatchEntryRelationManager;
use Atendwa\ActionWatch\Utils\FormatMetrics;
use Atendwa\Filakit\Concerns\CustomizesFilamentResource;
use Atendwa\Filakit\Resource;
use Filament\Clusters\Cluster;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Table;
use Throwable;

class ActionWatchAggregateResource extends Resource
{
    use CustomizesFilamentResource;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Fieldset::make('')->schema([
                textEntry('class'),
                textEntry('occurrences'),
            ]),
            Fieldset::make('')->columns(3)->schema([
                textEntry('formatted_averages')->badge()->label('Averages')
                    ->color(fn ($record): string => FormatMetrics::colour($record->formatted_averages, $record->getAttribute('class'))),
                textEntry('formatted_peaks')->badge()->label('Peaks')
                    ->color(fn ($record): string => FormatMetrics::colour($record->formatted_peaks, $record->getAttribute('class'))),
                textEntry('formatted_violation_counts')->label('Violation Counts')->badge()->color('gray'),
            ]),
        ]);
    }

    /**
     * @throws Throwable
     */
    public static function table(Table $table): Table
    {
        self::$customTable = $table->columns([
            column('class'),
            column('occurrences'),
            column('formatted_averages')->label('Averages')->badge()
                ->color(fn ($record): string => FormatMetrics::colour($record->formatted_metrics, $record->getAttribute('class'))),
            column('formatted_peaks')->label('Peaks')->badge()
                ->color(fn ($record): string => FormatMetrics::colour($record->formatted_peaks, $record->getAttribute('class'))),
            column('formatted_violation_counts')->label('Violation Counts')->badge(),
        ]);

        return self::customTable();
    }

    /**
     * @return array<PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListActionWatchAggregates::route('/'),
            'view' => ViewActionWatchAggregate::route('/view/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            ActionWatchEntryRelationManager::class,
        ];
    }

    public static function getCluster(): ?string
    {
        try {
            $cluster = app(asString(config('action-watch.resource.cluster')));

            return match ($cluster instanceof Cluster) {
                true => $cluster::class,
                false => null,
            };
        } catch (Throwable) {
            return null;
        }
    }

    public static function getNavigationSort(): ?int
    {
        return asInteger(config('action-watch.resource.sort'));
    }

    public static function getNavigationGroup(): ?string
    {
        $group = config('action-watch.resource.group');

        return match (is_string($group)) {
            true => $group,
            false => null,
        };
    }

    public static function getNavigationIcon(): string
    {
        $icon = config('action-watch.resource.icon');

        return match (is_string($icon)) {
            false => 'heroicon-o-rectangle-stack',
            true => $icon,
        };
    }

    public static function getActiveNavigationIcon(): string
    {
        $icon = config('action-watch.resource.active_icon');

        return match (is_string($icon)) {
            false => self::getNavigationIcon(),
            true => $icon,
        };
    }

    public static function getRecordTitleAttribute(): ?string
    {
        $title = config('action-watch.resource.record_title_attribute');

        return match (is_string($title)) {
            true => $title,
            false => null,
        };
    }
}
