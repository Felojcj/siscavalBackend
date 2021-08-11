<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Enrolled extends Model
{
    use HasFactory;
    use FilterQueryString;

    public $timestamps = false;

    protected $fillable = [
        'faculty',
        'program',
        'semester',
        'newcomers_poblado',
        'former_students_poblado',
        'total_poblado',
        'newcomers_rionegro',
        'former_students_rionegro',
        'total_rionegro',
        'newcomers_apartado',
        'former_students_apartado',
        'total_apartado',
        'grand_total',
        'status'
    ];

    protected $filters = [
        'semester',
        'program',
        'faculty',
    ];
}
