<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ✅ role et is_active retirés — ne jamais les laisser mass-assignables
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
        // 'role' => \App\Enums\UserRole::class, // ← décommenter quand l'Enum sera créé
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
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
    | HELPERS MÉTIER
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

    public function canAccessAdmin(): bool
    {
        return $this->isAdmin() || $this->isVendeur();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS — syntaxe moderne Laravel 9+
    |--------------------------------------------------------------------------
    */

    // Badge CSS TailwindCSS selon le rôle
    protected function roleBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->role) {
                'admin'   => 'bg-red-100 text-red-700',
                'vendeur' => 'bg-blue-100 text-blue-700',
                default   => 'bg-green-100 text-green-700',
            }
        );
    }

    // Label lisible selon le rôle
    protected function roleLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->role) {
                'admin'   => 'Administrateur',
                'vendeur' => 'Vendeur',
                default   => 'Client',
            }
        );
    }

    // Statut lisible
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_active ? 'Actif' : 'Inactif'
        );
    }

    // Badge CSS statut
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_active
                ? 'bg-green-100 text-green-700'
                : 'bg-red-100 text-red-700'
        );
    }
}
