<?php
namespace App\Filament\Widgets;

use App\Models\WorkHour;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\WorkHourResource\Pages\ListWorkHours;


class WorkHours extends StatsOverviewWidget
{
  
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListWorkHours::class;
    }

    public function getStats(): array
    {
        //$totalInputs = WorkHour::count();
        //$totalHours = WorkHour::sum('extra_hours');
        //$totalValue = number_format(WorkHour::sum('extra_value'), 2, ',', '.');

        return [
            Stat::make('Quantidade de Ocorrências', $this->getPageTableQuery()->count()),
            Stat::make('Quantidade de Horas', $this->getPageTableQuery()->sum('extra_hours')),
            Stat::make('Valor Estimado', 'R$ ' . $this->getPageTableQuery()->sum('extra_value'), 2, ',', '.'),
        ];
    }

    
}
