<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Implemento;

class Prestamo extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha_inicial',
        'fecha_final',
        'estado',
        'cantidad_implemento_solicitado',
        'cantidad_implemento_entregado',
        'cantidad_implemento_devuelto',
        'cantidad_implemento_perdido',
        'cantidad_implemento_defectuoso'
    ];

    public function prestamo() {
        return $this->belongsToMany(User::class, Implemento::class,'id_user', 'id_implemento');
    }
}
