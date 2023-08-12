<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubInfo extends Model
{
    use HasFactory;

    protected $table = 'user_sub_info';
    protected $primaryKey = 'user_sub_info_id';
    public $timestamps = false;

    // Define any relationships or additional methods here
}
