<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'lokasi_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'lokasi_id', 'lokasi_id');
    }

    public function absenharians()
    {
        return $this->hasMany(Absenharian::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function managedLokasis()
    {
        return $this->belongsToMany(Lokasi::class, 'petugas_lokasis')
                    ->withPivot('access_type')
                    ->withTimestamps();
    }

    public function createdLokasis()
    {
        return $this->managedLokasis()->wherePivot('access_type', 'creator');
    }

    public function assignedLokasis()
    {
        return $this->managedLokasis()->wherePivot('access_type', 'assigned');
    }
}
