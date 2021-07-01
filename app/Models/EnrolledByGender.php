<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolledByGender extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'faculty',
        'program',
        'semester',
        'male_newcomers_poblado',
        'female_newcomers_poblado',
        'male_newcomers_rionegro',
        'female_newcomers_rionegro',
        'male_newcomers_apartado',
        'female_newcomers_apartado',
        'male_former_students_poblado',
        'female_former_students_poblado',
        'male_former_students_rionegro',
        'female_former_students_rionegro',
        'male_former_students_apartado',
        'female_former_students_apartado',
        'male_total_students_poblado',
        'female_total_students_poblado',
        'male_total_students_rionegro',
        'female_total_students_rionegro',
        'male_total_students_apartado',
        'female_total_students_apartado',
        'total',
        'status'
    ];
}
