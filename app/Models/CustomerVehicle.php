<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerVehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'vehicle_id', 'plan_array', 'premium_amount', 'payment_term', 'next_payment'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
