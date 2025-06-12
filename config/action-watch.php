<?php

declare(strict_types=1);

return [
    'constraints' => [
        'max_queries_count' => env('ACTION_WATCH_MAX_QUERIES_COUNT', 5),
        'max_peak_memory_usage_mbs' => env('ACTION_WATCH_MAX_PEAK_MEMORY_USAGE_MBS', 5),
        'max_duration_milliseconds' => env('ACTION_WATCH_MAX_DURATION_MILLISECONDS', 1000),
    ],
    'process_metrics_in_queue' => env('ACTION_WATCH_PROCESS_METRICS_IN_QUEUE', false),
    'user_attribute' => env('ACTION_WATCH_USER_ATTRIBUTE', 'name'),
    'retention_months' => env('ACTION_WATCH_RETENTION_MONTHS', 2),
    'resource' => [
        'cluster' => null,
        'icon' => 'heroicon-o-cog',
        'active_icon' => 'heroicon-s-cog',
        'group' => null,
        'sort' => 0,
        'record_title_attribute' => 'key',
    ],
];
