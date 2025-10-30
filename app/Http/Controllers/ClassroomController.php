<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::withCount('students')->get();
        return view('classrooms.index', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        Classroom::create($validated);
        return redirect()->route('posts.index')->with('success', 'Klas toegevoegd');
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $classroom->update($validated);
        return back()->with('success', 'Klas bijgewerkt');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return back()->with('success', 'Klas verwijderd');
    }

    public function assignStudent(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:student,id'
        ]);
        $classroom->students()->syncWithoutDetaching([$validated['student_id']]);
        return response()->json(['success' => true]);
    }

    public function removeStudent(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:student,id'
        ]);
        $classroom->students()->detach($validated['student_id']);
        return response()->json(['success' => true]);
    }
}


