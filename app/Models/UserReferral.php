<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReferral extends Model
{
    use HasFactory;
    protected $table = 'user_referral';
    
    protected $fillable = [
        'user_id',
        'referral_id',
    ];
    protected $primaryKey = 'user_referral_id';
}
