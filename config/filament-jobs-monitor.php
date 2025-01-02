<?php

return [
    'resources' => [
        'enabled' => true,
        'label' => 'Fila',
        'plural_label' => 'Filas',
        'navigation_group' => 'Sistema',
        'navigation_icon' => 'heroicon-o-cpu-chip',
        'navigation_sort' => null,
        'navigation_count_badge' => false,
        'resource' => Croustibat\FilamentJobsMonitor\Resources\QueueMonitorResource::class,
        'cluster' => null,
    ],
    'pruning' => [
        'enabled' => true,
        'retention_days' => 7,
    ],
    'queues' => [
        'default'
    ],
];
