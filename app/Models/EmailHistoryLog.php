<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailHistoryLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'email_id', 'send_date','c_mail','c_time'
    ];


    public $primaryKey = 'id';
    protected $table    = 'email_log';
}
