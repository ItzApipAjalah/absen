<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $fillable = [
        'jadwal_id',
        'check_in_time',
        'check_out_time',
        'is_telat',
        'durasi_telat'
    ];

    protected $casts = [
        'is_telat' => 'boolean',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Jadwal::class,
            'id', // Foreign key on jadwals table
            'lokasi_id', // Foreign key on users table
            'jadwal_id', // Local key on absens table
            'lokasi_id' // Local key on jadwals table
        );
    }
}
