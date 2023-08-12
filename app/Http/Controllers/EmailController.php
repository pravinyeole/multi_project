<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\OfficeOrder;
use App\Models\Subject;
use App\Models\EmailLog;
use App\Models\EmailHistoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;


use PDF;
use Mail;
use DataTables;
use DB;
use Redirect;

use Dompdf\Dompdf;
use Dompdf\Options;

class EmailController extends Controller
{

    public function __construct(){
    	$this->middleware(['auth'])->except(['saveUserRoleConfig']);
  	}


    public function send_order(){
        $examdetails = OfficeOrder::first();
        return view('email/send_order',compact('examdetails'));
    }

    public function order_store(Request $request){

        $input = $request->all();

        $orderType = $input['order_type'];
        $attachment = $request->file('attachment');

        if(isset($attachment)){
            foreach($attachment as $key=> $data){
                $imageName  = time().$data->getClientOriginalName();
                $data =  file_put_contents(public_path().'/uploads/attachment/reports/'.$imageName,file_get_contents($data));
            }
        }

        $files = glob(public_path().'/uploads/attachment/reports/*');
        // dd($files);
        if(isset($files) && $files != null){
            $whatIWant = substr($files[0], strpos($files[0], "/"));    
        }
       
        $email_body = $input['email_body'];

        $theArray = Excel::toArray(new \stdClass(), $request->file('fto_file'));
        $uploaded_file_data = $theArray[0];
        $found_data = [];
        $notfoundData = [];
        if( $orderType  == "office_order"){
            foreach($uploaded_file_data as $key => $data){
                if($key > 0){
                    $name    = $data[0];
                    $s_email = $data[1];
                    $s_code  = $data[2];
                    $c_email = $data[3];
                    $check1 = Faculty::where('email',$s_email)->first();
                     if (!empty($name) && !empty($s_email) && !empty($s_code) && !empty($c_email)) {
                        if($check1 != null){
                            $check2 = Faculty::where('email',$c_email)->first();
                            if($check2 != null){
                            $found_data[$key]['name']       =  $name;
                            $found_data[$key]['s_email']    =  $s_email;
                            $found_data[$key]['c_email']    =  $c_email;
                            $found_data[$key]['s_code']    =  $s_code;
                            $found_data[$key]['chairman_found'] = true;
                            }
                            else{
                            $found_data[$key]['name']       =  $name;
                            $found_data[$key]['s_email']    =  $s_email;
                            $found_data[$key]['c_email']    =  $c_email;
                            $found_data[$key]['s_code']    =  $s_code;
                            $found_data[$key]['chairman_found'] = false;
                            }
                        }
                        else{
                            $notfoundData[$key]['name']       =  $name;
                            $notfoundData[$key]['s_email']    =  $s_email;
                            $notfoundData[$key]['c_email']    =  $c_email;
                            $notfoundData[$key]['s_code']     =  $s_code;
                            $notfoundData[$key]['chairman_found'] = true;
                        }
                    }
        
        
                }
                }

        }

        if( $orderType  == "paper_checking_order"){
            foreach($uploaded_file_data as $key => $data){
                 if($key > 0){
                    $name    = $data[0];
                    $s_email = $data[1];
                    $s_code  = $data[2];
                    $check1 = Faculty::where('email',$s_email)->first();
                    if (!empty($name) && !empty($s_email) && !empty($s_code)) {
                        if($check1 != null){
                            $found_data[$key]['name']       =  $name;
                            $found_data[$key]['s_email']    =  $s_email;
                            $found_data[$key]['s_code']    =  $s_code;
                        }
                        else{
                            $notfoundData[$key]['name']       =  $name;
                            $notfoundData[$key]['s_email']    =  $s_email;
                            $notfoundData[$key]['s_code']     =  $s_code;
                        }
                    }
                }
            }

        }
        
        return view('email/pre_order',compact('found_data','notfoundData', 'email_body', 'files','orderType'));
    }
    
    
    public function downloadOfficeOrderTemplate()
    {
        $filepath   = public_path().'/uploads/office_order.xlsx';
        return Response::download($filepath);  
    }
     public function downloadPaperCheckingTemplate()
    {
        $filepath   = public_path().'/uploads/paperchcking_order.xlsx';
        return Response::download($filepath);  
    }

