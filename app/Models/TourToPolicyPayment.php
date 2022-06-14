<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourToPolicyPayment extends Model
{
    use HasFactory;

    protected $table = 'tour_to_policy_payment';
    protected $guarded = [];

    public function payment_name()
    {
        return $this->hasOne('App\Models\Payments','id','payment_id')->select('id','name');
    }
}
