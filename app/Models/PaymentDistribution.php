<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDistribution extends Model
{
    use HasFactory;
    protected $primaryKey = 'pd_id'; // Specify the primary key field name

    protected $fillable = [
        'sender_id',
        'reciver_id',
        'mobile_id',
        'amount',
        'level',
    ];
    protected $table    = 'payment_distribution';
}
