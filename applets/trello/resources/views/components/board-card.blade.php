<div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
    <div class="flex justify-between items-start mb-2">
        <h3 class="text-lg font-semibold text-gray-900">{{ $board->title }}</h3>
        <span class="px-2 py-1 text-xs rounded {{ $board->visibility === 'public' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
            {{ ucfirst($board->visibility) }}
        </span>
    </div>
    
    @if($board->description)
        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($board->description, 100) }}</p>
    @endif
    
    <div class="flex justify-between items-center text-sm text-gray-500">
        <span>{{ $board->lists_count ?? $board->lists->count() }} lists</span>
        <span>{{ $board->created_at->diffForHumans() }}</span>
    </div>
    
    <div class="mt-4 flex space-x-2">
        <a href="{{ route('boards.show', $board) }}" 
           class="flex-1 text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            Open
        </a>
        <a href="{{ route('boards.edit', $board) }}" 
           class="flex-1 text-center bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300">
            Edit
        </a>
    </div>
</div>
