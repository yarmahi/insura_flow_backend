<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_vehicle_id', 'transaction_number', 'amount', 'start_date', 'end_date'
    ];

    public function customerVehicle()
    {
        return $this->belongsTo(CustomerVehicle::class);
    }
}
