<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renumeration extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_paper_id',
        'paper1_cost',
        'paper2_cost',
        'paper3_cost',
        'chairman',
        'internal_paper_setter',
        'external_paper_setter',
        'grand_total',
    ];

    protected $primaryKey = 'renumerations_id'; // Specify the primary key column name if different from "renumerations_id"
    protected $table = 'renumerations'; // Specify the table name if different from "renumerations"
}
