@extends('trello::layouts.app')

@section('title', 'My Boards')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Boards</h1>
        <a href="{{ route('boards.create') }}" 
           class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            + Create New Board
        </a>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <form action="{{ route('boards.index') }}" method="GET">
            <input type="text" 
                   name="search" 
                   value="{{ $search ?? '' }}"
                   placeholder="Search boards..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </form>
    </div>

    <!-- Boards Grid -->
    @if($boards->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($boards as $board)
                @include('trello::components.board-card', ['board' => $board])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $boards->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">No boards found</h3>
            <p class="mt-2 text-gray-500">Get started by creating your first board.</p>
            <a href="{{ route('boards.create') }}" 
               class="mt-4 inline-block bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700">
                Create Board
            </a>
        </div>
    @endif
</div>
@endsection
