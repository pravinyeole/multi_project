<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no', 'exam_year', 'examtype', 'submission_date', 'start_date', 'end_date'
    ];


    public $primaryKey = 'office_order_id';
    protected $table    = 'office_orders';
    public  $timestamps = false;
}
