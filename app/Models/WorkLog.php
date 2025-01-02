<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date',
        'project_id',
        'start_time',
        'end_time',
        'hours',
        'value_received',
    ];

   
    /**
     * Cálculo das horas trabalhadas com base no start_time e end_time.
     */
    public function setHoursAttribute($value)
    {
        if ($this->start_time && $this->end_time) {
            $start = strtotime($this->start_time);
            $end = strtotime($this->end_time);

            if ($start && $end && $end > $start) {
                // Calcular a quantidade de horas trabalhadas (em decimal)
                $this->attributes['hours'] = number_format(($end - $start) / 3600, 2);
            }
        }
    }

    /**
     * Cálculo do valor recebido baseado nas horas e valor por hora do projeto.
     */
    public function setValueReceivedAttribute($value)
    {
        if ($this->hours && $this->project_id) {
            $project = Project::find($this->project_id);
            if ($project) {
                // Calcular o valor recebido
                $this->attributes['value_received'] = number_format($this->hours * $project->value, 2);
            }
        }
    }

    /**
     * Definir evento 'saving' para garantir que os cálculos sejam feitos antes de salvar.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($worklog) {
            // Calcular e garantir que os campos sejam preenchidos
            $worklog->setHoursAttribute(null);
            $worklog->setValueReceivedAttribute(null);
        });
    }
    /**
     * Relacionamento com o projeto.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

   
}
