<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'transction_type',
        'to_uid',
        'amount',
        'balance',
        'to_user_balance',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
