<?php

namespace App\Filament\Exports;

use App\Models\WorkHour;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class WorkHourExporter extends Exporter
{
    protected static ?string $model = WorkHour::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user_id'),
            ExportColumn::make('user_setting_id'),
            ExportColumn::make('date'),
            ExportColumn::make('start_time'),
            ExportColumn::make('lunch_start'),
            ExportColumn::make('lunch_end'),
            ExportColumn::make('end_time'),
            ExportColumn::make('hourly_rate'),
            ExportColumn::make('extra_hours'),
            ExportColumn::make('extra_value'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your work hour export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
