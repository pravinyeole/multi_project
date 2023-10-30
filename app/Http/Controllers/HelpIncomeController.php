<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Mail\TwoFactor;
use App\Models\InsuranceAgency;
use App\Models\Notification;
use AWS;
use Mail;
use Config;
use App\Traits\CommonTrait;

use Illuminate\Support\Facades\Validator;

// use App\Traits\TwoFactorTrait;
use Session;

class HelpIncomeController extends Controller
{
    public function __construct(){
        $this->middleware(['auth']);
    }
    public function shPanel(Request $request){
        return view('admin.pincenter.sh');
    }
    public function ghPanel(Request $request){
        return view('admin.pincenter.gh');
    }
    public function myIncome(Request $request){
        return view('admin.pincenter.cal');
    }
    public function myNetwork(Request $request){
        return view('admin.pincenter.mynetwork');
    }
}