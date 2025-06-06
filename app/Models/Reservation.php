<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $primaryKey = 'reservation_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    public $timestamps = false;

    protected $fillable = ['pesel', 'plate_number', 'start_time', 'end_time', 'status'];

    public function user() {
        return $this->belongsTo(User::class, 'pesel', 'pesel');
    }

    public function car() {
        return $this->belongsTo(Car::class, 'plate_number', 'plate_number');
    }

    public function rental() {
        return $this->hasOne(Rental::class);
    }
}
