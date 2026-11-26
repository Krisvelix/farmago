<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfirmationRequest extends Model
{
    protected $fillable = [
        'customer_id',
        'pharmacy_id',
        'medicine_id',
        'quantity',
        'note',
        'status',
        'response_note',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'responded_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
