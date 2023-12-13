<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Traits\Utilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use Utilities;

    public function index()
    {
        $stream = auth()->user()->streams()->latest()->get();

        return response()->json(['streams' => $stream], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
           'name' => 'required|string|max:255',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new student
        $stream = Stream::create([
            'uuid' => $this->generateUuid(),
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
        ]);

        // Return a success response with the newly created student data
        // return response()->json(['student' => $student], 201);
        return response()->json(['stream' => $stream], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stream $stream)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stream $stream)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stream $stream)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stream $stream)
    {
    }
}
