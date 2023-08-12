<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeacherPaperExport implements FromCollection, WithHeadings
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
            // dd($this->data);
    }


    public function collection()
{
    // return $this->data;
    return collect($this->data);
}



    public function headings(): array
    {
        return [
            'Department Name',
            'Subject Name',
            'Subject Code',
            'Selected Paper',
            'Selected Paper Date',
        ];
    }
    public function map($row): array
    {
        return [
            $row['department_name'],
            $row['subject_name'],
            $row['subject_code'],
            isset($row['selected_paper']) ? $row['selected_paper'] : null,
            isset($row['selected_p_date']) ? $row['selected_p_date'] : null,
        ];
    }
}
