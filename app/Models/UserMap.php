<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMap extends Model
{
    use HasFactory;
    protected $table = 'user_map_new';
    protected $primaryKey = 'user_map_id';
    // protected $dates = ['deleted_at'];
    public $timestamps = ['created_at', 'updated_at'];
    protected $fillable = ['mobile_id', 'new_user_id','user_id','user_mobile_id', 'type'];

}
