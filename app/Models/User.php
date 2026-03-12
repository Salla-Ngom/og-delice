<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ✅ role et is_active hors fillable — jamais mass-assignables
    // ✅ phone et delivery_address OK dans fillable (pas sensibles)
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'delivery_address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
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
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

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

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_active ? 'Actif' : 'Inactif'
        );
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_active
                ? 'bg-green-100 text-green-700'
                : 'bg-red-100 text-red-700'
        );
    }

    // ✅ Affichage formaté : 771234567 → 77 123 45 67
    protected function formattedPhone(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->phone || strlen($this->phone) !== 9) {
                    return $this->phone;
                }
                return substr($this->phone, 0, 2) . ' '
                     . substr($this->phone, 2, 3) . ' '
                     . substr($this->phone, 5, 2) . ' '
                     . substr($this->phone, 7, 2);
            }
        );
    }

    // ✅ true si le client a renseigné une adresse de livraison
    protected function hasDeliveryAddress(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty($this->delivery_address)
        );
    }
}