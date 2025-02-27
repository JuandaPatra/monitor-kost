<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Row;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->get('id');
        $listsKamar = Room::where('properties_id', '=', $id)->get();


        return response()->json([
            'status' => 'success',
            'data'   => $listsKamar
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
                'properties_id' => 'required',
                'room_number'=> 'required',
                'type' => 'required',
                'price' => 'required',
                'status' => 'required',
            ]);

            $addRoom = Room::create($validated);
            // Kembalikan respons
            return response()->json([
                'status' => 'success',
                'message' => 'Room stored successfully.',
                'data' => $addRoom,
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
        $room = Room::where('id', $id)->first();

        try{
            $validated = $request->validate([
                'properties_id' => 'required',
                'room_number'=> 'required',
                'type' => 'required',
                'price' => 'required',
                'status' => 'required',
            ]);

            $room->update($validated);
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
        //
    }
}
