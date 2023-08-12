<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonTrait;

class UserRole extends Model
{
    use HasFactory, CommonTrait;

    protected $fillable = [
        'user_id', 'insurance_agency_id', 'role','user_role_status','created_at'
    ];

    public $timestamps = false;
    public $primaryKey = 'user_role_id';
    public $roleName = [
        'U' => 'User',
        'T' => 'Team Admin',
        'OA' => 'Insurance Agency Admin'
    ];

    public function InsuranceAgency()
    {

        return $this->belongsTo(InsuranceAgency::class, 'insurance_agency_id', 'insurance_agency_id')->withDefault();
    }

    public function getRoleNameAttribute()
    {

        return $this->roleName[$this->role];
    }

    public function getFormatDateAttribute()
    {
        return $this->getDate($this->created_at, config('app.timezone'));
    }
}
