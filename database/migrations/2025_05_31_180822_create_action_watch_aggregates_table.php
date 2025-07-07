<?php

declare(strict_types=1);

use Atendwa\Support\Concerns\Support\InferMigrationDownMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    use InferMigrationDownMethod;

    public function up(): void
    {
        Schema::create('action_watch_aggregates', function (Blueprint $blueprint): void {
            $blueprint->id();
//            $blueprint->slug();
            $blueprint->string('class');
            $blueprint->integer('occurrences');
            $blueprint->json('averages')->nullable();
            $blueprint->json('peaks')->nullable();
            $blueprint->json('violation_counts')->nullable();
            $blueprint->timestamps();
        });
    }
};
