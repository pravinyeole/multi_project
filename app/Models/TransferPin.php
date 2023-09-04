<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferPin extends Model
{
    use HasFactory;
    protected $table = 'transfer_pin_history';

    protected $fillable = [
        'trans_by',
        'trans_to',
        'trans_count'
    ];
    protected $primaryKey = 'trans_id';
}
