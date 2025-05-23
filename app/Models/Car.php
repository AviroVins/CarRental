<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $primaryKey = 'plate_number';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['plate_number', 'maker', 'model', 'year', 'status'];

    public function reservations() {
        return $this->hasMany(Reservation::class, 'plate_number', 'plate_number');
    }

    public function rentals() {
        return $this->hasMany(Rental::class, 'plate_number', 'plate_number');
    }
}