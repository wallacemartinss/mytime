<?php

namespace App\Filament\Resources\WorkHourResource\Pages;

use Filament\Actions;
use App\Filament\Widgets\WorkHours;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\WorkHourResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ManageWorkHours extends ManageRecords
{
    protected static string $resource = WorkHourResource::class;

    use ExposesTableToWidgets;

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
