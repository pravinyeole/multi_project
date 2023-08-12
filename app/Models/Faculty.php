<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'mobile', 'department', 'bank_account', 'bank_branch', 'ac_type', 'ifsc_code', 'designation','faculty_type','deleted_at'
    ];


    public $primaryKey = 'faculty_id';
    protected $table    = 'faculties';
}
