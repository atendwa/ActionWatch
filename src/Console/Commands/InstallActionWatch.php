<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Console\Commands;

use Atendwa\ActionWatch\Filament\Resources\ActionWatchAggregateResource;
use Atendwa\ActionWatch\Providers\ActionWatchServiceProvider;
use Atendwa\Support\Command;

class InstallActionWatch extends Command
{
    protected $signature = 'action-watch:install';

    protected $description = 'Install the ActionWatch package';

    protected string $provider = ActionWatchServiceProvider::class;

    protected array $resources = [ActionWatchAggregateResource::class];
}
