<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimWitness extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'claim_id', 'fname', 'mname', 'phone_number'
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
