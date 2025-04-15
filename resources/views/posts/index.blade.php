<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .student-card {
            width: 200px;
            margin-bottom: 1rem;
        }
        .student-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
    </style>
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
            <div class="student-container">
                @foreach($students as $student)
                    <div class="student-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">{{ $student->name }}</h2>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-4">
                            <a href="{{ route('posts.edit', $student->id) }}" 
                               class="text-blue-500 hover:text-blue-700 font-bold">
                                Bewerken
                            </a>
                            <button onclick="showDeleteModal({{ $student->id }})" 
                                    class="text-red-500 hover:text-red-700 font-bold">
                                Verwijderen
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="bg-white p-6 rounded-lg shadow-xl">
                        <h3 class="text-lg font-bold mb-4">Bevestig Verwijdering</h3>
                        <p class="mb-4">Weet je zeker dat je deze student wilt verwijderen?</p>
                        <div class="flex justify-end space-x-4">
                            <button onclick="hideDeleteModal()" 
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuleren
                            </button>
                            <form id="deleteForm" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Verwijderen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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

    <script>
        function showDeleteModal(studentId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/posts/${studentId}`;
            modal.classList.remove('hidden');
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>