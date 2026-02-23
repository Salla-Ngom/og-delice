<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }public function getStatusBadgeAttribute()
{
    return match($this->status) {
        'en_attente' => 'bg-yellow-100 text-yellow-700',
        'en_preparation' => 'bg-blue-100 text-blue-700',
        'prete' => 'bg-green-100 text-green-700',
        'annulee' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-700',
    };
}

public function getStatusLabelAttribute()
{
    return match($this->status) {
        'en_attente' => 'En attente',
        'en_preparation' => 'En préparation',
        'prete' => 'Prête',
        'annulee' => 'Annulée',
        default => ucfirst($this->status),
    };
}

}
