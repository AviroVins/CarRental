<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $primaryKey = 'reservation_id';
    public $timestamps = false;

    protected $fillable = ['user_id', 'plate_number', 'start_time', 'end_time', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function car() {
        return $this->belongsTo(Car::class, 'plate_number', 'plate_number');
    }

    public function rental() {
        return $this->hasOne(Rental::class);
    }
}
