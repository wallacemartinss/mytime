<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserSetting;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserSettingResource\Pages;
use App\Filament\Resources\UserSettingResource\RelationManagers;

class UserSettingResource extends Resource
{
    protected static ?string $model = UserSetting::class;

   
    protected static ?string $navigationIcon = 'fas-building-user';
    protected static ?string $navigationGroup = 'Empresarial';
    protected static ?string $navigationLabel = 'Dados Trabalho';
    protected static ?string $modelLabel = 'Parametro';
    protected static ?string $modelLabelPlural = "Criar Parametro";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->label('Nome da Empresa'),

                Forms\Components\TextInput::make('gross_salary')
                    ->numeric()
                    ->required()
                    ->label('Salário Bruto (R$)')
                    ->reactive() // Campo reativo
                    ->afterStateUpdated(fn(callable $set, $get) => $set('extra_hour_rate', static::calculateExtraHourRate($get('gross_salary'), $get('monthly_hours')))),

                Forms\Components\Select::make('monthly_hours')
                    ->required()
                    ->label('Horas Mensais Contratadas')
                    ->options([
                        160 => '160 Horas',
                        180 => '180 Horas',
                        200 => '200 Horas',
                        220 => '220 Horas',
                    ])
                    ->reactive() // Campo reativo
                    ->afterStateUpdated(fn(callable $set, $get) => $set('extra_hour_rate', static::calculateExtraHourRate($get('gross_salary'), $get('monthly_hours')))),

                Forms\Components\TextInput::make('extra_hour_rate')
                    ->numeric()
                    ->disabled() // Somente leitura
                    ->label('Valor da Hora Extra (R$)')
                    ->helperText('Calculado automaticamente com base no salário bruto e nas horas mensais.')
                    ->dehydrated(false) // Não enviar para o banco
                    ->afterStateHydrated(fn(callable $set, $get) => $set('extra_hour_rate', static::calculateExtraHourRate($get('gross_salary'), $get('monthly_hours')))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Empresa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_salary')
                    ->label('Salário Bruto')
                    ->money('BRL', true),
                Tables\Columns\TextColumn::make('monthly_hours')
                    ->label('Horas Contratadas'),
                Tables\Columns\TextColumn::make('extra_hour_rate')
                    ->label('Hora Extra (R$)')
                    ->money('BRL', true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUserSettings::route('/'),
        ];
    }
    /**
     * Calcula o valor da hora extra.
     */
    private static function calculateExtraHourRate(?float $grossSalary, ?int $monthlyHours): ?float
    {
        if (!$grossSalary || !$monthlyHours || $monthlyHours <= 0) {
            return null;
        }

        return round(($grossSalary / $monthlyHours) * 1.5, 2); // Adicional de 50%
    }
}
