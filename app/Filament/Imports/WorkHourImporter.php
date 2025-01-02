<?php

namespace App\Filament\Imports;

use App\Models\WorkHour;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class WorkHourImporter extends Importer
{
    protected static ?string $model = WorkHour::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('user_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('user_setting_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('start_time')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('lunch_start'),
            ImportColumn::make('lunch_end'),
            ImportColumn::make('end_time')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('hourly_rate')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('extra_hours')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('extra_value')
                ->numeric()
                ->rules(['integer']),
        ];
    }

    public function resolveRecord(): ?WorkHour
    {
        // return WorkHour::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new WorkHour();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your work hour import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
