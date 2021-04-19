<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prestamo;

class Implemento extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'placa',
        'descripcion',
        'valor',
        'cantidad',
        'cantidad_prestados'
    ];

    public function prestamos(){
        return $this->hasMany(Prestamo::class);
    }
}
