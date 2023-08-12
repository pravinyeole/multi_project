<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    
    protected $fillable = [
        'user_map_id ',
        'send_help_user_id',
        'get_help_user_id',
        'payment_mode',
        'attachment',
    ];
    public $primaryKey = 'payment_id';
}
