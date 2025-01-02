<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Attributes\Auth;

class Project extends Model
{

    protected $fillable = [
       'client_id',
       'name',
       'description',
       'start_date',
       'end_date',
       'status',
       'currency',
       'payment_type',
       'value',
       'notes',
    
    ];
    
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function worklogs()
    {
        return $this->hasMany(WorkLog::class);
    }

   
}

