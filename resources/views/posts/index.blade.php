@extends('layouts.app')

@section('title', 'Studenten Overzicht')

@section('content')
<head>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>
<div class="bg-gray-100 dark:bg-gray-900">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 min-h-[650px]">
        <!-- Eerste kolom - Studenten Overzicht -->
        <div class="studenten-kolom bg-gray-100 dark:bg-gray-700 h-[600px] flex flex-col px-4">
            <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Studenten Overzicht</h1>

            @auth
                <div class="mb-4 flex gap-2 items-center">
                    <form action="{{ route('posts.resetAll') }}" method="POST" onsubmit="return confirm('Weet je zeker dat je alle studenten terug wilt zetten naar het overzicht?');">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold text-sm py-1 px-3 rounded">
                            Terug naar overzicht
                        </button>
                    </form>
                    <button onclick="showAddClassroomModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold text-sm py-1 px-3 rounded">
                        Klas toevoegen
                    </button>
                </div>
            @endauth

            <div class="flex-1 overflow-y-auto ">
                <div id="all-students" class="kamer-dropzone grid grid-cols-1 gap-4 min-h-[100px]" data-location="all">
                    @if($students->count() > 0)
                        @foreach($students as $student)
                            @if($student->classrooms->count() == 0 && $student->locations->where('name', 'all')->count() > 0)
                                <a href="{{ route('posts.edit', $student->id) }}" class="block student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2 cursor-pointer" data-student-id="{{ $student->id }}" data-location="all">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $student->name }}</h2>
                                </a>
                            @endif
                        @endforeach
                    @endif
                    
                    <!-- Klassen blokken - altijd zichtbaar -->
                    @if(isset($classrooms) && $classrooms->count() > 0)
                        @foreach($classrooms as $classroom)
                            <div id="classroom-{{ $classroom->id }}" class="classroom-dropzone bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-classroom-id="{{ $classroom->id }}">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">{{ $classroom->name }}</h2>
                                @if($classroom->students->count() > 0)
                                    <div class="space-y-2" id="classroom-students-{{ $classroom->id }}">
                                        @foreach($classroom->students as $student)
                                            <a href="{{ route('posts.edit', $student->id) }}" class="block student-card bg-gray-100 dark:bg-gray-700 rounded p-2 text-sm cursor-pointer cursor-move" data-student-id="{{ $student->id }}" data-classroom-id="{{ $classroom->id }}">
                                                <span class="text-gray-800 dark:text-gray-200">{{ $student->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="space-y-2" id="classroom-students-{{ $classroom->id }}">
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Geen studenten in deze klas</p>
                                    </div>
                                @endif
                                @auth
                                    <div class="mt-3 flex flex-col space-y-2">
                                        <div class="flex space-x-4">
                                            <button onclick="editClassroom({{ $classroom->id }})" class="text-blue-500 hover:text-blue-700 font-bold text-sm">
                                                Bewerken
                                            </button>
                                            <form action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Weet je zeker dat je deze klas wilt verwijderen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm">
                                                    Verwijderen
                                                </button>
                                            </form>
                                        </div>
                                        <a href="{{ route('posts.create') }}?classroom_id={{ $classroom->id }}" class="bg-green-500 hover:bg-green-700 text-white font-semibold text-sm py-1 px-3 rounded text-center inline-block">
                                            Student toevoegen
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            
            @if($students->count() > 0)
                <div class="mt-8 text-gray-600 dark:text-gray-300">
                    Totaal aantal studenten: {{ $students->count() }}
                </div>
            @endif

        </div>

        <!-- Tweede kolom -->
        <div class="info-kolom bg-gray-100 dark:bg-gray-700 h-[600px] overflow-hidden">
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
                                        @auth
                                            <div class="mt-3 flex space-x-4">
                                                <a href="{{ route('posts.edit', $student->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-sm">
                                                    Bewerken
                                                </a>
                                                <button onclick="showDeleteModal({{ $student->id }})" class="text-red-500 hover:text-red-700 font-bold text-sm">
                                                    Verwijderen
                                                </button>
                                            </div>
                                        @endauth
                                    </div>
                                @endif
                            @endforeach
                            @if(isset($portfolios))
                                @foreach($portfolios as $portfolio)
                                    @if($portfolio->locations && $portfolio->locations->where('name', $location->name)->count() > 0)
                                        <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-portfolio-id="{{ $portfolio->id }}" data-location="{{ $location->name }}">
                                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $portfolio->title }}</h2>
                                            @if(!empty($portfolio->description))
                                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $portfolio->description }}</p>
                                            @endif
                                            @if(!empty($portfolio->link))
                                                <a href="{{ $portfolio->link }}" target="_blank" class="mt-3 inline-block text-blue-500 hover:text-blue-700 font-bold text-sm">Bekijken</a>
                                            @endif
                                            @auth
                                                <div class="mt-3 flex space-x-4">
                                                    <a href="{{ route('portfolios.edit', $portfolio->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-sm">Bewerken</a>
                                                    <button onclick="showPortfolioDeleteModal({{ $portfolio->id }})" class="text-red-500 hover:text-red-700 font-bold text-sm">Verwijderen</button>
                                                </div>
                                            @endauth
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Derde kolom - Portfolio Overzicht (zelfde structuur als eerste kolom) -->
        <div class="studenten-kolom bg-gray-100 dark:bg-gray-700 h-[600px] flex flex-col px-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Portfolio Overzicht</h1>
                @auth
                    <div class="flex gap-2 mb-6">
                        <a href="{{ route('portfolios.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">Nieuw Portfolio</a>
                        <form action="{{ route('portfolios.resetAll') }}" method="POST" onsubmit="return confirm('Weet je zeker dat je alle portfolio\'s terug wilt zetten naar het overzicht?');">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Alle portfolio's terug naar overzicht</button>
                        </form>
                    </div>
                @endauth
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(isset($portfolios) && $portfolios->where('locations.*.name', 'all')->count() > 0 || $portfolios->count() > 0)
                <div class="flex-1 overflow-y-auto ">
                    <div id="all-portfolios" class="kamer-dropzone grid grid-cols-1 gap-4 min-h-[100px]" data-section="portfolio" data-location="all">
                        @foreach($portfolios as $portfolio)
                            @php
                                $inAll = $portfolio->locations && $portfolio->locations->where('name', 'all')->count() > 0;
                            @endphp
                            @if($inAll)
                            <div class="student-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 w-full mb-2" data-portfolio-id="{{ $portfolio->id }}" data-location="all">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $portfolio->title }}</h2>
                                @if(!empty($portfolio->description))
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $portfolio->description }}</p>
                                @endif
                                @if(!empty($portfolio->link))
                                    <a href="{{ $portfolio->link }}" target="_blank" class="mt-3 inline-block text-blue-500 hover:text-blue-700 font-bold text-sm">Bekijken</a>
                                @endif
                                @auth
                                    <div class="mt-3 flex space-x-4">
                                        <a href="{{ route('portfolios.edit', $portfolio->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-sm">Bewerken</a>
                                        <button onclick="showPortfolioDeleteModal({{ $portfolio->id }})" class="text-red-500 hover:text-red-700 font-bold text-sm">Verwijderen</button>
                                    </div>
                                @endauth
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="mt-8 text-gray-600 dark:text-gray-300">
                    Totaal aantal portfolio's: {{ $portfolios->count() }}
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600 dark:text-gray-300 text-lg">Er zijn nog geen portfolio's beschikbaar.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
            <h3 id="modalTitle" class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Bevestig Verwijdering</h3>
            <p class="mb-4 text-gray-700 dark:text-gray-300" id="deleteModalText">Weet je zeker dat je deze student wilt verwijderen?</p>
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

