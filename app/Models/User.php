<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'pesel';

    protected $fillable = [
        'pesel',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'has_driver_license',
        'account_status',
        'role',
        'profile_photo'
    ];

    public $timestamps = true;

    public function reservations() {
        return $this->hasMany(Reservation::class, 'pesel');
    }

    public function rentals() {
        return $this->hasMany(Rental::class, 'pesel');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'pesel');
    }
}
