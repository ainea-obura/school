<?php

namespace App\Http\Controllers;

use App\Models\Assign;
use App\Models\Student;
use App\Models\User;
use App\Traits\Utilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use Utilities;

    public function index()
    {
        $assign = auth()->user()->assign()->latest()->get();

        return response()->json(['assign' => $assign], 200);
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
            'book_name' => 'required|string|max:255',
            'book_no' => 'required|string|max:255|unique:assigns',
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'nullable',
         ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Check if the book is already assigned to the student
        $isAssigned = Assign::where('book_name', $request->input('book_name'))
                            ->where('book_no', $request->input('book_no'))
                            ->where('student_id', $request->input('student_id'))
                            ->exists();

        if ($isAssigned) {
            return response()->json(['error' => 'This book is already assigned to the student.'], 400);
        }

        // Create a new assignment
        $assignment = Assign::create([
            'uuid' => $this->generateUuid(),
            'book_name' => $request->input('book_name'),
            'book_no' => $request->input('book_no'),
            'user_id' => auth()->user()->id,
            'student_id' => $request->input('student_id'),
            'assigned' => true, // book assigned upon creation
        ]);

        // Update the assigned status of the book in the 'assigns' table
        // if ($assignment) {
        //     Student::where('id', $request->input('student_id'))->update(['assigned' => true]);
        // }

        // Return a success response with the newly created assignment data
        return response()->json(['assignment' => $assignment], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();

        // Check if the user has the requested student
        $student = $user->students()->find($id);

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Fetch assignments for the specific student
        // $assignments = $student->assign()->latest()->get();
        $assignments = $student->assign()->where('assigned', true)->latest()->get();

        return response()->json(['assignments' => $assignments], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assign $assign)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assign $assign)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assign $assign)
    {
    }
}
