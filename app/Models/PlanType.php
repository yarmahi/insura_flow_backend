<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'base_price'
    ];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'plan_vehicle', 'plan_type_id', 'vehicle_id');
    }
}

