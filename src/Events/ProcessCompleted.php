<?php

declare(strict_types=1);

namespace Atendwa\ActionWatch\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ProcessCompleted
{
    use Dispatchable;

    /**
     * @param  array<string, int|float>  $metrics
     */
    public function __construct(public string $class, public array $metrics) {}
}
