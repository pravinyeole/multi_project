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
        'receivers_id',
        'payment_mode',
        'attachment',
        'user_id',
    ];
    public $primaryKey = 'payment_id';


    public function paymentHas()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
