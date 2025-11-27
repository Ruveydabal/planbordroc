@extends('layouts.app')

@section('title', 'Portfolio Bewerken')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Portfolio Bewerken</h1>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc ml-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($portfolio->locations && $portfolio->locations->count() > 0)
            <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/40 rounded-lg text-blue-900 dark:text-blue-100">
                <strong>Locatie:</strong>
                <span>
                    {{ $portfolio->locations->pluck('display_name')->filter()->implode(', ') ?: $portfolio->locations->pluck('name')->implode(', ') }}
                </span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 space-y-6">
            <form method="POST" action="{{ route('portfolios.update', $portfolio->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Titel</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $portfolio->title) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Voer de titel in"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Beschrijving (optioneel)</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Korte beschrijving"
                    >{{ old('description', $portfolio->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="link" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Link (optioneel)</label>
                    <input
                        type="url"
                        name="link"
                        id="link"
                        value="{{ old('link', $portfolio->link) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="https://..."
                    >
                </div>

                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mt-6">
                    <a
                        href="{{ route('posts.index') }}"
                        class="text-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    >
                        Terug naar overzicht
                    </a>
                    <button
                        type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    >
                        Portfolio bijwerken
                    </button>
                </div>
            </form>

            @auth
            <form
                action="{{ route('portfolios.destroy', $portfolio->id) }}"
                method="POST"
                onsubmit="return confirm('Weet je zeker dat je dit portfolio wilt verwijderen? Dit kan niet ongedaan worden gemaakt.');"
                class="flex justify-end"
            >
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                    Portfolio verwijderen
                </button>
            </form>
            @endauth
        </div>
    </div>
</div>
@endsection


