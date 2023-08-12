<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Help extends Model
{
    use HasFactory;
    protected $fillable = [
        'menu_name','menu_description','menu_url','sort_order', 'status', 'created_at','modified_at','deleted_at'
    ];
    public $timestamps = false;
}
?>
