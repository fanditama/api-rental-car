<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'car_id', 'id');
    }

   public function bookings(): HasMany
   {
       return $this->hasMany(Booking::class, 'user_id', 'id');
   }

    /**
     * override semua method dari class interface Authenticable
     * untuk proses middleware autentikasi proses login
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->username;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->token;
    }

    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    public function getRememberTokenName()
    {
        return 'token';
    }
}
