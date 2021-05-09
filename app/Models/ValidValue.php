<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidValue extends Model
{
    use HasFactory;

    protected $fillable = [
      'value',
      'id_detail',
      'status'
    ];

    public function detail()
    {
        return $this->belongsTo(Detail::class, 'id_detail');
    }
}
