<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category',
        'service_type',
        'image_url',
        'available',
        'featured',
        'is_popular',
        'is_traiteur',
        'preparation_time',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'available' => 'boolean',
        'featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_traiteur' => 'boolean',
    ];

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeByServiceType($query, $serviceType)
    {
        if ($serviceType && $serviceType !== 'all') {
            return $query->where('service_type', $serviceType);
        }
        return $query;
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeTraiteur($query)
    {
        return $query->where('is_traiteur', true);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', ' ') . ' €';
    }

    public function getFormattedCategoryAttribute()
    {
        $categories = [
            'entrees' => 'Entrées',
            'plats' => 'Plats Principaux',
            'desserts' => 'Desserts',
            'boissons' => 'Boissons',
            'snacks' => 'Snacks',
        ];

        return $categories[$this->category] ?? $this->category;
    }

    public function getFormattedServiceTypeAttribute()
    {
        $services = [
            'restauration' => 'Restauration',
            'traiteur' => 'Service Traiteur',
            'fast_food' => 'Fast Food',
        ];

        return $services[$this->service_type] ?? $this->service_type;
    }

    public function getPreparationTimeFormattedAttribute()
    {
        if ($this->preparation_time) {
            return $this->preparation_time . ' min';
        }
        return 'Sur commande';
    }
}
