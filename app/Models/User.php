<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cateringRequests()
    {
        return $this->hasMany(CateringRequest::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers métier
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendeur(): bool
    {
        return $this->role === 'vendeur';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor Badge Role (UI Premium)
    |--------------------------------------------------------------------------
    */

    protected function roleBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->role) {
                    'admin' => 'bg-red-100 text-red-700',
                    'vendeur' => 'bg-blue-100 text-blue-700',
                    default => 'bg-green-100 text-green-700',
                };
            }
        );
    }

    protected function roleLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->role) {
                    'admin' => 'Administrateur',
                    'vendeur' => 'Vendeur',
                    default => 'Client',
                };
            }
        );
    }
}