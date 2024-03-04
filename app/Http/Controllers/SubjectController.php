<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Traits\Utilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use Utilities;

    public function index()
    {
        $subject = auth()->user()->subjects()->latest()->get();

        return response()->json(['subjets' => $subject], 200);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:subjects',
         ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new student
        $subject = Subject::create([
            'uuid' => $this->generateUuid(),
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
        ]);

        // Return a success response with the newly created student data
        // return response()->json(['student' => $student], 201);
        return response()->json(['subject' => $subject], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
    }
}
