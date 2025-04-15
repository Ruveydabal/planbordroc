<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Student Bewerken</h1>

        @extends('layouts.app')

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @section('content')
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('posts.update', $student->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Naam:</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $student->name) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           placeholder="Voer de naam van de student in"
                           required>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Student Bijwerken
                    </button>
                    <a href="{{ route('posts.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Terug naar Overzicht
                    </a>
                </div>
            </form>
        </div>
        @endsection
    </div>
</body>
</html>