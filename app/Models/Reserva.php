<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Escenario;

class Reserva extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'fecha_inicial',
        'fecha_final',
        'estado',
    ];

    public function reserva() {
        return $this->belongsToMany(User::class, Escenario::class,'id_user', 'id_reserva');
    }
}
