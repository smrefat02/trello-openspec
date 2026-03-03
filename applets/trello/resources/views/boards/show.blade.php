@extends('trello::layouts.app')

@section('title', $board->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Board Header -->
    <div class="sticky top-0 z-10 bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $board->title }}</h1>
                @if($board->description)
                    <p class="text-gray-600 text-sm mt-1">{{ $board->description }}</p>
                @endif
            </div>
            <div class="flex space-x-2">
                <button onclick="showAddListModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition-all duration-200">
                    + Add List
                </button>
                <a href="{{ route('boards.edit', $board) }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded transition-all duration-200">
                    Edit Board
                </a>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex items-start space-x-3 overflow-x-auto px-6 py-6 pb-8">
        @forelse($board->lists->sortBy('position') as $list)
            @include('trello::components.list-column', ['list' => $list])
        @empty
            <div class="text-center py-12 w-full bg-white rounded-lg border-2 border-dashed border-gray-300">
                <p class="text-gray-500 text-lg">📋 No lists yet. Click "Add List" to get started.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Add List Modal -->
@component('trello::components.modal', ['modalId' => 'addListModal', 'title' => 'Add New List'])
    <form action="{{ route('trello.lists.store') }}" method="POST">
        @csrf
        <input type="hidden" name="board_id" value="{{ $board->id }}">
        
        <div class="mb-4">
            <label for="list_title" class="block text-sm font-medium text-gray-700 mb-2">List Title</label>
            <input type="text" 
                   id="list_title" 
                   name="title" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                   required>
        </div>
        
        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Add List
            </button>
            <button type="button" 
                    onclick="closeModal('addListModal')" 
                    class="bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300">
                Cancel
            </button>
        </div>
    </form>
@endcomponent

<!-- Edit List Modal -->
@component('trello::components.modal', ['modalId' => 'editListModal', 'title' => 'Edit List'])
    <form action="" method="POST" id="editListForm">
        @csrf
        @method('PUT')
        <input type="hidden" id="edit_list_id">
        
        <div class="mb-4">
            <label for="edit_list_title" class="block text-sm font-medium text-gray-700 mb-2">List Title</label>
            <input type="text" 
                   id="edit_list_title" 
                   name="title" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                   required>
        </div>
        
        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Update List
            </button>
            <button type="button" 
                    onclick="closeModal('editListModal')" 
                    class="bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300">
                Cancel
            </button>
        </div>
    </form>
@endcomponent

<!-- Add Card Modal -->
@component('trello::components.modal', ['modalId' => 'addCardModal', 'title' => 'Add New Card'])
    <form action="{{ route('trello.cards.store') }}" method="POST" id="addCardForm">
        @csrf
        <input type="hidden" name="list_id" id="card_list_id">
        
        <div class="mb-4">
            <label for="card_title" class="block text-sm font-medium text-gray-700 mb-2">Card Title *</label>
            <input type="text" 
                   id="card_title" 
                   name="title" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                   required>
        </div>
        
        <div class="mb-4">
            <label for="card_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea id="card_description" 
                      name="description" 
                      rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
        </div>
        
        <div class="mb-4">
            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
            <input type="date" 
                   id="due_date" 
                   name="due_date" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="todo">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </div>
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select id="priority" name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
        </div>
        
        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Add Card
            </button>
            <button type="button" 
                    onclick="closeModal('addCardModal')" 
                    class="bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300">
                Cancel
            </button>
        </div>
    </form>
@endcomponent

<!-- Card Details Modal -->
<div id="cardDetailsModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-y-auto">
    <div class="bg-white shadow-2xl w-full max-w-3xl mx-4 overflow-hidden" style="border-radius: 20px;" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="px-5 pt-4 pb-2 relative bg-white border-b border-gray-200">
            <button onclick="closeModal('cardDetailsModal')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full p-1 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <div class="flex items-start gap-2 mb-3">
                <svg class="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div class="flex-1">
                    <h2 id="modal_card_title" class="text-lg font-semibold text-gray-800 mb-0.5"></h2>
                    <p class="text-xs text-gray-500">in list <span id="card_list_name" class="font-medium"></span></p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex gap-1.5 ml-7 mb-2">
                <button onclick="showAddLabelForm()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700 font-medium transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Labels
                </button>
                <button id="checklistBtn" onclick="showAddChecklistForm()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700 font-medium transition-colors flex items-center gap-1 relative">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Checklist
                </button>
            </div>
            
            <!-- Add Checklist Popover -->
            <div id="addChecklistPopover" class="hidden absolute bg-white rounded-lg shadow-xl border border-gray-200 p-3 z-50" style="width: 304px; margin-left: 28px; margin-top: -8px;">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Add checklist</h3>
                    <button onclick="hideAddChecklistForm()" class="text-gray-500 hover:text-gray-700 p-1 hover:bg-gray-100 rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form onsubmit="return handleChecklistSubmit(event)">
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Title</label>
                        <input type="text" 
                               id="checklistTitleInput" 
                               value="Checklist"
                               class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Copy items from...</label>
                        <select class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                            <option value="">(none)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-1.5 px-3 rounded text-sm font-medium transition-colors">
                        Add
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal Body: Two Column Layout -->
        <div class="flex bg-white">
            <!-- Main Content (Left) -->
            <div class="flex-1 px-5 py-3 space-y-4 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                <!-- Labels -->
                <div id="labels-container" class="hidden">
                    <div class="mb-0.5">
                        <h3 class="text-xs font-semibold text-gray-600 mb-1.5">Labels</h3>
                    </div>
                    <div id="card-labels" class="flex flex-wrap gap-1"></div>
                </div>

                <!-- Dates -->
                <div id="dates-container" class="hidden">
                    <div class="mb-0.5">
                        <h3 class="text-xs font-semibold text-gray-600 mb-1.5">Dates</h3>
                    </div>
                    <input type="date" id="edit_due_date" class="px-2.5 py-1.5 border border-gray-300 rounded-md text-sm bg-white focus:outline-none focus:border-blue-400">
                </div>

                <!-- Description -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-800">Description</h3>
                    </div>
                    <div class="ml-6">
                        <textarea id="edit_card_description" 
                                  class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-blue-400 min-h-[60px] hover:bg-white transition-colors"
                                  placeholder="Add a more detailed description..."></textarea>
                    </div>
                </div>

                <!-- Checklists -->
                <div id="checklists-section"></div>

                <!-- Card Details Section -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-800">Details</h3>
                    </div>
                    <div class="ml-6 space-y-2">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Status</label>
                            <select id="edit_status" class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:border-blue-400">
                                <option value="todo">To Do</option>
                                <option value="in_progress">In Process</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Priority</label>
                            <select id="edit_priority" class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:border-blue-400">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="ml-6 flex gap-2 pt-1">
                    <button onclick="saveCardChanges()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm font-medium transition-colors">
                        Save Changes
                    </button>
                    <button onclick="deleteCard()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-lg text-sm font-medium transition-colors">
                        Delete Card
                    </button>
                </div>
            </div>

            <!-- Right Panel: Activity -->
            <div class="w-80 flex-shrink-0 border-l border-gray-200 px-4 py-3 bg-gray-50 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-800">Activity</h3>
                    <button class="text-xs text-gray-500 hover:text-gray-700">Show details</button>
                </div>
                
                <div class="mb-3">
                    <textarea id="new_comment" 
                              class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-400 transition-colors"
                              rows="2"
                              placeholder="Write a comment..."></textarea>
                    <button onclick="addComment()" class="mt-2 bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-700 transition-colors">
                        Save
                    </button>
                </div>
                
                <div id="card-comments" class="space-y-2.5"></div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden forms -->
<form id="editCardForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
</form>

<form id="deleteCardForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
const cards = @json($board->lists->flatMap(fn($list) => $list->cards)->keyBy('id'));
const lists = @json($board->lists->keyBy('id'));
let draggedCardId = null;
let currentCardId = null;

const labelColors = {
    'purple': 'bg-purple-500 text-white',
    'green': 'bg-green-500 text-white',
    'yellow': 'bg-yellow-400 text-gray-900',
    'orange': 'bg-orange-500 text-white',
    'red': 'bg-red-500 text-white',
    'blue': 'bg-blue-500 text-white',
    'pink': 'bg-pink-500 text-white',
    'gray': 'bg-gray-500 text-white'
};

function showAddListModal() {
    openModal('addListModal');
}

function showAddCardModal(listId) {
    document.getElementById('card_list_id').value = listId;
    openModal('addCardModal');
}

function showInlineCardForm(listId) {
    // Hide button, show form
    document.getElementById('add-card-btn-' + listId).classList.add('hidden');
    document.getElementById('inline-card-form-' + listId).classList.remove('hidden');
    // Focus on textarea
    document.getElementById('card-title-' + listId).focus();
}

function hideInlineCardForm(listId) {
    // Show button, hide form
    document.getElementById('add-card-btn-' + listId).classList.remove('hidden');
    document.getElementById('inline-card-form-' + listId).classList.add('hidden');
    // Clear textarea
    document.getElementById('card-title-' + listId).value = '';
}

function handleInlineCardSubmit(event, listId) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Reload the page to show new card
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add card');
    });
    
    return false;
}

