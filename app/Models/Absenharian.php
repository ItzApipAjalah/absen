<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absenharian extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'total_check_in',
        'total_time',
        'total_late'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
