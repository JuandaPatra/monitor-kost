<?php

use App\Http\Controllers\API\homecontroller;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\LeadsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Export\DataExport;
use App\Http\Controllers\API\ExportDataController;
use App\Http\Controllers\API\KostContoller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['api'])->group(function () {
    Route::apiResource('/leads', 
    LeadsController::class);

    Route::apiResource('/home', homecontroller::class);
    Route::apiResource('/statistics', StatisticController::class);

    Route::get('/export-excel', function (Request $request) {
        $startDate = Carbon::parse($request->input('start_date'))->toDateTimeString();
        $endDate = Carbon::parse($request->input('end_date'))->toDateTimeString();
    
        return Excel::download(new DataExport($startDate, $endDate), 'data'. $startDate .'-'.$request->input('end_date').'.xlsx');
    });
    
    Route::get('/kost/list', [KostContoller::class, 'ListKost']);
    Route::apiResource('/kost', KostContoller::class);

});



