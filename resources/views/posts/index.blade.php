@extends('layouts.app')

@section('title', 'Studenten Overzicht')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Studenten Overzicht</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nieuwe Student Toevoegen
            </a>
        </div>

        @if($students->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($students as $student)
                    <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ $student->name }}</h2>
                        <div class="mt-4 flex space-x-4">
                            <a href="{{ route('posts.edit', $student->id) }}" class="text-blue-500 hover:text-blue-700 font-bold">
                                Bewerken
                            </a>
                            <button onclick="showDeleteModal({{ $student->id }})" class="text-red-500 hover:text-red-700 font-bold">
                                Verwijderen
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Delete Modal -->
            <div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
                        <h3 id="modalTitle" class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Bevestig Verwijdering</h3>
                        <p class="mb-4 text-gray-700 dark:text-gray-300">Weet je zeker dat je deze student wilt verwijderen?</p>
                        <div class="flex justify-end space-x-4">
                            <button onclick="hideDeleteModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuleren
                            </button>
                            <form id="deleteForm" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Verwijderen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-gray-600 dark:text-gray-300">
                Totaal aantal studenten: {{ $students->count() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600 dark:text-gray-300 text-lg">Er zijn nog geen studenten beschikbaar.</p>
            </div>
        @endif
    </div>
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
@endsection
