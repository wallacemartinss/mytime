<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Client;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\Document;
use App\Filament\Resources\ClientResource\Pages;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClientResource\RelationManagers;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'fas-building-user';
    protected static ?string $navigationGroup = 'Projetos';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $modelLabel = 'Clientes';
    protected static ?string $modelLabelPlural = "Clientes";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Document::make('document_number')
                    ->label('CPF ou CNPJ')
                    ->required()
                    ->dynamic()
                    ->validation(false),

                TextInput::make('email')
                    ->label('E-mail de contato')
                    ->email()
                    ->required()
                    ->maxLength(255),

                PhoneNumber::make('phone')
                    ->label('Telefone de contato')
                    ->mask('(99) 99999-9999'),                    

                Cep::make('zip_code')
                    ->required()
                    ->label('CEP')
                    ->viaCep(
                        mode: 'suffix', // Determines whether the action should be appended to (suffix) or prepended to (prefix) the cep field, or not included at all (none).
                        errorMessage: 'CEP inválido.', // Error message to display if the CEP is invalid.
                
                        /**
                         * Other form fields that can be filled by ViaCep.
                         * The key is the name of the Filament input, and the value is the ViaCep attribute that corresponds to it.
                         * More information: https://viacep.com.br/
                         */
                        setFields: [
                            'address' => 'logradouro',
                            'district' => 'bairro',
                            'city' => 'localidade',
                            'state' => 'uf'

                        ]
                    ),

                TextInput::make('address')
                    ->label('Endereço')
                    ->maxLength(255),

                TextInput::make('city')
                    ->label('Cidade')
                    ->maxLength(255),

                TextInput::make('state')
                    ->label('Estado')
                    ->maxLength(255),

                TextInput::make('country')
                    ->label('País')
                    ->maxLength(255),
               
                Toggle::make('status')
                    ->label('Ativo')
                    ->required(),

                TextInput::make('notes')
                    ->label('Observações')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable(),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
