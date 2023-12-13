<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Traits\Utilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use Utilities;

    public function index()
    {
        $book = auth()->user()->books()->latest()->get();

        return response()->json(['books' => $book], 200);
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
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
         ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new student
        $book = Book::create([
            'uuid' => $this->generateUuid(),
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'subject_id' => $request->input('subject_id'),
        ]);

        // Return a success response with the newly created student data
        // return response()->json(['student' => $student], 201);
        return response()->json(['book' => $book], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
    }
}
