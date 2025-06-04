<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function students() 
    {
        $students = Student::all();
        return view('posts.index', [
            'students' => $students
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|max:25',
    //     ]);

    //     Student::create($request->all());

    //     return redirect()->route('posts.index')
    //         ->with('success', 'Student succesvol toegevoegd!');
    // }

    public function edit($id)
    {
        $student = Student::find($id);

        return view('posts.edit', [
            'student' => $student
        ]);
    }

    public function storeOrUpdate(Request $request, Student $student = null)
    {
        $validated = $request->validate([
            'name'=> 'required|max:25'
        ]);

        if($student)
        {
            $student->update($validated);
        } 
        else
        {
            Student::create($validated);
        }

        return redirect()->route('posts.index')
            ->with('success', $student ? 'Student succesvol bijgewerkt' : 'Student succesvol toegevoegd');
    }

    public function store(Request $request)
    {
        return $this-> storeOrUpdate($request);
    }

    public function update(Request $request, string $id)
    {
        $students = Student::findOrFail($id);
        return $this-> storeOrUpdate($request, $students);
    }

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return redirect()->route('posts.index')
                ->with('success', 'Student succesvol verwijderd!');
        } catch (\Exception $e) {
            return redirect()->route('posts.index')
                ->with('error', 'Er is een fout opgetreden bij het verwijderen van de student.');
        }
    }
}