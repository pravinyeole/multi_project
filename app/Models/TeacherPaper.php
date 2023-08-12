<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TeacherPaper extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'department_id', 'class_id','subject_id','exam_date', 'pattern','chairman','email', 'internal_paper_satter', 'external_paper_satter', 'paper1', 'paper2', 'paper3', 'paper1_cost', 'paper2_cost', 'paper3_cost', 'total', 'quantity1', 'quantity2' ,'quantity3', 'date', 'selected_paper', 'selected_p_date','deleted_at' 
    ];


    public $primaryKey = 'id';
    protected $table    = 'teacher_paper';

     /**
     * retrun qrcode
     */
    public function getQRCode()
    {
        try {
            
            // return QrCode::size(70)->generate('jhsgsdhfhd')->toHtml();

            $data = '1234567890'; // The data you want to encode in the barcode
        
        $barcode = QrCode::format('png')
            ->size(40) // The size of the barcode
            ->errorCorrection('H') // The error correction level (L, M, Q, H)
            ->generate($data);
            

        return response($barcode)->header('Content-Type', 'image/png');
        } catch (\Exception $e) {
            return $this->card_number;
        }
    }
}
