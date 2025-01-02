<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\WorkLog;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Imports\WorkLogImporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use App\Filament\Resources\WorkLogResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WorkLogResource\RelationManagers;

class WorkLogResource extends Resource
{
    protected static ?string $model = WorkLog::class;

    protected static ?string $navigationIcon = 'fas-clock';
    protected static ?string $navigationGroup = 'Projetos';
    protected static ?string $navigationLabel = 'Registro de hora';
    protected static ?string $modelLabel = 'Registro de horas';
    protected static ?string $modelLabelPlural = "Registro de horas";
    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('project_id')
                    ->relationship('project', 'name'),

                DatePicker::make('date')
                    ->required(),

                TimePicker::make('start_time')
                    ->label('Start Time')
                    ->required()
                    ->reactive(),

                TimePicker::make('end_time')
                    ->label('End Time')
                    ->required()
                    ->reactive() // Tornar o campo reativo para atualizar o valor ao ser alterado
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // Calcular as horas
                        $startTime = $get('start_time');
                        if ($startTime && $state) {
                            $start = strtotime($startTime);
                            $end = strtotime($state);

                            if ($start && $end && $end > $start) {
                                // Calcular as horas em formato decimal
                                $hours = ($end - $start) / 3600;
                                $set('hours', number_format($hours, 2));

                                // Calcular o valor recebido com base no projeto
                                $projectId = $get('project_id');
                                if ($projectId) {
                                    $project = Project::find($projectId);
                                    if ($project) {
                                        // Calcular o valor a ser recebido
                                        $valueReceived = $hours * $project->value;
                                        $set('value_received', number_format($valueReceived, 2));
                                    }
                                }
                            }
                        }
                    }),

                TextInput::make('hours')
                    ->label('Hours')
                    ->disabled()
                    ->dehydrated(true)
                    ->reactive(),

                TextInput::make('value_received')
                    ->label('Value Received')
                    ->dehydrated(true)
                    ->disabled()
                    ->reactive(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Data Lançamento')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Projeto')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Inicio'),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Fim'),

                Tables\Columns\TextColumn::make('hours')
                    ->label('Horas')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value_received')
                    ->label('Valor a Receber (R$)')
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
                Filter::make('this_month')
                    ->default()
                    ->label('Este Mês')
                    ->query(function ($query) {
                        return $query->whereMonth('date', Carbon::now()->month);
                    }),

                Filter::make('last_month')
                    ->label('Mês Anterior')
                    ->query(function ($query) {
                        return $query->whereMonth('date', Carbon::now()->subMonth()->month);
                    }),

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
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])->iconButton(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            ->headerActions([
                ImportAction::make()
                    ->importer(WorkLogImporter::class),

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
            'index' => Pages\ListWorkLogs::route('/'),
            'create' => Pages\CreateWorkLog::route('/create'),
            'edit' => Pages\EditWorkLog::route('/{record}/edit'),
        ];
    }
}
