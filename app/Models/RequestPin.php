<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestPin extends Model
{
    use HasFactory;
    protected $table = 'request_pin';

    protected $fillable = [
        'admin_slug',
        'no_of_pin',
        'req_user_id',
        'status',
    ];
    protected $primaryKey = 'pin_request_id';
}
