<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'client_id',
        'start_date',
        'end_date',
        'total_hours',
        'total_value',
        'status',
        'payment_method',
        'payment_status',
        'payment_date',
        'atachment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    
}
