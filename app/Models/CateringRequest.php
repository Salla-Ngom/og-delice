<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CateringRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_date',
        'guests',
        'message',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
