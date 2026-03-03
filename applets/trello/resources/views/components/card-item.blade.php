<div class="bg-white rounded-md shadow-sm hover:shadow-md p-3 cursor-pointer transition-all duration-200 border-b-2 border-transparent hover:border-blue-500" 
     data-card-id="{{ $card->id }}"
     draggable="true"
     ondragstart="handleDragStart(event, {{ $card->id }})"
     ondragend="handleDragEnd(event)"
     onclick="showCardDetails({{ $card->id }})">
    <h4 class="font-medium text-sm text-gray-800 mb-2 break-words">{{ $card->title }}</h4>
    
    @if($card->description)
        <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ Str::limit($card->description, 80) }}</p>
    @endif
    
    <div class="flex flex-wrap gap-1.5 mb-2">
        @if($card->status)
            <span class="px-2.5 py-0.5 text-xs rounded-full font-medium
                {{ $card->status === 'done' ? 'bg-green-100 text-green-700' : 
                   ($card->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                {{ $card->status === 'done' ? '✓ Done' : ($card->status === 'in_progress' ? '⟳ In Progress' : 'To Do') }}
            </span>
        @endif
        
        @if($card->priority && $card->priority !== 'medium')
            <span class="px-2.5 py-0.5 text-xs rounded-full font-medium
                {{ $card->priority === 'high' ? 'bg-red-100 text-red-700' : 'bg-blue-50 text-blue-600' }}">
                {{ $card->priority === 'high' ? '🔴 High' : '🔵 Low' }}
            </span>
        @endif
    </div>
    
    @if($card->due_date)
        <div class="flex items-center gap-1 text-xs mt-2
            {{ $card->due_date->isPast() && $card->status !== 'done' ? 'text-red-600 font-medium' : 'text-gray-500' }}">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            {{ $card->due_date->format('M d') }}
        </div>
    @endif
    
    @php
        $totalChecklistItems = 0;
        $completedChecklistItems = 0;
        if ($card->checklists) {
            foreach ($card->checklists as $checklist) {
                if ($checklist->items) {
                    $totalChecklistItems += $checklist->items->count();
                    $completedChecklistItems += $checklist->items->where('is_completed', true)->count();
                }
            }
        }
    @endphp
    
    @if($totalChecklistItems > 0)
        <div class="flex items-center gap-1 text-xs mt-2 {{ $completedChecklistItems === $totalChecklistItems ? 'text-green-600 bg-green-50' : 'text-gray-600 bg-gray-50' }} px-1.5 py-0.5 rounded w-fit checklist-counter">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span class="font-medium">{{ $completedChecklistItems }}/{{ $totalChecklistItems }}</span>
        </div>
    @endif
</div>
