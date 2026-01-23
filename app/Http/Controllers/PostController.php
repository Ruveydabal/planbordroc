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
        // Haal portfolios op en sorteer op sort_order (null komt onderaan)
        $portfolios = Portfolio::with('locations')
            ->orderByRaw('CASE WHEN sort_order IS NULL THEN 1 ELSE 0 END') // Null eerst
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $classrooms = Classroom::with(['students.locations'])->get();
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
        $student = Student::with('locations', 'classrooms')->find($id);
        $classrooms = Classroom::all();
        return view('posts.edit', [
            'student' => $student,
            'classrooms' => $classrooms,
        ]);
    }

    public function storeOrUpdate(Request $request, Student $student = null)
    {
        $classroomRule = $student ? 'nullable|exists:classrooms,id' : 'required|exists:classrooms,id';
        $validated = $request->validate([
            'name'=> 'required|max:25',
            'classroom_id' => $classroomRule,
            'opmerkingen' => 'nullable|string',
        ]);

        // Verzamel alle aangevinkte p01–p08 opties
        $pOptions = [];
        for ($i = 1; $i <= 8; $i++) {
            $key = 'p' . str_pad($i, 2, '0', STR_PAD_LEFT) . '_options';
            $values = $request->input($key, []);
            if (!empty($values)) {
                $pOptions[$key] = array_values($values);
            }
        }

        if ($student) {
            $student->name = $validated['name'];
            $student->opmerkingen = $validated['opmerkingen'] ?? null;
            $student->p_options = !empty($pOptions) ? $pOptions : null;

            if (array_key_exists('classroom_id', $validated)) {
                if (!empty($validated['classroom_id'])) {
                    $student->last_classroom_id = $validated['classroom_id'];
                    $student->classrooms()->sync([$validated['classroom_id']]);
                } else {
                    $student->last_classroom_id = null;
                    $student->classrooms()->detach();
                }
            }

            $student->save();
        } else {
            $student = Student::create([
                'name' => $validated['name'],
                'opmerkingen' => $validated['opmerkingen'] ?? null,
                'last_classroom_id' => $validated['classroom_id'],
                'p_options' => !empty($pOptions) ? $pOptions : null,
            ]);
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
            $previousClassroomId = $student->classrooms()->first()?->id;
            
            // Verwijder alle bestaande locaties
            $student->locations()->detach();
            // Voeg de nieuwe locatie toe
            $student->locations()->attach($location->id);

            if ($previousClassroomId) {
                $student->last_classroom_id = $previousClassroomId;
                $student->save();
            }

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

    public function updateClassroom(Request $request, $id)
    {
        try {
            $request->validate([
                'classroom_id' => 'required|exists:classrooms,id'
            ]);
            $student = Student::findOrFail($id);
            $classroomId = $request->classroom_id;
            $student->classrooms()->sync([$classroomId]);
            $student->last_classroom_id = $classroomId;
            $student->save();
            // Optioneel: locatie weer op 'all' zetten, als gewenst
            $allLocation = \App\Models\Location::where('name', 'all')->first();
            if ($allLocation) {
                $student->locations()->sync([$allLocation->id]);
            }
            return response()->json([
                'success' => true,
                'student_name' => $student->name,
                'message' => 'Student weer aan klas gekoppeld.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fout bij koppelen student aan klas'
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