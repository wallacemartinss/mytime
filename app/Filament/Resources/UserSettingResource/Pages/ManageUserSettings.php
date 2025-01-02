<?php

namespace App\Filament\Resources\UserSettingResource\Pages;

use App\Filament\Resources\UserSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

class ManageUserSettings extends ManageRecords
{
    protected static string $resource = UserSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
}
