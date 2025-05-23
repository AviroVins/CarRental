<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $primaryKey = 'rental_id';
    public $timestamps = false;

    protected $fillable = ['reservation_id', 'user_id', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function car() {
        return $this->belongsTo(Car::class, 'plate_number', 'plate_number');
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}