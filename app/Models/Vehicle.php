<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plate_number', 'engine_number', 'chassis_number', 'model', 'type_of_body', 'horse_power', 
        'year_manufactured', 'year_of_purchased', 'passenger_carrying_capacity', 'goods_carrying_capacity'
    ];
}
