<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'fname', 'mname', 'lname', 'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
