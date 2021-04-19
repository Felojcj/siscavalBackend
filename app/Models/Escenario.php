<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escenario extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'codigo',
        'imagen',
        'descripcion',
        'medidas'
    ];

    public function reservas(){
        return $this->hasMany(Reserva::class);
    }
}
