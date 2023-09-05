<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Renumeration;
use Illuminate\Http\Request;
use App\Models\TeacherPaper;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherPaperExport;
use Illuminate\Support\Facades\Log;
use DataTables;
use Barryvdh\DomPDF\Facade as PDF;

class ReportController extends Controller
{
    //
    public function __construct(){
        $this->middleware(['auth']);
    }
    public function index(Request $request ){
         $departments   = Department::where('department_status', 'active')->get();
        return view('report.index',compact('departments'));
    }
    public function download(Request $request ){
        try{
            $input = $request->all();
            if($request->report_type == "paper_enverntry") {
            $data = TeacherPaper::join('departments','departments.department_id','teacher_paper.department_id')
                    ->join('subjects','subjects.subject_id','teacher_paper.subject_id')
                    ->where('teacher_paper.department_id', $input['department_id'])
                    ->select(
                        'teacher_paper.exam_date',
                        'teacher_paper.pattern',
                        'teacher_paper.paper1',
                        'teacher_paper.paper2',
                        'teacher_paper.paper3',
                        'teacher_paper.selected_paper',
                        'teacher_paper.selected_p_date',
                        'departments.department_name',
                        'subjects.subject_term',
                        'subjects.subject_code',
                        'subjects.subject_name',
                        'subjects.subject_pattern',
                        DB::raw('IF(paper1 IS NOT NULL, 1, 0) + IF(paper2 IS NOT NULL, 1, 0) + IF(paper3 IS NOT NULL, 1, 0) as paper_count')
                    )
                    ->get();

                    $outputArray = [];

                    foreach ($data as $item) {
                        $output['count'] = $item['paper_count'];
                        $output['department'] = $item['department_name'];
                        $output['subject_name'] = $item['subject_name'];
                        $output['subject_pattern'] = $item['subject_pattern'];
                        $output['paper1'] = $item['paper1'];
                        $output['paper2'] = $item['paper2'];
                        $output['paper3'] = $item['paper3'];


                        array_push($outputArray, $output);
                    }
                    Excel::download(new TeacherPaperExport($outputArray), 'teacher_paper_report.xlsx');
                    return view('report.index');
            }
            else if($input['report_type'] == "selected_paper"){
                    $data = TeacherPaper::join('departments','departments.department_id','teacher_paper.department_id')->join('subjects','subjects.department_id','teacher_paper.department_id')->where('teacher_paper.department_id',$input['department_id'])->where('departments.department_status','active')->where('subjects.subject_status','active')->get();
                    // $data['report_type'] = "selected_paper";
                    $dataArray = [];
                    foreach ($data as $item) {
                        $dataRow = [
                            'department_name' => $item['department_name'],
                            'subject_name' => $item['subject_name'],
                            'subject_code' => $item['subject_code'],
                            'selected_paper' => isset($item['selected_paper']) ? $item['selected_paper'] : null,
                            'selected_p_date' => isset($item['selected_p_date']) ? $item['selected_p_date'] : null,
                            'report_type' => 'selected_paper'
                        ];
                        $dataArray[] = $dataRow;
                    }
                        Excel::download(new TeacherPaperExport($dataArray), 'teacher_paper_report.xlsx');
            }
            $departments   = Department::where('department_status', 'active')->get();
         }catch(\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            toastr()->error(Config('messages.500'));
        }
    }

    public function indexShowSelectedPaper(Request $request){
            try {
            if ($request->ajax()) {
                $data = TeacherPaper::select('department_name','subject_name','subject_code','selected_paper','selected_p_date')->join('subjects','subjects.subject_id','teacher_paper.subject_id')->join('departments','departments.department_id','teacher_paper.department_id')->whereNotNull('selected_paper');
                // $data->dd();
                return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('selected_p_date', function ($row) {
                   $date = date('d M Y',strtotime($row->selected_p_date));
                    return $date;
                })
                ->rawColumns(['department_name', 'subject_name','subject_code','selected_paper','selected_p_date'])
                ->make(true);
            }
        }catch(\Exception $e) {
            DB::rollback();
            toastr()->error(Config('messages.500'));
        }
        return view ('report.selected_paper');
    }