function editList(listId) {
    const list = lists[listId];
    if (!list) return;
    
    document.getElementById('edit_list_id').value = list.id;
    document.getElementById('edit_list_title').value = list.title;
    document.getElementById('editListForm').action = `/trello/lists/${list.id}`;
    
    openModal('editListModal');
}

function reloadCardData(cardId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/trello/cards/${cardId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.card) {
            // Update the cards object with fresh data
            cards[cardId] = data.card;
            // Re-render the card details in the modal
            showCardDetails(cardId);
            // Update the card checklist display in the board view
            updateCardChecklistDisplay(cardId, data.card);
        }
    })
    .catch(error => console.error('Error reloading card:', error));
}

function updateCardChecklistDisplay(cardId, card) {
    // Find the card element in the board view
    const cardElement = document.querySelector(`[data-card-id="${cardId}"]`);
    if (!cardElement) return;
    
    // Calculate checklist totals
    let totalChecklistItems = 0;
    let completedChecklistItems = 0;
    
    if (card.checklists) {
        card.checklists.forEach(checklist => {
            if (checklist.items) {
                totalChecklistItems += checklist.items.length;
                completedChecklistItems += checklist.items.filter(item => item.is_completed).length;
            }
        });
    }
    
    // Find existing checklist display element or create placeholder
    let checklistDisplay = cardElement.querySelector('.checklist-counter');
    
    if (totalChecklistItems > 0) {
        // Determine if all items are completed
        const allCompleted = completedChecklistItems === totalChecklistItems;
        const bgClass = allCompleted ? 'text-green-600 bg-green-50' : 'text-gray-600 bg-gray-50';
        
        const checklistHTML = `
            <div class="flex items-center gap-1 text-xs mt-2 ${bgClass} px-1.5 py-0.5 rounded w-fit checklist-counter">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span class="font-medium">${completedChecklistItems}/${totalChecklistItems}</span>
            </div>
        `;
        
        if (checklistDisplay) {
            // Replace existing element
            checklistDisplay.outerHTML = checklistHTML;
        } else {
            // Add new element to the card
            cardElement.insertAdjacentHTML('beforeend', checklistHTML);
        }
    } else if (checklistDisplay) {
        // Remove checklist display if no items exist
        checklistDisplay.remove();
    }
}

