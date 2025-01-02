<?php

namespace App\Filament\Resources\WorkLogResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\WorkLogResource;
use App\Filament\Widgets\WorkLogs;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListWorkLogs extends ListRecords
{

    use ExposesTableToWidgets;
    
    protected static string $resource = WorkLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WorkLogs::class,
        ];
    }
}
