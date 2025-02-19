<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Properties;
use Illuminate\Http\Request;

class KostContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $keyword = $request->get('q', null); // Default no keyword
        $perPage = $request->input('per_page', 10);

        $listsKost = Properties::query();

        $listsKost->leftJoin('users', 'properties.user_id', '=', 'users.id')->select('properties.id', 'properties.name', 'properties.address', 'properties.price', 'users.name as kost_owner' );

        if ($keyword) {
            $listsKost->where(function ($query) use ($keyword) {
                $query->where('properties.name', 'like', "%{$keyword}%")
                    ->orWhere('properties.address', 'like', "%{$keyword}%")
                    ->orWhere('users.name', 'like', "%{$keyword}%");
            });
        }

        return response()->json([
            'status' => 'success',
            'data'   => $listsKost->paginate($perPage)
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'user_id'=> 'required',
                'address' => 'required|string|max:255',
                'price' => 'required|string|max:15',
                'description' => 'required',
            ]);

            $addKost = Properties::create($validated);
            // Kembalikan respons
            return response()->json([
                'status' => 'success',
                'message' => 'Properties stored successfully.',
                'data' => $addKost,
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $properties = Properties::where('id', $id)->first();

        
        try{

            $validated = $request->validate([
                'name' => 'required',
                'address' => 'required',
                'price' => 'required',
                'description' => 'required'
            ]);
    
            $properties->update($validated);
            if (!$properties) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Properties not found.',
                ], 404);
                
            }
    
            // Kembalikan respons
            return response()->json([
                'status' => 'success',
                'message' => 'Properties updated successfully.',
                'data' => $properties,
            ], 200);
        }
        catch (\Exception $e) {
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
        $properties = Properties::where('id', $id)->first();

        try{
            $properties->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Properties updated successfully.',
                'data' => 'data with id ' . $id . ' has deleted',
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
