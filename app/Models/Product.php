<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

/**
 * Menu.php est supprimé.
 * Ce modèle est la source unique pour tous les articles.
 */
class Product extends Model
{
    use HasFactory;

    // slug absent — auto-généré dans boot()
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'discount_price',
        'stock',
        'image',
        'service_type',
        'is_active',
        'is_featured',
        'is_popular',
        'is_traiteur',
        'preparation_time',
        'sort_order',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'discount_price'   => 'decimal:2',
        'stock'            => 'integer',
        'is_active'        => 'boolean',
        'is_featured'      => 'boolean',
        'is_popular'       => 'boolean',
        'is_traiteur'      => 'boolean',
        'preparation_time' => 'integer',
        'sort_order'       => 'integer',
    ];

    const SERVICE_TYPES = [
        'restauration' => 'Restauration',
        'traiteur'     => 'Service Traiteur',
        'fast_food'    => 'Fast Food',
    ];

    /*
    |--------------------------------------------------------------------------
    | SLUG AUTO-GÉNÉRÉ ET UNIQUE
    |--------------------------------------------------------------------------
    */

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $product) {
            $product->slug = static::generateUniqueSlug($product->name);
        });

        static::updating(function (self $product) {
            if ($product->isDirty('name')) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    private static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug     = Str::slug($name);
        $original = $slug;
        $count    = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Actif ET en stock
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }

    // Actif même si stock = 0
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeTraiteur($query)
    {
        return $query->where('is_traiteur', true);
    }

    public function scopeByServiceType($query, ?string $serviceType)
    {
        if ($serviceType && $serviceType !== 'all'
            && array_key_exists($serviceType, self::SERVICE_TYPES)
        ) {
            return $query->where('service_type', $serviceType);
        }
        return $query;
    }

    public function scopeByCategory($query, ?int $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Alerte stock faible — dashboard admin
    public function scopeLowStock($query, int $threshold = 5)
    {
        return $query->where('stock', '<=', $threshold)->where('stock', '>', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS — syntaxe moderne Laravel 9+
    |--------------------------------------------------------------------------
    */

    protected function finalPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discount_price ?? $this->price
        );
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format((float) $this->final_price, 0, ',', ' ')
                . ' ' . config('app.currency', 'FCFA')
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image
                ? asset('storage/' . $this->image)
                : asset('images/default-product.png')
        );
    }

    protected function isOnSale(): Attribute
    {
        return Attribute::make(
            get: fn() => !is_null($this->discount_price)
                && (float) $this->discount_price < (float) $this->price
        );
    }

    protected function discountPercent(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_on_sale
                ? (int) round((1 - $this->discount_price / $this->price) * 100)
                : 0
        );
    }

    protected function isAvailable(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_active && $this->stock > 0
        );
    }

    protected function serviceTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => self::SERVICE_TYPES[$this->service_type] ?? $this->service_type
        );
    }

    protected function preparationTimeFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->preparation_time
                ? $this->preparation_time . ' min'
                : 'Sur commande'
        );
    }
}
