<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Filament\Resources\ActionWatchAggregateResource\Pages;

use Atendwa\ActionWatch\Filament\Resources\ActionWatchAggregateResource;
use Atendwa\Filakit\Pages\ListRecords;

class ListActionWatchAggregates extends ListRecords
{
    protected static string $resource = ActionWatchAggregateResource::class;
}
