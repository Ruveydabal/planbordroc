<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Location;
use App\Models\Portfolio;
use App\Models\Classroom;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function students() 
    {
        $students = Student::with('locations')->get();
        $locations = Location::all();
        $portfolios = Portfolio::with('locations')->orderBy('sort_order')->get();
        $classrooms = Classroom::with('students')->get();
        return view('posts.index', [
            'students' => $students,
            'locations' => $locations,
            'portfolios' => $portfolios,
            'classrooms' => $classrooms
        ]);
    }

    public function create(Request $request)
    {
        $classroomId = $request->get('classroom_id');
        $classrooms = \App\Models\Classroom::all();
        return view('posts.create', compact('classroomId', 'classrooms'));
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
        $student = Student::with('locations')->find($id);
        return view('posts.edit', [
            'student' => $student
        ]);
    }

    public function storeOrUpdate(Request $request, Student $student = null)
    {
        $validated = $request->validate([
            'name'=> 'required|max:25',
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        if($student)
        {
            $student->update(['name' => $validated['name']]);
            // Bijwerken classroom: optioneel (optioneel toevoegen)
        } 
        else
        {
            $student = Student::create(['name' => $validated['name']]);
            $classroom = Classroom::find($validated['classroom_id']);
            if ($classroom) {
                $student->classrooms()->attach($classroom->id);
            }
            $allLocation = Location::where('name', 'all')->first();
            if ($allLocation) {
                $student->locations()->attach($allLocation->id);
            }
        }

        return redirect()->route('posts.index')
            ->with('success', $student ? 'Student succesvol bijgewerkt' : 'Student succesvol toegevoegd');
    }

    public function store(Request $request)
    {
        return $this->storeOrUpdate($request);
    }

    public function update(Request $request, string $id)
    {
        $students = Student::findOrFail($id);
        return $this->storeOrUpdate($request, $students);
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

    public function updateLocation(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);
            $location = Location::where('name', $request->location)->firstOrFail();
            
            // Verwijder alle bestaande locaties
            $student->locations()->detach();
            
            // Voeg de nieuwe locatie toe
            $student->locations()->attach($location->id);

            return response()->json([
                'success' => true,
                'message' => 'Locatie succesvol bijgewerkt'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Er is een fout opgetreden bij het bijwerken van de locatie'
            ], 500);
        }
    }

    public function resetAllToOverview()
    {
        $allLocation = \App\Models\Location::where('name', 'all')->first();
        if (!$allLocation) {
            return redirect()->route('posts.index')->with('error', 'Locatie "all" niet gevonden.');
        }
        $students = \App\Models\Student::all();
        foreach ($students as $student) {
            $student->locations()->sync([$allLocation->id]);
        }
        return redirect()->route('posts.index')->with('success', 'Alle studenten zijn teruggezet naar het overzicht.');
    }
}