<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $sortBy = $request->get('sortBy', 'leads.created_at'); // Default sorting by 'created_at'
        $sortOrder = $request->get('sortOrder', 'desc'); // Default order 'desc'
        $keyword = $request->get('q', null); // Default no keyword
        $perPage = $request->input('per_page', 10);
        $startDate = $request->get('start_date', null); // Start date for filtering
        $endDate = $request->get('end_date', null); // End date for filtering
        // Build the query
        $leads = Leads::query();
        $leads->leftJoin('properties', 'leads.property_id', '=', 'properties.id')->select(
            'leads.*',
            DB::raw("COALESCE(properties.name, 'Tanpa Properti') as property_name"),
            DB::raw("
        CASE 
          WHEN leads.status = 0 THEN 'menghubungi pemilik'
          WHEN leads.status = 1 THEN 'menyewa kos'
          WHEN leads.status = 2 THEN 'Tidak jadi menyewa'
          WHEN leads.status = 3 THEN 'Tidak memberi feedback'
          ELSE 'Unknown'
        END as status
      "),
            'leads.status as status_id',

            DB::raw("DATE_FORMAT(leads.created_at, '%d-%m-%Y') as date")
        );



        // Apply keyword search (searching by name or email)
        if ($keyword) {
            $leads->where(function ($query) use ($keyword) {
                $query->where('leads.name', 'like', "%{$keyword}%")
                    ->orWhere('leads.email', 'like', "%{$keyword}%");
            });
        }

        if ($startDate && $endDate) {
            $leads->whereBetween('leads.created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $leads->whereDate('leads.created_at', '>=', $startDate);
        } elseif ($endDate) {
            $leads->whereDate('leads.created_at', '<=', $endDate);
        }

        // Apply sorting
        $leads->orderBy($sortBy, $sortOrder);

        // Fetch results

        return response()->json([
            'status' => 'success',
            'data' => $leads->paginate($perPage)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            $validated = $request->validate([
                'property_id'   => 'required',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:leads,email',
                'phone' => 'required|string|max:15',
                'status' => 'required',
            ]);

            // Simpan data leads ke database
            $lead = Leads::create($validated);

            // Kembalikan respons
            return response()->json([
                'status' => 'success',
                'message' => 'Lead stored successfully.',
                'data' => $lead,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
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
        $lead = Leads::where('id', $id)->first();

        try {

            $validated = $request->validate([
                'status' => 'required',
            ]);

            $lead->update($validated);
            if (!$lead) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lead not found.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Lead updated successfully.',
                'data' => $lead,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
