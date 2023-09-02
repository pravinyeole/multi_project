<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'type','announce','start_date', 'end_date'
    ];


    public $primaryKey = 'id';
    protected $table    = 'announcement';
}
