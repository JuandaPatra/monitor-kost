<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $sortBy = $request->get('sortBy', 'created_at'); // Default sorting by 'created_at'
        $sortOrder = $request->get('sortOrder', 'desc'); // Default order 'desc'
        $keyword = $request->get('keyword', null); // Default no keyword
        $perPage = $request->input('per_page', 10);
        // Build the query
        $leads = Leads::query();

        // Apply keyword search (searching by name or email)
        if ($keyword) {
            $leads->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%");
            });
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

        try{

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
            ], 201);

        }catch (\Exception $e) {
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
