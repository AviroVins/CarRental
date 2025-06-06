<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $primaryKey = 'rental_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    public $timestamps = false;

    protected $fillable = ['reservation_id', 'pesel', 'plate_number', 'pickup_time', 'return_time', 'distance_km', 'cost'];

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'pesel', 'pesel');
    }

    public function car() {
        return $this->belongsTo(Car::class, 'plate_number', 'plate_number');
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}