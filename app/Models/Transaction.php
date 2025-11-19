<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions'; 
    public $timestamps = true;

    protected $fillable = [
        'description', 'account_id', 'amount',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
