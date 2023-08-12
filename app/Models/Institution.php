<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'name', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];

     public $timestamps  = false;
     public $primaryKey  = 'institution_id';
     protected $table    = 'institutions';
}
