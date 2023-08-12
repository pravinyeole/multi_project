<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;
    protected $table = 'email_log';
    protected $fillable = ['email_id', 'send_date','c_mail','c_time','updated_at','created_at'];
}
