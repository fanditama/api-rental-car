<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = "payments";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'amount',
        'payment_date',
        'status',
        'transaction_proof',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'booking_id', 'id');
    }
}
