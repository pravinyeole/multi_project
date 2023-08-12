<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;
    protected $fillable = [
        'parameter_key',
        'parameter_value',
    ];
    public $timestamps = false;
    public $primaryKey = 'parameter_id';

}
