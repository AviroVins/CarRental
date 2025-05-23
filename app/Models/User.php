<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password_hash',
        'phone_number',
        'has_driver_license',
        'account_status',
    ];

    public $timestamps = true;

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
