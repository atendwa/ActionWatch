<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Console\Commands;

use Atendwa\ActionWatch\Models\ActionWatchEntry;
use Illuminate\Console\Command;

class PruneActionWatchEntries extends Command
{
    protected $signature = 'action-watch:prune';

    protected $description = 'Prune old Action Watch entries';

    public function handle(): void
    {
        $this->call('model:prune', ['--model' => ActionWatchEntry::class]);
    }
}
