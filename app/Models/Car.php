<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $table = "cars";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'name',
        'brand',
        'model',
        'year',
        'color',
        'image',
        'transmision',
        'seat',
        'cost_per_day',
        'location',
        'available',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'user_id', 'id');
    }

    public function booking(): HasMany
    {
        return $this->hasMany(Booking::class, 'car_id', 'id');
    }
}
