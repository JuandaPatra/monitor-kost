<?php

namespace App\Export;

use App\Models\Leads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{

    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        
    }


    public function collection()
    {
        return Leads::select('id', 'name', 'email', 'phone', DB::raw('CAST(status AS CHAR) as status'), 'created_at')->whereBetween('created_at', [$this->startDate, $this->endDate])->get(); // Ambil data dari database
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Status',
            'Created At',
        ];
    }

       // Mengatur styling pada header
       public function styles(Worksheet $sheet)
       {
           return [
               1 => ['font' => ['bold' => true]], // Baris pertama (header) menjadi bold
           ];
       }
}