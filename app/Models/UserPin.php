<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPin extends Model
{
    use HasFactory;
    protected $table = 'user_pins';
    protected $primaryKey = 'user_pin_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pins',
    ];

}
