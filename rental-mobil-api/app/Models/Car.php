<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'icon',
        'price',
        'type',
        'capacity',
        'transmission',
        'monthly_price',
        'driver_price',
        'status', 
        'user_id',
        'rental_type'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function driver() {
    return $this->belongsTo(Driver::class);
}
}
