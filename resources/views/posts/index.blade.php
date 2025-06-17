@extends('layouts.app')

@section('title', 'Studenten Overzicht')

@section('content')
<head>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>
<div class="bg-gray-100 dark:bg-gray-900">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 min-h-[450px]">
        <!-- Eerste kolom - Studenten Overzicht -->
        <div class="studenten-kolom bg-gray-100 dark:bg-gray-700 h-[400px] flex flex-col px-4">
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
                <div class="flex-1 overflow-y-auto ">
                    <div id="all-students" class="grid grid-cols-1 gap-4 min-h-[100px]" data-location="all">
                        @foreach($students as $student)
                            @if(!$student->location || $student->location === 'all')
                                <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-student-id="{{ $student->id }}" data-location="{{ $student->location }}">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $student->name }}</h2>
                                    <div class="mt-3 flex space-x-4">
                                        <a href="{{ route('posts.edit', $student->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-sm">
                                            Bewerken
                                        </a>
                                        <button onclick="showDeleteModal({{ $student->id }})" class="text-red-500 hover:text-red-700 font-bold text-sm">
                                            Verwijderen
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
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

        <!-- Tweede kolom -->
        <div class="info-kolom bg-gray-100 dark:bg-gray-700 h-[400px] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 h-full">
                <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Indeling Studenten</h1>
                <!-- Dropzones voor kamers -->
                <div id="kamer-PraktijkHal" class="kamer-dropzone flex flex-col items-center bg-white dark:bg-gray-900 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow px-4 text-lg font-semibold text-white mb-4 min-h-[60px]" data-location="PraktijkHal">
                    PraktijkHal
                    @foreach($students as $student)
                        @if($student->location === 'PraktijkHal')
                            <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-student-id="{{ $student->id }}" data-location="{{ $student->location }}">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $student->name }}</h2>
                                <div class="mt-3 flex space-x-4">
                                    <a href="{{ route('posts.edit', $student->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-sm">
                                        Bewerken
                                    </a>
                                    <button onclick="showDeleteModal({{ $student->id }})" class="text-red-500 hover:text-red-700 font-bold text-sm">
                                        Verwijderen
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div id="kamer-Studieplein" class="kamer-dropzone flex flex-col items-center bg-white dark:bg-gray-900 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow px-4 text-lg font-semibold text-white mb-4 min-h-[60px]" data-location="Studieplein">
                    Studieplein
                    @foreach($students as $student)
                        @if($student->location === 'Studieplein')
                            <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-student-id="{{ $student->id }}" data-location="{{ $student->location }}">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $student->name }}</h2>
                                <div class="mt-3 flex space-x-4">
                                    <a href="{{ route('posts.edit', $student->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-sm">
                                        Bewerken
                                    </a>
                                    <button onclick="showDeleteModal({{ $student->id }})" class="text-red-500 hover:text-red-700 font-bold text-sm">
                                        Verwijderen
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div id="kamer-Afwezig" class="kamer-dropzone flex flex-col items-center bg-white dark:bg-gray-900 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow px-4 text-lg font-semibold text-white mb-4 min-h-[60px]" data-location="Afwezig">
                    Afwezig
                    @foreach($students as $student)
                        @if($student->location === 'Afwezig')
                            <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-student-id="{{ $student->id }}" data-location="{{ $student->location }}">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $student->name }}</h2>
                                <div class="mt-3 flex space-x-4">
                                    <a href="{{ route('posts.edit', $student->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-sm">
                                        Bewerken
                                    </a>
                                    <button onclick="showDeleteModal({{ $student->id }})" class="text-red-500 hover:text-red-700 font-bold text-sm">
                                        Verwijderen
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Derde kolom -->
        <div class="info-kolom bg-gray-100 dark:bg-gray-700 h-[60vh] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 h-full">
                <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Derde Kolom</h1>
                <!-- Hier komt de inhoud van de derde kolom -->
            </div>
        </div>
    </div>
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

    // Drag & drop functionaliteit
    document.addEventListener('DOMContentLoaded', function() {
        // Maak de kamers tot dropzones
        ['PraktijkHal', 'Studieplein', 'Afwezig'].forEach(function(kamer) {
            new Sortable(document.getElementById('kamer-' + kamer), {
                group: 'students',
                animation: 150,
                onAdd: function (evt) {
                    const studentCard = evt.item;
                    const studentId = studentCard.getAttribute('data-student-id');
                    const newLocation = kamer;
                    // AJAX call om locatie te updaten
                    fetch(`/posts/${studentId}/location`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ location: newLocation })
                    }).then(response => {
                        if (!response.ok) {
                            alert('Kon locatie niet bijwerken!');
                        }
                    });
                }
            });
        });
        // Maak de algemene studentenlijst ook een Sortable (voor terugzetten)
        new Sortable(document.getElementById('all-students'), {
            group: 'students',
            animation: 150,
            onAdd: function (evt) {
                const studentCard = evt.item;
                const studentId = studentCard.getAttribute('data-student-id');
                // AJAX call om locatie te updaten naar 'all'
                fetch(`/posts/${studentId}/location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ location: 'all' })
                }).then(response => {
                    if (!response.ok) {
                        alert('Kon locatie niet bijwerken!');
                    }
                });
            }
        });
    });
</script>
@endsection
