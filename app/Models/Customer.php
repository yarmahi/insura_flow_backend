<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'is_company', 'fname', 'mname', 'lname', 'license_number', 'wereda', 'phone', 'house_no'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerVehicles()
    {
        return $this->hasMany(CustomerVehicle::class);
    }
}

