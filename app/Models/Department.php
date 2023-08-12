<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'department_id','department_name', 'department_status'
    ];


    public $primaryKey = 'department_id';
    protected $table    = 'departments';
}
