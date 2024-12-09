<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude'
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function petugas()
    {
        return $this->belongsToMany(User::class, 'petugas_lokasis')
                    ->withPivot('access_type')
                    ->withTimestamps();
    }

    public function creator()
    {
        return $this->petugas()->wherePivot('access_type', 'creator');
    }
}
