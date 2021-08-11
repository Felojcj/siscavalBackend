<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Graduate extends Model
{
    use HasFactory;
    use FilterQueryString;

    public $timestamps = false;

    protected $fillable = [
        'faculty',
        'program',
        'semester',
        'total',
        'status'
    ];

    protected $filters = [
        'faculty',
        'program',
        'semester',
    ];
}