    public function attached_pdf_static($s_email, $s_code, $c_email){

        $setter_data = Faculty::select('name','mobile','department')->where('email',$s_email)->first();

        $chairman_data = Faculty::select('name','mobile')->where('email',$c_email)->first();

        $subject_data = Subject::select('term_name','subject_code','subject_name','class_name')
        ->leftJoin('terms', 'terms.term_id', 'subjects.subject_term')
        ->leftJoin('classes', 'classes.class_id', 'subjects.class_id')
        ->where('subject_code',$s_code)->first();

        $office_order_data = OfficeOrder::select('ref_no','exam_year','examtype','submission_date')->where('office_order_id', '1')->first();

        $submissionDate = $office_order_data->submission_date = date("d F, Y (l)", strtotime($office_order_data->submission_date));

        // $pdf = PDF::loadView('email.office_order',compact('setter_data','chairman_data', 'subject_data', 'office_order_data'));
        //     //For download  use of download//
        //     return $pdf->stream('attachment.pdf');
        //  $signature = '<image scr ="{{asset('images/avatars/1.png')}'>'';

           $signature;
        $pdf = PDF::loadView('email.office_order',compact('setter_data','chairman_data', 'subject_data', 'office_order_data','submissionDate'));

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->set_opacity(.2,"Multiply");

        $canvas->set_opacity(.2);

        $canvas->page_text($width/5, $height/2, 'CONFIDENTIAL', null,
        55, array(255,0,0),2,2,-30);

        //Load the watermark image
        // $watermarkPath = public_path('images/logo/logo-removebg.png');

        // // Add the image as a watermark on the canvas
        // $canvas->image($watermarkPath, $width / 5, $height / 2, $width / 2, $height / 2);
 

            //  return $pdf->stream('attachment.pdf');
        return $pdf->download('attachment.pdf');
    }

    public function attachedCheckingPdfStatic($s_email, $s_code){

        $setter_data = Faculty::select('name','mobile','department')->where('email',$s_email)->first();

        $subject_data = Subject::select('term_name','subject_code','subject_name','class_name')
        ->leftJoin('terms', 'terms.term_id', 'subjects.subject_term')
        ->leftJoin('classes', 'classes.class_id', 'subjects.class_id')
        ->where('subject_code',$s_code)->first();

        $office_order_data = OfficeOrder::where('office_order_id', '1')->first();

        $submissionDate = $office_order_data->submission_date = date("d F, Y (l)", strtotime($office_order_data->submission_date));
        $paperCheckingStartDate = date("d F, Y (l)", strtotime($office_order_data->start_date));
         $paperCheckingEndDate = date("d F, Y (l)", strtotime($office_order_data->end_date));
        // $pdf = PDF::loadView('email.office_order',compact('setter_data','chairman_data', 'subject_data', 'office_order_data'));
        //     //For download  use of download//
        //     return $pdf->stream('attachment.pdf');
        //  $signature = '<image scr ="{{asset('images/avatars/1.png')}'>'';

        //    $signature;

        $pdf = PDF::loadView('email.paper_checking_order',compact('setter_data','subject_data', 'office_order_data','paperCheckingStartDate','paperCheckingEndDate'));

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.1);

        $canvas->page_text($width/5, $height/2, 'CONFIDENTIAL', null,
        55, array(255,0,0),2,2,-30);

