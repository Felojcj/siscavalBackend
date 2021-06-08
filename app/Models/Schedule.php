<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
      'start_date',
      'end_date',
      'implementation_date',
      'id_user',
      'id_template',
      'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'id_template');
    }
}
