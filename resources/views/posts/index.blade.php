<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Studenten Overzicht</h1>

        @extends('layouts.app')

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @section('content')
        <div class="mb-4">
            <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nieuwe Student Toevoegen
            </a>
        </div>

        @if($students->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($students as $student)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">{{ $student->name }}</h2>
                                <p class="text-sm text-gray-500 mt-1">ID: {{ $student->id }}</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                Student #{{ $loop->iteration }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            @if($student->created_at)
                                Aangemaakt op: {{ $student->created_at->format('d-m-Y H:i') }}
                            @else
                                Aangemaakt op: Onbekend
                            @endif
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('posts.edit', $student->id) }}" 
                               class="text-blue-500 hover:text-blue-700 font-bold">
                                Bewerken
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                <div class="flex justify-between items-center">
                    <div class="text-gray-600">
                        Totaal aantal studenten: {{ $students->count() }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600 text-lg">Er zijn nog geen studenten beschikbaar.</p>
            </div>
        @endif
    </div>
</body>
</html>