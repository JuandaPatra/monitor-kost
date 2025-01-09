<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Leads;
use App\Models\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class homecontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        
        $leadsStatusDistribution = Leads::select(
            DB::raw("
            CASE 
              WHEN leads.status = 0 THEN 'menghubungi pemilik'
              WHEN leads.status = 1 THEN 'menyewa kos'
              WHEN leads.status = 2 THEN 'Tidak jadi menyewa'
              WHEN leads.status = 3 THEN 'Tidak memberi feedback'
              ELSE 'Unknown'
            END as status
          "),
          DB::raw('COUNT(*) as total')
        )
        ->groupBy('status')
        ->orderBy('total', 'desc')
        ->get();



        $data = Cache::remember('leads_data', 600, function () {
            return [
                'properties' => Properties::select('id', 'name')->get(),
                'totalLeads' => Leads::count(),
                'todayLeads' => Leads::whereDate('created_at', today())->count(),
                'propertyMostChosen' => Leads::select('property_id', 'properties.name',  DB::raw('count(*) as total'))
                    ->leftJoin('properties', 'leads.property_id', '=', 'properties.id')
                    ->groupBy('property_id')
                    ->orderBy('total', 'desc')
                    ->get(),
                'last7DaysLeads' => Leads::where('created_at', '>=', Carbon::now()->subDays(7))
                ->select(
                    DB::raw("DATE(created_at) as date"),
                    DB::raw("COUNT(*) as total")
                )
                ->groupBy(DB::raw("DATE(created_at)"))
                ->orderBy(DB::raw("DATE(created_at)"), 'asc')
                ->get()
            ];
        });
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