function showCardDetails(cardId) {
    const card = cards[cardId];
    if (!card) return;
    
    currentCardId = cardId;
    
    // Populate basic fields
    document.getElementById('modal_card_title').textContent = card.title;
    document.getElementById('edit_card_description').value = card.description || '';
    document.getElementById('edit_due_date').value = card.due_date || '';
    document.getElementById('edit_status').value = card.status;
    document.getElementById('edit_priority').value = card.priority;
    document.getElementById('card_list_name').textContent = card.list ? (lists[card.list.id]?.title || '') : '';
    
    // Set form actions
    document.getElementById('editCardForm').action = `/trello/cards/${card.id}`;
    document.getElementById('deleteCardForm').action = `/trello/cards/${card.id}`;
    
    // Show/hide labels section
    const labelsSection = document.getElementById('labels-container');
    if (card.labels && card.labels.length > 0) {
        labelsSection.classList.remove('hidden');
        renderLabels(card.labels);
    } else {
        labelsSection.classList.add('hidden');
    }
    
    // Show/hide dates section
    const datesSection = document.getElementById('dates-container');
    if (card.due_date) {
        datesSection.classList.remove('hidden');
    } else {
        datesSection.classList.add('hidden');
    }
    
    // Load checklists
    renderChecklists(card.checklists || []);
    
    // Load comments
    renderComments(card.comments || []);
    
    openModal('cardDetailsModal');
}

function renderLabels(labels) {
    const container = document.getElementById('card-labels');
    container.innerHTML = labels.map(label => `
        <span class="${labelColors[label.color] || 'bg-gray-500 text-white'} px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center gap-1.5">
            ${label.name}
            <button onclick="deleteLabel(${label.id})" class="hover:bg-black hover:bg-opacity-20 rounded-full p-0.5 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </span>
    `).join('');
}

