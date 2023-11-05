<?php

namespace App\Models;

use App\Models\InsuranceAgency;
use App\Models\Team;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   // Define the fillable attributes
    protected $fillable = [
        'user_fname',
        'user_lname',
        'mobile_number',
        'email',
        'total_invited',
        'email_verified_at',
        'user_status',
        'user_last_login',
        'created_at',
        'modified_at',
        'deleted_at',
        'remember_token',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'user_role',
        'operator',
        'circle',
        'upi',
    ];
     public $timestamps  = false;

     
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'trial_ends_at' => 'string',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function scopeInsuranceAgencyScope($query){
        return $query->whereHas('userRole', function($q){
            $q->where('insurance_agency_id',Auth::user()->getInsuranceAgencyID())->whereNotIn('role',['O']);
        });
    }

    public function scopeActive($query){
        return $query->where('user_status','Active');
    }

    public function getRole(){
       return UserRole::where(['user_id' =>$this->id])->value('role');
    }

    public static function getInsuranceAgencyID()
    {
        if (session()->has('INSURANCE_AGENCY_ID')) {
            return session('INSURANCE_AGENCY_ID');
        }
    }

    public function insurance_agencies(){
        return $this->hasOne(InsuranceAgency::class, 'user_id', 'id')->with('contactinfo');
    }

    public function team(){
        return $this->hasOne(Team::class,'user_id','id');
    }

    public function insuranceTeam()
    {
        return $this->hasOne(Team::class,'team_id','team_id');
    }

    public function subscriptionDetails(){
        return $this->hasOne(Subscription::class, 'user_id', 'id');
    }

    public function get_org_name()
    {
        return $this->belongsTo(InsuranceAgency::class,'insurance_agency_id','insurance_agency_id')->select('insurance_agency_id','insurance_agency_name','notification_alert','is_mfa','mfa_type');
    }

    public function organizationUser(){
        return $this->hasMany(InsuranceAgency::class, 'insurance_agency_id', 'insurance_agency_id');
    }

    public function createdby(){
        return $this->belongsTo(User::class,'created_by_id','id')->selectRaw('id, concat(user_fname,"  ",user_lname) as userOnlyName');
    }

    public function userTeamAccess(){
        return $this->hasMany(UserAceessTeam::class, 'user_id', 'id');
    }

    public function role(){
        return $this->hasOne(UsersRole::class, 'user_id', 'id')->where('status','Active')->where('role_name','!=','');
    }

    public function userRole(){
        return $this->hasMany(UserRole::class,'user_id','id');
    }
    
    public function getAuthTeamsID(){
        return UserAceessTeam::where(['user_id' => Auth::user()->id, 'insurance_agency_id' => self::getInsuranceAgencyID()])->pluck('team_id')->toArray();
    }

    public function scopeTeamAccessScope($q){
        return $q->whereHas('userTeamAccess', function($q) {
            $q->whereIn('team_id',Auth::user()->getAuthTeamsID());
        });
    }

    public function scopeIgnoreSelf($q){
        return $q->where('users.id','!=',Auth::user()->id);
    }
    // generate OTP
    public function generateTwoFactorCode($mfa_type){
        $code1 = rand(100000, 999999);
        $code2 = rand(100000, 999999);
        if($mfa_type == "phone"){
            $this->phone_otp = $code1;
        }else if($mfa_type == "email"){
            $this->email_otp = $code2;
        }else{
            $this->phone_otp = $code1;
            $this->email_otp = $code2;
        }
        // $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }

    //Reset OTP
    public function resetTwoFactorCode($mfa_type){
        if($mfa_type == "phone"){
            $this->phone_otp = null;
        }else if($mfa_type == "email"){
            $this->email_otp = null;
        }else{
            $this->email_otp = null;
            $this->phone_otp = null;
        }
        $this->count_of_max_otp = null;
        // $this->two_factor_expires_at = null;
        $this->save();
    }
    


}
