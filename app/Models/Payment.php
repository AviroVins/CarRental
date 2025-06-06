<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = ['rental_id', 'pesel', 'amount', 'status', 'method'];

    public function rental() {
        return $this->belongsTo(Rental::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'pesel', 'pesel');
    }
}