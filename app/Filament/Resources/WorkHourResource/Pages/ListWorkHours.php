<?php

namespace App\Filament\Resources\WorkHourResource\Pages;

use Filament\Actions;
use App\Filament\Widgets\WorkHours;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\WorkHourResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListWorkHours extends ListRecords
{
    use ExposesTableToWidgets;
    
    protected static string $resource = WorkHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WorkHours::class,
        ];
    }
}
