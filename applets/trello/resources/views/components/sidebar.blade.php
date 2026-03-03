<aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-4">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">My Boards</h2>
        <a href="{{ route('boards.create') }}" 
           class="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            + New Board
        </a>
    </div>

    <div class="space-y-2">
        @php
            $userBoards = Auth::user()->boards()->latest()->take(10)->get();
        @endphp
        
        @forelse($userBoards as $board)
            <a href="{{ route('boards.show', $board) }}" 
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                {{ $board->title }}
            </a>
        @empty
            <p class="text-sm text-gray-500 px-4">No boards yet</p>
        @endforelse

        <a href="{{ route('boards.index') }}" 
           class="block px-4 py-2 text-sm text-blue-600 hover:bg-gray-100 rounded mt-4">
            View All Boards →
        </a>
    </div>
</aside>
