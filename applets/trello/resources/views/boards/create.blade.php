@extends('trello::layouts.app')

@section('title', 'Create Board')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Board</h1>

    <form action="{{ route('boards.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Board Title *
            </label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="{{ old('title') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                   required>
            @error('title')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Visibility -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Visibility *
            </label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" 
                           name="visibility" 
                           value="private" 
                           {{ old('visibility', 'private') === 'private' ? 'checked' : '' }}
                           class="mr-2">
                    <span class="text-sm text-gray-700">Private</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" 
                           name="visibility" 
                           value="public" 
                           {{ old('visibility') === 'public' ? 'checked' : '' }}
                           class="mr-2">
                    <span class="text-sm text-gray-700">Public</span>
                </label>
            </div>
            @error('visibility')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex space-x-4">
            <button type="submit" 
                    class="bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700">
                Create Board
            </button>
            <a href="{{ route('boards.index') }}" 
               class="bg-gray-200 text-gray-700 py-2 px-6 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