function renderChecklists(checklists) {
    const container = document.getElementById('checklists-section');
    container.innerHTML = checklists.map(checklist => {
        const items = checklist.items || [];
        const completed = items.filter(i => i.is_completed).length;
        const total = items.length;
        const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
        
        return `
            <div class="mb-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-800 flex-1">${checklist.title}</h3>
                    <button onclick="deleteChecklist(${checklist.id})" class="text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-100 px-2 py-0.5 rounded-md transition-colors">
                        Delete
                    </button>
                </div>
                <div class="ml-6">
                    ${total > 0 ? `
                    <div class="mb-2">
                        <div class="flex items-center gap-2 text-xs text-gray-600 mb-1">
                            <span>${percentage}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="${percentage === 100 ? 'bg-green-500' : 'bg-blue-500'} h-1.5 rounded-full transition-all duration-300" style="width: ${percentage}%"></div>
                        </div>
                    </div>
                    ` : ''}
                    <div class="space-y-1">
                        ${items.map(item => `
                            <div class="flex items-center gap-2 group hover:bg-gray-50 rounded-lg px-2 py-1 -mx-2 transition-colors">
                                <input type="checkbox" 
                                       ${item.is_completed ? 'checked' : ''} 
                                       onchange="toggleChecklistItem(${item.id})"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-1 w-3.5 h-3.5 cursor-pointer flex-shrink-0">
                                <span class="${item.is_completed ? 'line-through text-gray-500' : 'text-gray-700'} text-sm flex-1">${item.title}</span>
                                <button onclick="deleteChecklistItem(${item.id})" class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-600 p-0.5 transition-opacity">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        `).join('')}
                        <button id="add-item-btn-${checklist.id}" onclick="showAddChecklistItem(${checklist.id})" class="text-xs text-gray-600 hover:bg-gray-100 px-2 py-1 rounded-md inline-flex items-center gap-1 mt-0.5 transition-colors">
                            + Add an item
                        </button>
                        <div id="add-item-form-${checklist.id}" class="hidden mt-2">
                            <form onsubmit="return handleChecklistItemSubmit(event, ${checklist.id})">
                                <input type="text" 
                                       id="item-title-${checklist.id}"
                                       placeholder="Add an item"
                                       class="w-full px-3 py-2 border-2 border-blue-500 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                                       required>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-sm font-medium transition-colors">
                                        Add
                                    </button>
                                    <button type="button" onclick="hideAddChecklistItem(${checklist.id})" class="text-gray-600 hover:text-gray-800 px-3 py-1.5 text-sm">
                                        Cancel
                                    </button>
                                    <div class="flex-1"></div>
                                    <button type="button" class="text-gray-600 hover:bg-gray-100 px-2 py-1 rounded text-xs inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Assign
                                    </button>
                                    <button type="button" class="text-gray-600 hover:bg-gray-100 px-2 py-1 rounded text-xs inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Due date
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function renderComments(comments) {
    const container = document.getElementById('card-comments');
    if (comments.length === 0) {
        container.innerHTML = '<p class="text-xs text-gray-500">No activity yet.</p>';
        return;
    }
    
    container.innerHTML = comments.map(comment => {
        const date = new Date(comment.created_at);
        const timeAgo = getTimeAgo(date);
        
        return `
        <div class="flex gap-2 items-start">
            <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-xs font-semibold">
                ${comment.user?.name?.charAt(0).toUpperCase() || 'U'}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-baseline gap-1 mb-0.5">
                    <span class="font-semibold text-xs text-gray-800">${comment.user?.name || 'User'}</span>
                    <span class="text-xs text-gray-500">${timeAgo}</span>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs text-gray-700">
                    ${comment.comment}
                </div>
                ${comment.user_id === {{ auth()->id() }} ? `
                <button onclick="deleteComment(${comment.id})" class="text-xs text-gray-500 hover:text-red-600 hover:underline mt-0.5 transition-colors">
                    Delete
                </button>
                ` : ''}
            </div>
        </div>
    `}).join('');
}

function getTimeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);
    
    if (seconds < 60) return 'just now';
    if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
    if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
    if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
    
    return date.toLocaleDateString();
}

function saveCardChanges() {
    if (!currentCardId) return;
    
    const card = cards[currentCardId];
    const form = document.getElementById('editCardForm');
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('title', card.title); // Keep original title
    formData.append('description', document.getElementById('edit_card_description').value);
    formData.append('due_date', document.getElementById('edit_due_date').value);
    formData.append('status', document.getElementById('edit_status').value);
    formData.append('priority', document.getElementById('edit_priority').value);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Reload the board to reflect changes in card list view
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save changes');
    });
}

function deleteCard() {
    if (confirm('Are you sure you want to delete this card?')) {
        document.getElementById('deleteCardForm').submit();
    }
}

function showAddLabelForm() {
    const name = prompt('Enter label name:');
    const color = prompt('Enter color (purple/green/yellow/orange/red/blue/pink/gray):');
    
    if (name && color) {
        addLabel(name, color);
    }
}

