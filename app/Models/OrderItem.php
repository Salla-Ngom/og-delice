<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * IMPORTANT : unit_price et unit_price_promo sont des SNAPSHOTS.
     * Ils capturent le prix au moment de la commande.
     * Modifier le prix d'un produit n'affecte jamais les commandes passées.
     *
     * Si ton ancienne colonne s'appelle 'price', crée une migration :
     * php artisan make:migration rename_price_to_unit_price_in_order_items_table
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',       // ✅ snapshot du prix normal au moment de la commande
        'unit_price_promo', // ✅ snapshot du prix promo (null si pas de promo)
    ];

    protected $casts = [
        'quantity'         => 'integer',
        'unit_price'       => 'decimal:2',
        'unit_price_promo' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // Prix effectif payé (promo si disponible, sinon normal)
    protected function effectivePrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->unit_price_promo ?? $this->unit_price,
        );
    }

    // Sous-total pour cette ligne (calculé, jamais stocké)
    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->effective_price * $this->quantity,
        );
    }

    // Indique si cet item a bénéficié d'une promotion
    protected function hadPromo(): Attribute
    {
        return Attribute::make(
            get: fn() => !is_null($this->unit_price_promo),
        );
    }

    // Montant économisé sur cette ligne grâce à la promo
    protected function savedAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->had_promo
                ? ($this->unit_price - $this->unit_price_promo) * $this->quantity
                : 0,
        );
    }
}
