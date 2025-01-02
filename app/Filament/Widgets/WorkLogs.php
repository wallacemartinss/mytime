<?php
namespace App\Filament\Widgets;

use App\Models\WorkLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\WorkLogResource\Pages\ListWorkLogs;
use App\Filament\Resources\WorkHourResource\Pages\ListWorkHours;


class WorkLogs extends StatsOverviewWidget
{
  
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListWorkLogs::class;
    }

    public function getStats(): array
    {
        return [
            Stat::make('Quantidade de Ocorrências', $this->getPageTableQuery()->count()),
            Stat::make('Quantidade de Horas', $this->getPageTableQuery()->sum('hours')),
            Stat::make('Valor Estimado', 'R$ ' . $this->getPageTableQuery()->sum('value_received'), 2, ',', '.'),
        ];
    }

    
}
