<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vehicle_id', 'transaction_number', 'amount', 'start_date', 'end_date', 'status'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function daysLeft() {
        $today = Carbon::today();

        $targetDate = Carbon::create($this->end_date);

        return $targetDate->diffInDays($today, true);
    }
}
