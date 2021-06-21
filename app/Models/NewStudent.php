<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewStudent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'faculty',
        'program',
        'semester',
        'enrolled_poblado',
        'admitted_poblado',
        'newcomers_poblado',
        'enrolled_rionegro',
        'admitted_rionegro',
        'newcomers_rionegro',
        'enrolled_apartado',
        'admitted_apartado',
        'newcomers_apartado',
        'status'
    ];
}
