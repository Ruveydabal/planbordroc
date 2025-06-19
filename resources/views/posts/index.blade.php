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

            <div class="mb-4 flex gap-2">
                <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center w-56">
                    Nieuwe Student Toevoegen
                </a>
                <form action="{{ route('posts.resetAll') }}" method="POST" onsubmit="return confirm('Weet je zeker dat je alle studenten terug wilt zetten naar het overzicht?');">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Alle studenten terug naar overzicht
                    </button>
                </form>
            </div>

            @if($students->count() > 0)
                <div class="flex-1 overflow-y-auto ">
                    <div id="all-students" class="kamer-dropzone grid grid-cols-1 gap-4 min-h-[100px]" data-location="all">
                        @foreach($students as $student)
                            @if($student->locations->where('name', 'all')->count() > 0)
                                <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-student-id="{{ $student->id }}" data-location="all">
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
                @foreach($locations as $location)
                    @if($location->name !== 'all')
                        <div id="kamer-{{ $location->name }}" class="kamer-dropzone flex flex-col items-center bg-white dark:bg-gray-900 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow px-4 text-lg font-semibold text-white mb-4 min-h-[60px]" data-location="{{ $location->name }}">
                            {{ $location->display_name }}
                            @foreach($students as $student)
                                @if($student->locations->where('name', $location->name)->count() > 0)
                                    <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-student-id="{{ $student->id }}" data-location="{{ $location->name }}">
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
                    @endif
                @endforeach
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

    document.addEventListener('DOMContentLoaded', function() {
        const studentCards = document.querySelectorAll('.student-card');
        const dropzones = document.querySelectorAll('.kamer-dropzone');

        studentCards.forEach(card => {
            card.setAttribute('draggable', true);
            card.addEventListener('dragstart', handleDragStart);
            card.addEventListener('dragend', handleDragEnd);
        });

        dropzones.forEach(zone => {
            zone.addEventListener('dragover', handleDragOver);
            zone.addEventListener('drop', handleDrop);
        });

        function handleDragStart(e) {
            e.target.classList.add('opacity-50');
            e.dataTransfer.setData('text/plain', e.target.dataset.studentId);
        }

        function handleDragEnd(e) {
            e.target.classList.remove('opacity-50');
        }

        function handleDragOver(e) {
            e.preventDefault();
        }

        function handleDrop(e) {
            e.preventDefault();
            const studentId = e.dataTransfer.getData('text/plain');
            const newLocation = e.target.closest('.kamer-dropzone').dataset.location;
            const studentCard = document.querySelector(`[data-student-id="${studentId}"]`);
            const oldLocation = studentCard.dataset.location;

            // Update de locatie via AJAX
            fetch(`/posts/${studentId}/location`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ location: newLocation })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Verwijder de kaart van de oude locatie
                    const oldDropzone = document.querySelector(`#kamer-${oldLocation}`);
                    if (oldDropzone) {
                        oldDropzone.removeChild(studentCard);
                    }

                    // Voeg de kaart toe aan de nieuwe locatie
                    const newDropzone = document.querySelector(`#kamer-${newLocation}`);
                    if (newDropzone) {
                        studentCard.dataset.location = newLocation;
                        newDropzone.appendChild(studentCard);
                    }

                    // Herlaad de pagina om de wijzigingen te tonen
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Er is een fout opgetreden bij het verplaatsen van de student.');
            });
        }
    });
</script>
@endsection