<!-- Add Classroom Modal -->
<div id="addClassroomModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
            <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Nieuwe Klas Toevoegen</h3>
            <form id="addClassroomForm" method="POST" action="{{ route('classrooms.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="classroomName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Naam van de klas
                    </label>
                    <input type="text" id="classroomName" name="name" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                           placeholder="Voer klas naam in" required>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideAddClassroomModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuleren
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Klas Toevoegen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showDeleteModal(studentId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/posts/${studentId}`;
        document.getElementById('deleteModalText').textContent = 'Weet je zeker dat je deze student wilt verwijderen?';
        modal.classList.remove('hidden');
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }

    function showPortfolioDeleteModal(portfolioId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/portfolios/${portfolioId}`;
        document.getElementById('deleteModalText').textContent = 'Weet je zeker dat je dit portfolio wilt verwijderen?';
        modal.classList.remove('hidden');
    }

    function editClassroom(classroomId) {
        const newName = prompt('Nieuwe naam voor de klas:');
        if (!newName) return;
        
        fetch(`/classrooms/${classroomId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name: newName })
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Klas bijwerken mislukt.');
            }
        })
        .catch(() => alert('Klas bijwerken mislukt.'));
    }


    function showAddClassroomModal() {
        const modal = document.getElementById('addClassroomModal');
        const input = document.getElementById('classroomName');
        modal.classList.remove('hidden');
        input.focus();
    }

    function hideAddClassroomModal() {
        const modal = document.getElementById('addClassroomModal');
        const form = document.getElementById('addClassroomForm');
        modal.classList.add('hidden');
        form.reset();
    }


    document.addEventListener('DOMContentLoaded', function() {
        const studentCards = document.querySelectorAll('.student-card[data-student-id]');
        const portfolioCards = document.querySelectorAll('.student-card[data-portfolio-id]');
        const dropzones = document.querySelectorAll('.kamer-dropzone, .classroom-dropzone');
        const allPortfoliosZone = document.getElementById('all-portfolios');
        const portfolioColumnWrapper = allPortfoliosZone ? allPortfoliosZone.closest('.studenten-kolom') : null;

        // Modal event listeners
        const addClassroomModal = document.getElementById('addClassroomModal');
        if (addClassroomModal) {
            addClassroomModal.addEventListener('click', function(e) {
                if (e.target === addClassroomModal) {
                    hideAddClassroomModal();
                }
            });
        }

        // Setup drag and drop for all student cards (including those in classrooms)
        studentCards.forEach(card => {
            card.setAttribute('draggable', true);
            card.addEventListener('dragstart', handleDragStart);
            card.addEventListener('dragend', handleDragEnd);
        });

        // Ook studenten in klassen draggable maken
        const classroomStudentCards = document.querySelectorAll('.student-card[data-student-id]');
        classroomStudentCards.forEach(card => {
            card.setAttribute('draggable', true);
            card.addEventListener('dragstart', handleDragStart);
            card.addEventListener('dragend', handleDragEnd);
        });

        // Setup drop zones for classrooms
        const classroomDropzones = document.querySelectorAll('.classroom-dropzone');
        classroomDropzones.forEach(zone => {
            zone.addEventListener('dragover', handleDragOver);
            zone.addEventListener('drop', handleClassroomDrop);
            // Voeg visuele feedback toe tijdens drag
            zone.addEventListener('dragenter', function(e) {
                e.preventDefault();
                zone.classList.add('bg-blue-100', 'dark:bg-blue-900');
            });
            zone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                zone.classList.remove('bg-blue-100', 'dark:bg-blue-900');
            });
        });

        portfolioCards.forEach(card => {
            card.setAttribute('draggable', true);
            card.addEventListener('dragstart', handleDragStart);
            card.addEventListener('dragend', handleDragEnd);
        });

        dropzones.forEach(zone => {
            zone.addEventListener('dragover', handleDragOver);
            zone.addEventListener('drop', handleDrop);
        });
        if (allPortfoliosZone && !allPortfoliosZone._dndBound) {
            allPortfoliosZone.addEventListener('dragover', handleDragOver);
            allPortfoliosZone.addEventListener('drop', handleDrop);
            allPortfoliosZone._dndBound = true;
        }
        if (portfolioColumnWrapper && !portfolioColumnWrapper._dndBound) {
            portfolioColumnWrapper.addEventListener('dragover', function(e){
                // Routeer dragover naar de overzicht dropzone als je boven de kolom hangt
                e.preventDefault();
            });
            portfolioColumnWrapper.addEventListener('drop', function(e){
                // Routeer drops op de kolom naar de overzicht dropzone
                if (!allPortfoliosZone) return;
                e.preventDefault();
                e.stopPropagation();
                // Simuleer drop op allPortfoliosZone door de handler met aangepaste currentTarget aan te roepen
                const evt = new Event('drop', { bubbles: true, cancelable: true });
                // Kopieer dataTransfer is niet mogelijk tussen events; roep daarom direct handleDrop met de originele event maar forceer dropzone-detectie
                let payloadText = e.dataTransfer.getData('application/json');
                if (!payloadText) return;
                const payload = JSON.parse(payloadText);
                const fakeEvent = {
                    preventDefault: ()=>{},
                    stopPropagation: ()=>{},
                    dataTransfer: { getData: ()=>payloadText },
                    currentTarget: allPortfoliosZone,
                    target: allPortfoliosZone
                };
                handleDrop(fakeEvent);
            });
            portfolioColumnWrapper._dndBound = true;
        }

        function handleDragStart(e) {
            const el = e.currentTarget;
            el.classList.add('opacity-50');
            const dataset = el.dataset;
            const payload = dataset.studentId ? JSON.stringify({ type: 'student', id: dataset.studentId }) : JSON.stringify({ type: 'portfolio', id: dataset.portfolioId });
            e.dataTransfer.setData('application/json', payload);
            // Globaal bijhouden welk type gesleept wordt
            window.__currentDragType = dataset.studentId ? 'student' : 'portfolio';
            console.log('Drag start:', payload);
        }

        function handleDragEnd(e) {
            const el = e.currentTarget;
            el.classList.remove('opacity-50');
            window.__currentDragType = undefined;
            
            // Verwijder visuele feedback van alle drop zones
            document.querySelectorAll('.classroom-dropzone').forEach(zone => {
                zone.classList.remove('bg-blue-100', 'dark:bg-blue-900');
            });
        }

        function handleDragOver(e) {
            // Sta drop toe op alle dropzones; validatie gebeurt in handleDrop
            e.preventDefault();
            e.stopPropagation();
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            const payloadText = e.dataTransfer.getData('application/json');
            if (!payloadText) return;
            const payload = JSON.parse(payloadText);
            let dropzone = e.currentTarget.classList && e.currentTarget.classList.contains('kamer-dropzone') ? e.currentTarget : e.target.closest('.kamer-dropzone');
            if (!dropzone && allPortfoliosZone && allPortfoliosZone.contains(e.target)) {
                dropzone = allPortfoliosZone;
            }
            if (!dropzone) return;
            // Validatie: blokkeer ongeldige combinaties (portfolio mag niet naar all-students; student niet naar all-portfolios)
            if ((payload.type === 'portfolio' && dropzone.id === 'all-students') || (payload.type === 'student' && dropzone.id === 'all-portfolios')) {
                return;
            }
            const newLocation = dropzone.dataset.location;
            const isStudent = payload.type === 'student';
            const selector = isStudent ? `[data-student-id="${payload.id}"]` : `[data-portfolio-id="${payload.id}"]`;
            const card = document.querySelector(selector);
            const oldLocation = card.dataset.location;

            // Update de locatie via AJAX
            const url = isStudent ? `/posts/${payload.id}/location` : `/portfolios/${payload.id}/location`;
            fetch(url, {
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
                    let oldDropzone = null;
                    if (oldLocation === 'all') {
                        oldDropzone = document.querySelector(isStudent ? '#all-students' : '#all-portfolios');
                    } else {
                        oldDropzone = document.querySelector(`#kamer-${oldLocation}`);
                    }
                    if (oldDropzone && oldDropzone.contains(card)) {
                        oldDropzone.removeChild(card);
                    }

                    // Voeg de kaart toe aan de nieuwe locatie
                    const newDropzone = newLocation === 'all'
                        ? document.querySelector(isStudent ? '#all-students' : '#all-portfolios') || document.getElementById('all-portfolios')
                        : document.querySelector(`#kamer-${newLocation}`);
                    if (newDropzone) {
                        card.dataset.location = newLocation;
                        newDropzone.appendChild(card);
                    }

                    // Geen automatische herlaad; DOM is al bijgewerkt
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Er is een fout opgetreden bij het verplaatsen.');
            });
        }

        function handleClassroomDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Verwijder visuele feedback
            e.currentTarget.classList.remove('bg-blue-100', 'dark:bg-blue-900');
            
            const payloadText = e.dataTransfer.getData('application/json');
            if (!payloadText) {
                console.log('No payload data found');
                return;
            }
            
            const payload = JSON.parse(payloadText);
            console.log('Drop payload:', payload);
            
            // Alleen studenten kunnen naar klassen gesleept worden
            if (payload.type !== 'student') {
                console.log('Not a student, ignoring drop');
                return;
            }
            
            const classroomId = e.currentTarget.dataset.classroomId;
            const studentId = payload.id;
            
            console.log('Dropping student', studentId, 'to classroom', classroomId);
            
            // Eerst proberen met de bestaande route, als die niet werkt, gebruik dan een alternatieve aanpak
            fetch(`/posts/${studentId}/classroom`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ classroom_id: classroomId })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Server error: ' + response.status);
                }
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Verwijder de student van de oude locatie
                    const studentCard = document.querySelector(`[data-student-id="${studentId}"]`);
                    if (studentCard) {
                        studentCard.remove();
                        
                        // Voeg de student toe aan de klas
                        const classroomStudentsContainer = document.getElementById(`classroom-students-${classroomId}`);
                        if (classroomStudentsContainer) {
                            // Update de student card styling voor in de klas
                            studentCard.className = 'student-card bg-gray-100 dark:bg-gray-700 rounded p-2 text-sm cursor-move';
                            studentCard.dataset.classroomId = classroomId;
                            studentCard.innerHTML = `<span class="text-gray-800 dark:text-gray-200">${data.student_name || 'Student'}</span>`;
                            
                            classroomStudentsContainer.appendChild(studentCard);
                            
                            // Verberg de "Geen studenten" tekst
                            const noStudentsText = classroomStudentsContainer.querySelector('p');
                            if (noStudentsText && noStudentsText.textContent.includes('Geen studenten')) {
                                noStudentsText.style.display = 'none';
                            }
                            
                            // Re-setup drag events voor de nieuwe student card
                            studentCard.setAttribute('draggable', true);
                            studentCard.addEventListener('dragstart', handleDragStart);
                            studentCard.addEventListener('dragend', handleDragEnd);
                        }
                    }
                } else {
                    alert('Student toevoegen aan klas mislukt: ' + (data.message || 'Onbekende fout'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Er is een fout opgetreden bij het toevoegen aan de klas: ' + error.message);
            });
        }
        // Portfolios gebruiken dezelfde HTML5 drag/drop flow als studenten (geen SortableJS)
    });
</script>
@endsection
