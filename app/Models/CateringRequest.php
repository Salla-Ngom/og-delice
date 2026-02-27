<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CateringRequest extends Model
{
    use HasFactory;

    // user_id retiré — assigné explicitement dans le contrôleur
    // status retiré — jamais assignable par l'utilisateur
    protected $fillable = [
        'event_date',
        'guests',
        'message',
    ];

    const STATUSES = ['en_attente', 'confirme', 'refuse'];

    protected $casts = [
        'event_date' => 'date',
        'guests'     => 'integer',
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeEnAttente($query)
    {
        return $query->where('status', 'en_attente');
    }

    public function scopeConfirme($query)
    {
        return $query->where('status', 'confirme');
    }

    public function scopeRefuse($query)
    {
        return $query->where('status', 'refuse');
    }

    // Événements à venir — triés par date
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', today())->orderBy('event_date');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS — syntaxe moderne Laravel 9+
    |--------------------------------------------------------------------------
    */

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'en_attente' => 'En attente',
                'confirme'   => 'Confirmé',
                'refuse'     => 'Refusé',
                default      => ucfirst($this->status),
            }
        );
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'en_attente' => 'bg-yellow-100 text-yellow-700',
                'confirme'   => 'bg-green-100 text-green-700',
                'refuse'     => 'bg-red-100 text-red-700',
                default      => 'bg-gray-100 text-gray-700',
            }
        );
    }

    // Date formatée en français : "15 mars 2026"
    protected function formattedEventDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->event_date?->translatedFormat('d F Y')
        );
    }

    // Encore modifiable (admin peut changer uniquement si en_attente)
    protected function isEditable(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === 'en_attente'
        );
    }
}
