<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'price',
        'type',
        'capacity',
        'transmission',
        'monthly_price',
        'driver_price',
    ];
}
