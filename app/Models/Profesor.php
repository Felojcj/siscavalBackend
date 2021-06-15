<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

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
}
