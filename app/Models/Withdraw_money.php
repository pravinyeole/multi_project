<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw_money extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'user_id','money','withdraw_rpin','updated_at','created_at'
    ];
    protected $table = 'withdraw_money';
    protected $primaryKey = 'id';

}