function addLabel(name, color) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/trello/labels', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            card_id: currentCardId,
            name: name,
            color: color
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            reloadCardData(currentCardId);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteLabel(labelId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/trello/labels/${labelId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            reloadCardData(currentCardId);
        }
    })
    .catch(error => console.error('Error:', error));
}

function showAddChecklistForm() {
    const popover = document.getElementById('addChecklistPopover');
    popover.classList.remove('hidden');
    document.getElementById('checklistTitleInput').focus();
    document.getElementById('checklistTitleInput').select();
}

function hideAddChecklistForm() {
    const popover = document.getElementById('addChecklistPopover');
    popover.classList.add('hidden');
}

function handleChecklistSubmit(event) {
    event.preventDefault();
    const title = document.getElementById('checklistTitleInput').value.trim();
    if (title) {
        addChecklist(title);
        hideAddChecklistForm();
    }
    return false;
}

function addChecklist(title) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/trello/checklists', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            card_id: currentCardId,
            title: title
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload card data to update checklists
            reloadCardData(currentCardId);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteChecklist(checklistId) {
    if (!confirm('Delete this checklist and all its items?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/trello/checklists/${checklistId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            reloadCardData(currentCardId);
        }
    })
    .catch(error => console.error('Error:', error));
}

function showAddChecklistItem(checklistId) {
    document.getElementById('add-item-btn-' + checklistId).classList.add('hidden');
    document.getElementById('add-item-form-' + checklistId).classList.remove('hidden');
    document.getElementById('item-title-' + checklistId).focus();
}

function hideAddChecklistItem(checklistId) {
    document.getElementById('add-item-btn-' + checklistId).classList.remove('hidden');
    document.getElementById('add-item-form-' + checklistId).classList.add('hidden');
    document.getElementById('item-title-' + checklistId).value = '';
}

function handleChecklistItemSubmit(event, checklistId) {
    event.preventDefault();
    const title = document.getElementById('item-title-' + checklistId).value.trim();
    if (title) {
        addChecklistItem(checklistId, title);
    }
    return false;
}

function addChecklistItem(checklistId, title) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/trello/checklist-items', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            checklist_id: checklistId,
            title: title
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide the form
            hideAddChecklistItem(checklistId);
            // Reload card data to update checklists
            reloadCardData(currentCardId);
        }
    })
    .catch(error => console.error('Error:', error));
}

function toggleChecklistItem(itemId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/trello/checklist-items/${itemId}/toggle`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            reloadCardData(currentCardId);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteChecklistItem(itemId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/trello/checklist-items/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            reloadCardData(currentCardId);
        }
    })
    .catch(error => console.error('Error:', error));
}

function addComment() {
    const comment = document.getElementById('new_comment').value.trim();
    
    if (!comment) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/trello/comments', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            card_id: currentCardId,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('new_comment').value = '';
            reloadCardData(currentCardId);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Drag and Drop Functions
function handleDragStart(event, cardId) {
    draggedCardId = cardId;
    event.target.style.opacity = '0.4';
    event.target.style.transform = 'rotate(2deg)';
    event.stopPropagation();
}

function handleDragEnd(event) {
    event.target.style.opacity = '1';
    event.target.style.transform = 'rotate(0deg)';
}

function handleDragOver(event) {
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
}

function handleDragEnter(event) {
    const dropZone = event.currentTarget;
    if (dropZone.classList.contains('drop-zone')) {
        dropZone.classList.add('bg-blue-100', 'ring-2', 'ring-blue-400', 'ring-inset');
        dropZone.classList.remove('bg-transparent');
    }
}

function handleDragLeave(event) {
    const dropZone = event.currentTarget;
    if (dropZone.classList.contains('drop-zone') && !dropZone.contains(event.relatedTarget)) {
        dropZone.classList.remove('bg-blue-100', 'ring-2', 'ring-blue-400', 'ring-inset');
    }
}

function handleDrop(event, listId) {
    event.preventDefault();
    event.stopPropagation();
    
    const dropZone = event.currentTarget;
    dropZone.classList.remove('bg-blue-100', 'ring-2', 'ring-blue-400', 'ring-inset');
    
    if (!draggedCardId) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/trello/cards/${draggedCardId}/move`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ list_id: listId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error moving card:', error);
        alert('Failed to move card. Please try again.');
    });
    
    draggedCardId = null;
}

// Close popover when clicking outside
document.addEventListener('click', function(event) {
    const popover = document.getElementById('addChecklistPopover');
    const checklistBtn = document.getElementById('checklistBtn');
    
    if (!popover.contains(event.target) && !checklistBtn.contains(event.target)) {
        popover.classList.add('hidden');
    }
});
</script>
@endsection
