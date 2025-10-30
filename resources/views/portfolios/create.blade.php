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
                    <label for="description" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Beschrijving (optioneel):</label>
                    <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Korte beschrijving">{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="link" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Link (optioneel):</label>
                    <input type="url" name="link" id="link" value="{{ old('link') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="https://...">
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('posts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Terug naar Overzicht</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Portfolio Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection


