<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
        @extends('layouts.app')

        @section('title', 'Student Bewerken')

        @section('content')
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
            <div class="max-w-7xl mx-auto px-4">
                <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Student Bewerken</h1>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($student->classrooms->count() > 0)
                    <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 rounded-lg text-blue-800 dark:text-blue-200">
                        <strong>Deze student hoort bij klas(sen):</strong>
                        <ul class="ml-4 list-disc">
                            @foreach($student->classrooms as $classroom)
                                <li>{{ $classroom->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-700 rounded-lg text-yellow-900 dark:text-yellow-200">Deze student hoort momenteel bij geen enkele klas.</div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 space-y-4">
                    <form id="student-update-form" method="POST" action="{{ route('posts.update', $student->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Naam:</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $student->name) }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Voer de naam van de student in"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="classroom_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Klas:</label>
                            <select name="classroom_id" id="classroom_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">{{ __('Geen klas') }}</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ (int) old('classroom_id', optional($student->classrooms->first())->id) === $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4 grid grid-cols-4 gap-8">
                            <div class="space-y-4">
                                {{-- Kolom 1: p01 en p02 --}}
                                @for($i = 1; $i <= 2; $i++)
                                <div>
                                    <span class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:</span>
                                    @php
                                        $pKey = 'p' . str_pad($i, 2, '0', STR_PAD_LEFT) . '_options';
                                        $savedOptions = $student->p_options[$pKey] ?? [];
                                        $currentOptions = (array) old($pKey, $savedOptions);
                                    @endphp
                                    <div class="mt-2 flex gap-4">
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="1" class="mr-2"
                                                   {{ in_array('1', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>1</span>
                                        </label>
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="2" class="mr-2"
                                                   {{ in_array('2', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>2</span>
                                        </label>
                                        @if(in_array($i, [2, 3, 7]))
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="3" class="mr-2"
                                                   {{ in_array('3', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>3</span>
                                        </label>
                                        @endif
                                    </div>
                                </div>
                                @endfor
                            </div>

                            <div class="space-y-4">
                                {{-- Kolom 2: p03 en p04 --}}
                                @for($i = 3; $i <= 4; $i++)
                                <div>
                                    <span class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:</span>
                                    @php
                                        $pKey = 'p' . str_pad($i, 2, '0', STR_PAD_LEFT) . '_options';
                                        $savedOptions = $student->p_options[$pKey] ?? [];
                                        $currentOptions = (array) old($pKey, $savedOptions);
                                    @endphp
                                    <div class="mt-2 flex gap-4">
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="1" class="mr-2"
                                                   {{ in_array('1', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>1</span>
                                        </label>
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="2" class="mr-2"
                                                   {{ in_array('2', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>2</span>
                                        </label>
                                        @if($i == 3)
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="3" class="mr-2"
                                                   {{ in_array('3', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>3</span>
                                        </label>
                                        @endif
                                    </div>
                                </div>
                                @endfor
                            </div>

                            <div class="space-y-4">
                                {{-- Kolom 3: p05 en p06 --}}
                                @for($i = 5; $i <= 6; $i++)
                                <div>
                                    <span class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:</span>
                                    @php
                                        $pKey = 'p' . str_pad($i, 2, '0', STR_PAD_LEFT) . '_options';
                                        $savedOptions = $student->p_options[$pKey] ?? [];
                                        $currentOptions = (array) old($pKey, $savedOptions);
                                    @endphp
                                    <div class="mt-2 flex gap-4">
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="1" class="mr-2"
                                                   {{ in_array('1', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>1</span>
                                        </label>
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="2" class="mr-2"
                                                   {{ in_array('2', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>2</span>
                                        </label>
                                    </div>
                                </div>
                                @endfor
                            </div>

                            <div class="space-y-4">
                                {{-- Kolom 4: p07 en p08 --}}
                                @for($i = 7; $i <= 8; $i++)
                                <div>
                                    <span class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:</span>
                                    @php
                                        $pKey = 'p' . str_pad($i, 2, '0', STR_PAD_LEFT) . '_options';
                                        $savedOptions = $student->p_options[$pKey] ?? [];
                                        $currentOptions = (array) old($pKey, $savedOptions);
                                    @endphp
                                    <div class="mt-2 flex gap-4">
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="1" class="mr-2"
                                                   {{ in_array('1', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>1</span>
                                        </label>
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="2" class="mr-2"
                                                   {{ in_array('2', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>2</span>
                                        </label>
                                        @if($i == 7)
                                        <label class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <input type="checkbox" name="p{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}_options[]" value="3" class="mr-2"
                                                   {{ in_array('3', $currentOptions, true) ? 'checked' : '' }}>
                                            <span>3</span>
                                        </label>
                                        @endif
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="opmerkingen" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Opmerkingen (optioneel):</label>
                            <textarea name="opmerkingen" id="opmerkingen" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('opmerkingen', $student->opmerkingen) }}</textarea>
                        </div>
                    </form>

                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <a href="{{ route('posts.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Terug naar Overzicht
                        </a>
                        <div class="flex gap-2">
                            <button type="submit" form="student-update-form"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Student Bijwerken
                            </button>
                            @auth
                            <form action="{{ route('posts.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze student wilt verwijderen? Dit kan niet ongedaan worden gemaakt?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Verwijderen
                                </button>
                            </form>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
</body>
</html>