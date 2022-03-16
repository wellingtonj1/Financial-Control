<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillsToPay extends Model
{
    use HasFactory;
    protected $table = 'bills_to_pay';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
