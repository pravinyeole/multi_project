<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_otp_id'; // Specify the primary key field name

    protected $fillable = [
        'user_id',
        'phone_otp',
    ];
    protected $table    = 'user_otp';
}
