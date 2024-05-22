<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimPhoto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'claim_id', 'url'
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