    public function downloadReport(Request $request){
           try{
            $input = $request->all();
            if($request->report_type == "paper_enverntry") {
            $data = TeacherPaper::join('departments','departments.department_id','teacher_paper.department_id')
                    ->join('subjects','subjects.subject_id','teacher_paper.subject_id')
                    ->where('teacher_paper.department_id', $input['selectedDepartment'])
                    ->select(
                        'teacher_paper.exam_date',
                        'teacher_paper.pattern',
                        'teacher_paper.paper1',
                        'teacher_paper.paper2',
                        'teacher_paper.paper3',
                        'teacher_paper.selected_paper',
                        'teacher_paper.selected_p_date',
                        'departments.department_name',
                        'subjects.subject_term',
                        'subjects.subject_code',
                        'subjects.subject_name',
                        'subjects.subject_pattern',
                        DB::raw('IF(paper1 IS NOT NULL, 1, 0) + IF(paper2 IS NOT NULL, 1, 0) + IF(paper3 IS NOT NULL, 1, 0) as paper_count')
                    )
                    ->get();

                    $outputArray = [];

                    foreach ($data as $item) {
                        $output['count'] = $item['paper_count'];
                        $output['department'] = $item['department_name'];
                        $output['subject_name'] = $item['subject_name'];
                        $output['subject_pattern'] = $item['subject_pattern'];
                        $output['paper1'] = $item['paper1'];
                        $output['paper2'] = $item['paper2'];
                        $output['paper3'] = $item['paper3'];
                        array_push($outputArray, $output);
                    }
                    $pdf = PDF::loadView('report.export_pdf', compact('outputArray'));
                    return $pdf->download('metrics.pdf');
            }
            else if($input['report_type'] == "selected_paper"){
                    $data = TeacherPaper::select('department_name','subject_name','subject_code','selected_paper','selected_p_date')->join('subjects','subjects.subject_id','teacher_paper.subject_id')->join('departments','departments.department_id','teacher_paper.department_id')->whereNotNull('selected_paper')->get();
                    // $data['report_type'] = "selected_paper";
                    $dataArray = [];
                    foreach ($data as $item) {
                        $dataRow = [
                            'department_name' => $item['department_name'],
                            'subject_name' => $item['subject_name'],
                            'subject_code' => $item['subject_code'],
                            'selected_paper' => isset($item['selected_paper']) ? $item['selected_paper'] : null,
                            'selected_p_date' => isset($item['selected_p_date']) ? $item['selected_p_date'] : null,
                            'report_type' => 'selected_paper'
                        ];
                        $dataArray[] = $dataRow;
                    }
                     $pdf = PDF::loadView('report.selected_paper_export_pdf', compact('dataArray'));
                    return $pdf->download('metrics.pdf');
                }elseif ($request->report_type == "renum_report") {
    $data = Renumeration::select(
        'renumerations.*',
        'faculties.*',
        'teacher_paper.id as teacher_paper_id',
        'teacher_paper.department_id as department_id'
    )
        ->join('teacher_paper', 'teacher_paper.id', '=', 'renumerations.teacher_paper_id')
        ->join('faculties', 'faculties.faculty_id', '=', 'teacher_paper.chairman')
        ->where('teacher_paper.department_id', $input['selectedDepartment'])
        ->get();

    $dataArray = [];
    foreach ($data as $item) {
        $dataArray = [];
        if ($item['chairman']) {
            $tds = round(($item['paper1_cost'] * 0.01), 0); // Calculate TDS as 1% of the amount
            $netPay = $item['paper1_cost'] - $tds; // Calculate Net Pay by deducting TDS from the amount
        // Chairman
        $chairmanData = [
            'faculty_name' => $item['name'],
            'amount' => $item['paper1_cost'],
            'tds' => $tds,
            'netpay' => $netPay,
            'bank_acc_no' => $item['bank_account'],
        ];
        $dataArray[] = $chairmanData;
        }

        // Internal Paper Setter
        if ($item['internal_paper_setter']) {
            $internalPaperSatterName = Faculty::where('faculty_id', $item['internal_paper_setter'])->value('name');
            $tds = round(($item['paper2_cost'] * 0.01), 0); // Calculate TDS as 1% of the amount
            $netPay = $item['paper2_cost'] - $tds; // Calculate Net Pay by deducting TDS from the amount
            $internalData = [
                'faculty_name' => $internalPaperSatterName,
                'amount' => $item['paper2_cost'],
                'tds' => $tds,
                'netpay' => $netPay,
                'bank_acc_no' => $item['bank_account'],
            ];
            $dataArray[] = $internalData;
        }

        // External Paper Setter
        if ($item['external_paper_setter']) {
            $externalPaperSatterName = Faculty::where('faculty_id', $item['external_paper_setter'])->value('name');
              $tds = round(($item['paper3_cost'] * 0.01), 0); // Calculate TDS as 1% of the amount
            $netPay = $item['paper3_cost'] - $tds; // Calculate Net Pay by deducting TDS from the amount
            $externalData = [
                'faculty_name' => $externalPaperSatterName,
                'amount' => $item['paper3_cost'],
                'tds' => $tds,
                'netpay' => $netPay,
                'bank_acc_no' => $item['bank_account'],
            ];
            $dataArray[] = $externalData;
        }
    }

    // Calculate the total amounts, TDS, and Net Pay
    $totalAmount = array_sum(array_column($dataArray, 'amount'));
    $totalTDS = array_sum(array_column($dataArray, 'tds'));
    $totalNetPay = array_sum(array_column($dataArray, 'netpay'));

    $pdf = PDF::loadView('report.reum_report_pdf', compact('dataArray', 'totalAmount', 'totalTDS', 'totalNetPay'));
    return $pdf->download('metrics.pdf');
}

         }catch(\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            toastr()->error(Config('messages.500'));
        }
    }
    
}
