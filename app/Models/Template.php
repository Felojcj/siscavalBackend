<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'description',
      'id_dependence',
      'status'
    ];

    public function dependence()
    {
        return $this->belongsTo(Dependence::class,'id_dependence');
    }
}
