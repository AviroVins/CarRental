<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password_hash',
        'phone_number',
        'has_driver_license',
        'account_status',
        'role'
    ];

    public $timestamps = true;

    public function setPasswordHashAttribute($value) {
        $this->attributes['password_hash'] = bcrypt($value);
    }

    public function getAuthPassword() {
        return $this->password_hash;
    }

    public function reservations() {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    public function rentals() {
        return $this->hasMany(Rental::class, 'user_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'user_id');
    }
}
