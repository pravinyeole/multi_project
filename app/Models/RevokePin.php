<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevokePin extends Model
{
    use HasFactory;
    protected $table = 'revoke_pin_history';

    protected $fillable = [
        'revoke_by',
        'revoke_from',
        'revoke_count'
    ];
    protected $primaryKey = 'revoke_id';
}
