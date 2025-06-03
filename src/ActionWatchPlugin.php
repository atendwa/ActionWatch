<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch;

use Atendwa\ActionWatch\Filament\Resources\ActionWatchAggregateResource;
use Atendwa\Filakit\Concerns\UsesPluginSetup;
use Atendwa\Filakit\Panel;
use Filament\Contracts\Plugin;

class ActionWatchPlugin implements Plugin
{
    use UsesPluginSetup;

    public function register(Panel|\Filament\Panel $panel): void
    {
        $panel->resources([ActionWatchAggregateResource::class]);
    }
}
