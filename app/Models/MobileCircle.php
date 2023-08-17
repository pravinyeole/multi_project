<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MobileCircle extends Model
{
    use HasFactory;
    protected $table='mobile_circle';
    protected $fillable = [
        'serial','operator','circle'
    ];
}
?>
