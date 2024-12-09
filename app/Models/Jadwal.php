<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'lokasi_id',
        'start_time',
        'end_time',
        'late_tolerance',
        'status',
        'hari'
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Lokasi::class,
            'id', // Foreign key on lokasis table
            'lokasi_id', // Foreign key on users table
            'lokasi_id', // Local key on jadwals table
            'id' // Local key on lokasis table
        );
    }
}
