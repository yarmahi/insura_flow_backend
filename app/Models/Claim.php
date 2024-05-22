<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Claim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'latitude', 'longitude', 'customer_vehicle_id', 'description', 'time_of_accident', 'status'
    ];

    public function customerVehicle()
    {
        return $this->belongsTo(CustomerVehicle::class);
    }

    public function agentClaims()
    {
        return $this->hasMany(AgentClaim::class);
    }

    public function claimWitnesses()
    {
        return $this->hasMany(ClaimWitness::class);
    }

    public function claimPhotos()
    {
        return $this->hasMany(ClaimPhoto::class);
    }
}
