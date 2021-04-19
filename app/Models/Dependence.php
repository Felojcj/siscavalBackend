<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependence extends Model
{
    use HasFactory;

    protected $fillable = [
        'cost_center',
        'description',
        'email',
        'status',
      ];

      public function users()
      {
          return $this->hasMany(User::class);
      }
}
