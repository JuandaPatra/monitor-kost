<?php

namespace App\Http\Controllers\API;

use App\Export\DataExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportDataController extends Controller
{
    public function exportData(Request $request) {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        return [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        return Excel::download(new DataExport($startDate, $endDate), 'leads.xlsx');
    }
}
