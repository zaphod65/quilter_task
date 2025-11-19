<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts'; 
    public $timestamps = true;

    protected $fillable = [
        'name', 'user_id', 'balance',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeForUser($query, $accountId, $userId)
    {
        return $query->where([
            'id' => $accountId,
            'user_id' => $userId,
        ]);
    }
}