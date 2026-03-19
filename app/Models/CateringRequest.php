<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CateringRequest extends Model
{
    use HasFactory;

    // ✅ Champs publics remplissables via le formulaire
    // user_id, status, admin_response, responded_by, responded_at → assignés manuellement
    protected $fillable = [
        'name',
        'email',
        'phone',
        'event_type',
        'event_date',
        'guests',
        'budget',
        'message',
    ];

    // ✅ Statuts alignés avec la migration et l'admin
    // Ton ancien modèle avait ['en_attente','confirme','refuse'] — remplacés par les nouveaux
    const STATUSES = ['nouvelle', 'en_cours', 'acceptee', 'refusee'];

    // ✅ Types d'événements — utilisés dans le formulaire ET les vues admin
    const EVENT_TYPES = [
        'mariage'      => 'Mariage',
        'bapteme'      => 'Baptême',
        'anniversaire' => 'Anniversaire',
        'conference'   => 'Conférence / Séminaire',
        'soiree'       => 'Soirée privée',
        'autre'        => 'Autre événement',
    ];

    protected $casts = [
        'event_date'   => 'datetime',
        'responded_at' => 'datetime',
        'guests'       => 'integer',
        'budget'       => 'integer',
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

    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    | ✅ Gardé tes scopes originaux + ajout byStatus() et upcoming()
    |--------------------------------------------------------------------------
    */

    public function scopeNouvelle($query)
    {
        return $query->where('status', 'nouvelle');
    }

    public function scopeEnCours($query)
    {
        return $query->where('status', 'en_cours');
    }

    public function scopeAcceptee($query)
    {
        return $query->where('status', 'acceptee');
    }

    public function scopeRefusee($query)
    {
        return $query->where('status', 'refusee');
    }

    // Filtre générique sécurisé — utilisé dans AdminCateringController
    public function scopeByStatus($query, ?string $status)
    {
        if ($status && in_array($status, self::STATUSES)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Événements à venir — triés par date (gardé de ton ancien modèle)
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', today())->orderBy('event_date');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'nouvelle'  => 'Nouvelle',
                'en_cours'  => 'En cours',
                'acceptee'  => 'Acceptée',
                'refusee'   => 'Refusée',
                default     => ucfirst($this->status),
            }
        );
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'nouvelle'  => 'bg-blue-100 text-blue-700',
                'en_cours'  => 'bg-yellow-100 text-yellow-700',
                'acceptee'  => 'bg-green-100 text-green-700',
                'refusee'   => 'bg-red-100 text-red-700',
                default     => 'bg-gray-100 text-gray-700',
            }
        );
    }

    // ✅ Libellé du type d'événement — utilisé dans les vues admin
    protected function eventTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => self::EVENT_TYPES[$this->event_type] ?? ucfirst($this->event_type)
        );
    }

    // ✅ Budget formaté en FCFA
    protected function formattedBudget(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->budget
                ? number_format($this->budget, 0, ',', ' ') . ' FCFA'
                : 'Non précisé'
        );
    }

    // Date formatée : "15 mars 2026 à 14:00"
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->event_date?->format('d M Y à H:i')
        );
    }

    // ✅ Gardé de ton ancien modèle — demande encore modifiable
    protected function isEditable(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->status, ['nouvelle', 'en_cours'])
        );
    }

    // Demande en attente de traitement
    protected function isPending(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->status, ['nouvelle', 'en_cours'])
        );
    }
}
