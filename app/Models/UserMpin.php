<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMpin extends Model
{
    use HasFactory;
    protected $primaryKey = 'mid'; // Specify the primary key field name
    protected $table    = 'user_mpin';
    protected $fillable = [
        'uid',
        'mpin',
    ];
}
