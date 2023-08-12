<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Papercost extends Model
{
    use HasFactory;
     protected $fillable = [
        'exam_type',
        'paper_cost_name',
        'paper_cost',
    ];
    public $timestamps = false;
    public $primaryKey = 'paper_cost_id';
}
