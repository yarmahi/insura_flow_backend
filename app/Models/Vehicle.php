<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id','plate_number', 'engine_number', 'chassis_number', 'model', 'type_of_body', 'horse_power', 
        'year_manufactured', 'year_of_purchased', 'passenger_carrying_capacity', 'goods_carrying_capacity'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function Claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function planTypes()
    {
        return $this->belongsToMany(PlanType::class, 'plan_vehicle', 'vehicle_id', 'plan_type_id');
    }
}


