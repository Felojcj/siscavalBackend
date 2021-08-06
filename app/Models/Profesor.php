<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Profesor extends Model
{
    use HasFactory;
    use FilterQueryString;

    public $timestamps = false;

    protected $fillable = [
        'semester',
        'campus',
        'faculty',
        'formation_level',
        'dedication',
        'total',
        'status'
    ];

    protected $filters = [
        'semester',
        'campus',
        'faculty',
        'formation_level',
        'dedication',
    ];
}
