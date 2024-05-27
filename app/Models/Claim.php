<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Claim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'latitude', 'longitude', 'agent_id', 'vehicle_id', 'description', 'time_of_accident', 'status'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
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
