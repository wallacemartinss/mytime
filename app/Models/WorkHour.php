<?php

// app/Models/WorkHour.php
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_setting_id',
        'date',
        'start_time',
        'lunch_start',
        'lunch_end',
        'end_time',
        'extra_hours',
        'extra_value',
        'hourly_rate',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'lunch_start' => 'datetime:H:i',
        'lunch_end' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function calculateExtraHours()
    {
        $totalWorked = $this->end_time->diffInMinutes($this->start_time) -
            $this->lunch_end->diffInMinutes($this->lunch_start);
        $normalHours = 480; // 8 horas em minutos
        $extraMinutes = max(0, $totalWorked - $normalHours);
        $this->extra_hours = $extraMinutes / 60; // Converter minutos para horas
        $this->extra_value = $this->extra_hours * $this->hourly_rate * 1.5; // 50% adicional
        $this->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userSetting()
    {
        return $this->belongsTo(UserSetting::class);
    }

    protected static function booted()
    {
        
        // Evento para criação
        static::creating(function ($userSetting) {
            $userSetting->user_id = Auth::id();
        });

        // Evento para atualização
        static::updating(function ($userSetting) {
            if (!$userSetting->user_id) {
                $userSetting->user_id = Auth::id();
            }
        });
    }
}
