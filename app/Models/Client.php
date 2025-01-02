<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
   protected $fillable = [
       'user_id',
       'name',
       'document_number',
       'email',
       'phone',
       'address',
       'city',
       'state',
       'country',
       'zip_code',
       'status',
       'notes',
   ];

   public function projects()
    {
        return $this->hasMany(Project::class);
    }

    protected static function booted()
    {        
        // Evento para criação
        static::creating(function ($Client) {
            $Client->user_id = Auth::id();
        });

        // Evento para atualização
        static::updating(function ($Client) {
            if (!$Client->user_id) {
                $Client->user_id = Auth::id();
            }
        });
    }
    
}
