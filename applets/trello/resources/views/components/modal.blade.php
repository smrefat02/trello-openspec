<div id="{{ $modalId ?? 'modal' }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Modal' }}</h3>
            <button onclick="closeModal('{{ $modalId ?? 'modal' }}')" class="text-gray-500 hover:text-gray-700">
                ✕
            </button>
        </div>
        
        <div class="mt-2">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}
</script>
