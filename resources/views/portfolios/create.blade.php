@extends('layouts.app')

@section('title', 'Nieuw Portfolio Toevoegen')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Nieuw Portfolio Toevoegen</h1>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('portfolios.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Titel:</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Voer de titel in" required>
                </div>

                <div class="mb-4">
                    <label for="subtitle" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tweede titel (optioneel):</label>
                    <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Voer een tweede titel in">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Beschrijving (optioneel):</label>
                    <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Korte beschrijving">{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="link" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Link (optioneel):</label>
                    <input type="url" name="link" id="link" value="{{ old('link') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="https://...">
                </div>

                @php
                    $portfolioPalette = [
                        ['bg' => '#eb6931', 'border' => '#f5520c', 'text' => '#78350f', 'bg_dark' => '#92400e', 'border_dark' => '#d97706', 'text_dark' => '#fef3c7'],
                        ['bg' => '#e39b44', 'border' => '#e67f02', 'text' => '#064e3b', 'bg_dark' => '#065f46', 'border_dark' => '#10b981', 'text_dark' => '#dcfce7'],
                        ['bg' => '#ebe660', 'border' => '#d9d202', 'text' => '#1e3a8a', 'bg_dark' => '#1d4ed8', 'border_dark' => '#3b82f6', 'text_dark' => '#dbeafe'],
                        ['bg' => '#3ce69f', 'border' => '#02b86c', 'text' => '#4c1d95', 'bg_dark' => '#5b21b6', 'border_dark' => '#8b5cf6', 'text_dark' => '#ede9fe'],
                        ['bg' => '#4bdbcf', 'border' => '#06baab', 'text' => '#831843', 'bg_dark' => '#9f1239', 'border_dark' => '#f43f5e', 'text_dark' => '#ffe4e6'],
                        ['bg' => '#6591f0', 'border' => '#0a43bf', 'text' => '#312e81', 'bg_dark' => '#4338ca', 'border_dark' => '#6366f1', 'text_dark' => '#e0e7ff'],
                        ['bg' => '#a867e6', 'border' => '#4b088a', 'text' => '#581c87', 'bg_dark' => '#6b21a8', 'border_dark' => '#c084fc', 'text_dark' => '#f3e8ff'],
                        ['bg' => '#ed58cf', 'border' => '#a80387', 'text' => '#7c2d12', 'bg_dark' => '#c2410c', 'border_dark' => '#ea580c', 'text_dark' => '#ffedd5'],
                    ];
                @endphp

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Kleur:</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($portfolioPalette as $index => $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="color_index" value="{{ $index }}" {{ old('color_index', '0') == $index ? 'checked' : '' }} class="hidden color-radio">
                                <div class="w-12 h-12 rounded-lg border-2 transition-all {{ old('color_index', '0') == $index ? 'border-gray-800 dark:border-gray-200 ring-2 ring-blue-500' : 'border-gray-300 dark:border-gray-600' }}" style="background-color: {{ $color['bg'] }}; border-color: {{ old('color_index', '0') == $index ? '#1f2937' : $color['border'] }};" title="Kleur {{ $index + 1 }}"></div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('posts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Terug naar Overzicht</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Portfolio Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <script>
        document.querySelectorAll('.color-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.color-radio').forEach(r => {
                    const div = r.nextElementSibling;
                    div.classList.remove('border-gray-800', 'dark:border-gray-200', 'ring-2', 'ring-blue-500');
                    div.classList.add('border-gray-300', 'dark:border-gray-600');
                });
                const selectedDiv = this.nextElementSibling;
                selectedDiv.classList.remove('border-gray-300', 'dark:border-gray-600');
                selectedDiv.classList.add('border-gray-800', 'dark:border-gray-200', 'ring-2', 'ring-blue-500');
            });
        });
    </script>
@endsection


