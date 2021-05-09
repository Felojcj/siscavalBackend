<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    protected $fillable = [
      'column_name',
      'data_type',
      'min_length',
      'max_length',
      'id_template',
      'valid_value'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class, 'id_template');
    }
}
