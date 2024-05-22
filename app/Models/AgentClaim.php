<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentClaim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'claim_id', 'agent_id'
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
