<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefectionRate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'faculty',
        'program',
        'semester',
        'enrolled_poblado',
        'academic_retirement_poblado',
        'voluntary_retirement_poblado',
        'enrolled_rionegro',
        'academic_retirement_rionegro',
        'voluntary_retirement_rionegro',
        'enrolled_apartado',
        'academic_retirement_apartado',
        'voluntary_retirement_apartado',
        'enrolled_total',
        'academic_retirement_total',
        'voluntary_retirement_total',
        'defection_rate',
        'status'
    ];
}
