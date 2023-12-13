<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Traits\Utilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    use Utilities;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = auth()->user()->students()->latest()->get();

        return response()->json(['students' => $students], 200);
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
           'fname' => 'required|string|max:255',
           'lname' => 'required|string|max:255',
           'adm_no' => 'required|string|unique:students',
           'class' => 'required',
           'stream_id' => 'nullable',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new student
        $student = Student::create([
            'uuid' => $this->generateUuid(),
            'user_id' => auth()->user()->id,
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'adm_no' => $request->input('adm_no'),
            'class' => $request->input('class'),
            'stream_id' => $request->input('stream'),
        ]);

        // Return a success response with the newly created student data
        // return response()->json(['student' => $student], 201);
        return response()->json(['student' => $student], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Validate incoming search query
        $validator = Validator::make(['query' => $query], [
           'query' => 'required|string|max:255',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Search for students based on the query parameter
        $students = auth()->user()->students()
            ->with('stream') // Assuming 'stream' is the relationship name
            ->where(function ($q) use ($query) {
                $q->where('fname', 'like', '%'.$query.'%')
                  ->orWhere('lname', 'like', '%'.$query.'%')
                  ->orWhere('adm_no', 'like', '%'.$query.'%');
            })
            ->latest()
            ->get();

        // Transforming stream_id to stream name in the response
        $students = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'uuid' => $student->uuid,
                'fname' => $student->fname,
                'lname' => $student->lname,
                'adm_no' => $student->adm_no,
                'user_id' => $student->user_id,
                'class' => $student->class,
                'stream' => $student->stream ? $student->stream->name : null, // Assuming 'name' is the stream name attribute
                // Add other attributes if needed
            ];
        });

        // Return a JSON response containing the search results with stream name
        return response()->json(['students' => $students], 200);
    }

    // public function search(Request $request)
    // {
    //     $query = $request->input('query');

    //     // Validate incoming search query
    //     $validator = Validator::make(['query' => $query], [
    //        'query' => 'required|string|max:255',
    //     ]);

    //     // If validation fails, return an error response
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     // Search for students based on the query parameter
    //     $students = auth()->user()->students()
    //         ->where(function ($q) use ($query) {
    //             $q->where('fname', 'like', '%'.$query.'%')
    //               ->orWhere('lname', 'like', '%'.$query.'%')
    //               ->orWhere('adm_no', 'like', '%'.$query.'%');
    //         })
    //         ->latest()
    //         ->get();

    //     // Return a JSON response containing the search results
    //     return response()->json(['students' => $students], 200);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
    }
}
