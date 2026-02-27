<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use HasFactory;

    // user_id assigné explicitement dans OrderController
    // status assigné via transitionTo() uniquement
    protected $fillable = [
        'total_price',
    ];

    const STATUSES = ['en_attente', 'en_preparation', 'prete', 'annulee'];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeEnAttente($query)
    {
        return $query->where('status', 'en_attente');
    }

    public function scopeEnPreparation($query)
    {
        return $query->where('status', 'en_preparation');
    }

    public function scopePrete($query)
    {
        return $query->where('status', 'prete');
    }

    public function scopeAnnulee($query)
    {
        return $query->where('status', 'annulee');
    }

    // Filtre générique sécurisé — vérifie que le statut est valide
    public function scopeByStatus($query, ?string $status)
    {
        if ($status && in_array($status, self::STATUSES)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Commandes des 30 derniers jours — dashboard
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSITION DE STATUT SÉCURISÉE
    | Utiliser cette méthode plutôt que $order->status = '...'
    |--------------------------------------------------------------------------
    */

    public function transitionTo(string $newStatus): bool
    {
        if (!in_array($newStatus, self::STATUSES)) {
            return false;
        }
        $this->status = $newStatus;
        return $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS — syntaxe moderne Laravel 9+
    |--------------------------------------------------------------------------
    */

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'en_attente'     => 'bg-yellow-100 text-yellow-700',
                'en_preparation' => 'bg-blue-100 text-blue-700',
                'prete'          => 'bg-green-100 text-green-700',
                'annulee'        => 'bg-red-100 text-red-700',
                default          => 'bg-gray-100 text-gray-700',
            }
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'en_attente'     => 'En attente',
                'en_preparation' => 'En préparation',
                'prete'          => 'Prête',
                'annulee'        => 'Annulée',
                default          => ucfirst($this->status),
            }
        );
    }

    // Prix formaté avec la devise configurée dans config/app.php
    protected function formattedTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format((float) $this->total_price, 0, ',', ' ')
                . ' ' . config('app.currency', 'FCFA')
        );
    }

    // Commande encore modifiable par l'admin ?
    protected function isEditable(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === 'en_attente'
        );
    }

    // Commande terminée (succès ou annulation)
    protected function isClosed(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->status, ['prete', 'annulee'])
        );
    }
}
