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
        Schema::create('action_watch_entries', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('class')->index();
            $blueprint->unsignedInteger('user_id')->index()->nullable();
            $blueprint->json('metrics')->nullable();
            $blueprint->json('constraints')->nullable();
            $blueprint->json('violations')->nullable();
            $blueprint->timestamps();
        });
    }
};
