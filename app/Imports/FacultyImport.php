<?php

namespace App\Imports;

use App\Models\Faculty;
use Maatwebsite\Excel\Concerns\ToModel;

class FacultyImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Faculty([
            //
            'name' => $row['name'],
            'email' => $row['email'],
            'mobile' => $row['mobile'],
            'department' => $row['department'],
            'bank_account' => $row['bank_account'],
            'bank_branch' => $row['bank_branch'],
            'ac_type' => $row['ac_type'],
            'ifsc_code' => $row['ifsc_code'],
            'designation' => $row['designation'],
        ]);
    }
}
