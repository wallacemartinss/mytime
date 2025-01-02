<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'gross_salary',
        'monthly_hours',
        'extra_hour_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateExtraHourRate()
    {
        if ($this->monthly_hours > 0) {
            $this->extra_hour_rate = ($this->gross_salary / $this->monthly_hours) * 1.5; // 50% adicional
        }
    }

    protected static function booted()
    {
        static::saving(function ($userSetting) {
            $userSetting->calculateExtraHourRate();
        });

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
