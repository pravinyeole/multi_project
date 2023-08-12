<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'department_id', 'class_id','subject_term','subject_code', 'subject_name','subject_pattern','subject_status'
    ];


    public $primaryKey = 'subject_id';
    protected $table    = 'subjects';
}