        return $pdf->download('attachment.pdf');
    }

    public function check_c_email($c_email){
        // dd($c_email);
        $check = Faculty::where('email',$c_email)->first();
        if($check != null){
            return "true";
        }
        else{
            return "false";
        }
    }

    public function send_order_emails(Request $request)
    {
        $input = $request->all();
        // $html = $this->convertHtmlToText($request);
        // $text = $html->original;
        // $text = str_replace("\r", "", $text);
        // $text = str_replace("&nbsp;", "", $text);
        // $text = html_entity_decode(strip_tags($text)); // Remove HTML tags and decode entities
        $orderType   = $input['order_type'];
        $data1["title"] = "Your Appointment For Exam Work";
        // $data1["body"] = $text;
        $data1['body'] = $input['email_body'];

        foreach($input['s_email'] as $key => $data){
            $setter_data = Faculty::select('name','mobile','department')->where('email',$data)->first();
            if( $orderType  == "office_order"){
            $chairman_data = Faculty::select('name','mobile')->where('email',$input['c_email'][$key])->first();
            }
            $subject_data = Subject::select('term_name','subject_code','subject_name','class_name')
            ->leftJoin('terms', 'terms.term_id', 'subjects.subject_term')
            ->leftJoin('classes', 'classes.class_id', 'subjects.class_id')
            ->where('subject_code',$input['s_code'][$key])->first();
    
            $office_order_data = OfficeOrder::select('ref_no','exam_year','examtype','submission_date')->where('office_order_id', '1')->first();
    
           $submissionDate = $office_order_data->submission_date = date("d F, Y (l)", strtotime($office_order_data->submission_date));
            
             if( $orderType  == "office_order"){
                $pdf = PDF::loadView('email.office_order',compact('setter_data','chairman_data', 'subject_data', 'office_order_data','submissionDate'));
             }else{
                  $paperCheckingStartDate = $office_order_data->submission_date = date("d F, Y (l)", strtotime($office_order_data->start_date));
                $paperCheckingEndDate = $office_order_data->submission_date = date("d F, Y (l)", strtotime($office_order_data->end_date));
                $pdf = PDF::loadView('email.paper_checking_order',compact('setter_data', 'subject_data', 'office_order_data','paperCheckingEndDate','paperCheckingStartDate'));
             }
            $pdf->setPaper('L');
            $pdf->output();
            $canvas = $pdf->getDomPDF()->getCanvas();

            $height = $canvas->get_height();
            $width = $canvas->get_width();

            $canvas->set_opacity(.1,"Multiply");

            $canvas->set_opacity(.1);

            $canvas->page_text($width/5, $height/2, 'CONFIDENTIAL', null,
                55, array(255,0,0),2,2,-30);
            $content = $pdf->download()->getOriginalContent();
        
    
            file_put_contents(public_path().'/uploads/attachment/reports/office_order.pdf',$pdf->output());
    
            $files = glob(public_path().'/uploads/attachment/reports/*');
            
             if( $orderType  == "office_order"){
                // Sending the email using the modified $data1["body"]:
                $mail = Mail::send('email.myTestMail', $data1, function ($message) use ($data, $files, $input, $key, $data1) {
                    $message->setContentType('text/html');
                    $message->to($data)
                        ->cc($input['c_email'][$key])
                        ->subject("Your Appointment For Exam Work")
                        ->setBody($data1["body"], 'text/html');
                    
                    foreach ($files as $file) {
                        $message->attach($file);
                    }
                    EmailLog::create([
                        'email_id' => $data,
                        'send_date' => date('Y-m-d h:i:s a'),
                        'c_mail' => $input['c_email'][$key],
                        'c_time' => date('Y-m-d h:i:s a')
                    ]);
                });
            }else{
                 $mail =  Mail::send('email.myTestMail',$data1,function($message)use($data, $files, $input, $key,$data1) {
                        $message->setContentType('text/html');
                        $message->to($data)
                        ->subject("Your Appointment For Exam Work")
                        ->setBody($data1["body"], 'text/html');
                    
                        foreach ($files as $file) {
                            $message->attach($file);
                        }
                        EmailLog::create([
                            'email_id' => $data,
                            'send_date' => date('Y-m-d h:i:s a'),
                        ]);

                    });
            }
             // Delete the files after sending the email
            foreach ($files as $file) {
                unlink($file);
            }

        }
        return redirect('send_order');
         
    }

    public function download_attachment($file){

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . basename(public_path().'/uploads/attachment/reports/1680460584verticalmenujson.txt') . '"',
        ];
    
        return response()->download(public_path().'/uploads/attachment/reports/'.$file, basename(public_path().'/uploads/attachment/reports/'.$file), $headers);
    }

    public function exam_details(){

        $office_order_data = OfficeOrder::where('office_order_id', '1')->first();
        return view('exam.exam_details',compact('office_order_data'));
    }

    public function update_exam_details(Request $request){
        try {
            $this->title_msg = "Exam Details";
            $input = $request->all();
            // dd($input);
            $searchInput['office_order_id'] = $input['office_order_id'];
            $update = OfficeOrder::where('office_order_id', $input['office_order_id'])->update([
                        'ref_no' => $input['ref_no'],
                        'exam_year' => $input['exam_year'],
                        'examtype' => $input['examtype'],
                        'submission_date' => $input['submission_date'],
                        'start_date' => $input['start_date'],
                        'end_date' => $input['end_date']
                    ]);


            //   dd($update);
            toastr()->success($this->title_msg.' '.Config('messages.update'));
            return redirect('exam_details');
         }catch(\Exception $e) {
            dd($e->getMessage());
            toastr()->error('Something went wrong');
            return Redirect::back();
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                         Paper setting email history                        */
    /* -------------------------------------------------------------------------- */
    public function paperCheckingEmailHistory(Request $request){
        try {
            if ($request->ajax()) {
                $data = EmailHistoryLog::orderBy('id','DESC')->get();
        
                return Datatables::of($data)
                ->addIndexColumn()
                // ->rawColumns(['action'])
                ->make(true);
            }
        }catch(\Exception $e) {
            dd($e->getMessage());
            toastr()->error(Config('messages.500'));
        }
        
        // return view('admin.department.index', compact('title'));
        return view ('email.send_order');
    }
    /* -------------------------------------------------------------------------- */

    private function convertHtmlToText(Request $request)
    {
        // Get the HTML text from the request
        $htmlText = $request->input('email_body');

        // Strip HTML tags and convert to plain text
        $plainText = strip_tags($htmlText);

        // If you want to preserve line breaks, use the HtmlString class to prevent double encoding
        $plainText = new HtmlString($plainText);

        // Return the plain text response
        return response($plainText, 200)->header('Content-Type', 'text/plain');
    }
}
