<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
        'customer_name', // ✅ ajouté
        'note',          // ✅ ajouté
    ];

    // ✅ livree ajouté — nécessaire pour les ventes POS
    const STATUSES = ['en_attente', 'en_preparation', 'prete', 'livree', 'annulee'];

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

    // ✅ relation vendeur — BelongsTo maintenant importé
    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
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

    public function scopeByStatus($query, ?string $status)
    {
        if ($status && in_array($status, self::STATUSES)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSITION DE STATUT SÉCURISÉE
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
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // ✅ Référence lisible — VTE-000042 (POS) ou CMD-000042 (online)
    public function getReferenceAttribute(): string
    {
        $prefix = ($this->source ?? 'online') === 'pos' ? 'VTE' : 'CMD';
        return $prefix . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'en_attente'     => 'bg-yellow-100 text-yellow-700',
                'en_preparation' => 'bg-blue-100 text-blue-700',
                'prete'          => 'bg-green-100 text-green-700',
                'livree'         => 'bg-emerald-100 text-emerald-700', // ✅
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
                'livree'         => 'Livrée',  // ✅
                'annulee'        => 'Annulée',
                default          => ucfirst($this->status),
            }
        );
    }

    protected function formattedTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format((float) $this->total_price, 0, ',', ' ')
                . ' ' . config('app.currency', 'FCFA')
        );
    }

    protected function isEditable(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === 'en_attente'
        );
    }

    protected function isClosed(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->status, ['prete', 'livree', 'annulee']) // ✅ livree ajouté
        );
    }
}
