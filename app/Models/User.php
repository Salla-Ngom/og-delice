<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cateringRequests()
    {
        return $this->hasMany(CateringRequest::class);
    }

    // Helpers métier
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isVendeur()
    {
        return $this->role === 'vendeur';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }
}
