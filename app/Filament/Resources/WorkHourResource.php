<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\WorkHour;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserSetting;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WorkHourResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WorkHourResource\RelationManagers;

class WorkHourResource extends Resource
{
    protected static ?string $model = WorkHour::class;

    protected static ?string $navigationIcon = 'fas-clock';
    protected static ?string $navigationGroup = 'Empresarial';
    protected static ?string $navigationLabel = 'Hora Extra';
    protected static ?string $modelLabel = 'Horas Extra';
    protected static ?string $modelLabelPlural = "Hora Extra";
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_setting_id')
                    ->label('Empresa')
                    ->required()
                    ->options(
                        UserSetting::where('user_id', Auth::id())
                            ->pluck('company_name', 'id')
                    )
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Busca o valor da hora do UserSetting selecionado
                        if ($state) {
                            $valuePerHour = UserSetting::find($state)?->extra_hour_rate ?? 0;
                            $set('hourly_rate', $valuePerHour);
                        }
                    })
                    ->searchable(),

                Forms\Components\TextInput::make('hourly_rate')
                    ->label('Valor da Hora (R$)')
                    ->disabled()
                    ->numeric()
                    ->dehydrated(false)
                    ->hidden()
                    ->prefix('R$'),

                Forms\Components\DatePicker::make('date')
                    ->required(),

                Forms\Components\TimePicker::make('start_time')
                    ->label('Hora de Entrada')
                    ->reactive()
                    ->required(),

                Forms\Components\TimePicker::make('lunch_start')
                    ->label('Início do Almoço')
                    ->reactive()
                    ->nullable(),

                Forms\Components\TimePicker::make('lunch_end')
                    ->label('Fim do Almoço')
                    ->reactive()
                    ->nullable(),

                Forms\Components\TimePicker::make('end_time')
                    ->label('Hora de Saída')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $get) {
                        // Calcula as horas extras automaticamente


                        $start_time = $get('start_time');
                        $lunchStart = $get('lunch_start');
                        $lunchEnd = $get('lunch_end');
                        $end_time = $get('end_time');
                        $hourlyRate = $get('hourly_rate');

                        if ($start_time && $end_time) {
                            $workedHours = (strtotime($end_time) - strtotime($start_time)) / 3600;

                            // Subtrai o intervalo de almoço, se informado
                            if ($lunchStart && $lunchEnd) {
                                $lunchHours = (strtotime($lunchEnd) - strtotime($lunchStart)) / 3600;
                                $workedHours -= $lunchHours;
                            }

                            $extraHours = max(0, $workedHours - 8); // Considera 8 horas como jornada padrão
                            $set('extra_hours', $extraHours);

                            // Calcula o valor das horas extras
                            if ($hourlyRate) {
                                $set('extra_value', $extraHours * $hourlyRate); // 50% adicional
                            }
                        }
                    }),

                Forms\Components\TextInput::make('extra_hours')
                    ->label('Horas Extras')
                    ->disabled()
                    ->numeric()
                    ->dehydrated(true)
                    ->helperText('Calculado automaticamente com base na jornada.'),

                Forms\Components\TextInput::make('extra_value')
                    ->label('Valor das Horas Extras (R$)')
                    ->disabled()
                    ->numeric()
                    ->dehydrated(true)
                    ->prefix('R$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('userSetting.company_name')
                    ->label('Empresa'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Data Hora Extra')
                    ->Date('d/m/Y'),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Entrada')
                    ->Time(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Saída')
                    ->Time(),
                Tables\Columns\TextColumn::make('extra_hours')
                    ->label('Horas Extras'),
                Tables\Columns\TextColumn::make('extra_value')
                    ->label('Valor Horas Extras (R$)')
                    ->money('BRL'),
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
                // Filtro para Esse Mês
                Filter::make('this_month')
                    ->label('Este Mês')
                    ->default()
                    ->query(function ($query) {
                        return $query->whereMonth('date', Carbon::now()->month);
                    }),
    
                // Filtro para Mês Anterior
                Filter::make('last_month')
                    ->label('Mês Anterior')
                    ->query(function ($query) {
                        return $query->whereMonth('date', Carbon::now()->subMonth()->month);
                    }),
    
                // Filtro para intervalo de datas
                Filter::make('date_range')
                    ->label('Intervalo de Datas')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')->label('Data Início'),
                        Forms\Components\DatePicker::make('end_date')->label('Data Fim'),
                    ])
                    ->query(function ($query, $data) {
                        if (isset($data['start_date']) && isset($data['end_date'])) {
                            return $query->whereBetween('date', [
                                Carbon::parse($data['start_date'])->startOfDay(),
                                Carbon::parse($data['end_date'])->endOfDay(),
                            ]);
                        }
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkHours::route('/'),
            'create' => Pages\CreateWorkHour::route('/create'),
            'edit' => Pages\EditWorkHour::route('/{record}/edit'),
        ];
    }
}
