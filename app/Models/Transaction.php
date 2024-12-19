<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_wallet_id',
        'reciepient_wallet_id',
        'amount',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function wallet(){
        return $this->belongsToMany(Wallet::class);
      }
}
