<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;


class EditProfile extends BaseEditProfile
{
    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }
    public function getFormActionsAlignment(): string | Alignment
    {
        return Alignment::Right;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Section::make('Informações Pessoais')
                            ->aside()
                            ->schema([
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                                Fieldset::make('settings')
                                    ->label('Cor do tema')
                                    ->schema([        
                                        ColorPicker::make('settings.color')
                                            ->label('Cor do tema')
                                            ->columnSpanFull()
                                            ->inLineLabel()
                                            ->default('#000000')   
                                            ->formatStateUsing(fn (?string $state):string => $state ?? config('filament.theme.colors.primary'))               
                                    ])
                            ])
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data'),



            ),
        ];
    }

    protected function afterSave(): void
    {
        redirect((request()->header('referer')));
    }

    
}

                   
  