<div class="bg-gray-100 rounded-lg p-3 w-72 flex-shrink-0 shadow-sm self-start" data-list-id="{{ $list->id }}" style="background-color: #ebecf0;">
    <div class="flex justify-between items-center mb-3">
        <div class="flex items-center gap-2">
            <h3 class="font-semibold text-sm text-gray-700">{{ $list->title }}</h3>
            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $list->cards->count() }}</span>
        </div>
        <div class="flex space-x-1">
            <button class="text-gray-500 hover:bg-gray-200 rounded p-1 transition-all duration-200" onclick="editList({{ $list->id }})" title="Edit list">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </button>
            <form action="{{ route('trello.lists.destroy', $list) }}" method="POST" class="inline"
                  onsubmit="return confirm('Delete this list and all its cards?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-gray-500 hover:bg-red-100 hover:text-red-600 rounded p-1 transition-all duration-200" title="Delete list">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    
    <div class="space-y-2 mb-2 overflow-y-auto drop-zone"
         ondragover="handleDragOver(event)"
         ondragenter="handleDragEnter(event)"
         ondragleave="handleDragLeave(event)"
         ondrop="handleDrop(event, {{ $list->id }})"
         style="max-height: 70vh; min-height: 50px;">
        @forelse($list->cards->sortBy('position') as $card)
            @include('trello::components.card-item', ['card' => $card])
        @empty
            <div class="text-center py-3 text-gray-400 text-xs">
                Drop cards here
            </div>
        @endforelse
    </div>
    
    <!-- Add Card Button -->
    <button onclick="showInlineCardForm({{ $list->id }})" 
            id="add-card-btn-{{ $list->id }}"
            class="w-full text-left text-sm text-gray-600 hover:bg-gray-200 hover:bg-opacity-50 p-2 rounded transition-all duration-200 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add a card
    </button>
    
    <!-- Inline Card Form -->
    <div id="inline-card-form-{{ $list->id }}" class="hidden">
        <form action="{{ route('trello.cards.store') }}" method="POST" onsubmit="return handleInlineCardSubmit(event, {{ $list->id }})">
            @csrf
            <input type="hidden" name="list_id" value="{{ $list->id }}">
            <textarea 
                name="title" 
                id="card-title-{{ $list->id }}"
                class="w-full px-3 py-2 border-2 border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                rows="3"
                placeholder="Enter a title or paste a link"
                required></textarea>
            <div class="flex items-center gap-2 mt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-sm font-medium transition-colors">
                    Add card
                </button>
                <button type="button" onclick="hideInlineCardForm({{ $list->id }})" class="text-gray-600 hover:text-gray-800 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
