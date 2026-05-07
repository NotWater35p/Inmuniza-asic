{{-- Modal eliminar --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50">
    <div class="bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-red-100 rounded-full">
                <i data-lucide="triangle-alert" class="w-5 h-5 text-red-600"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-900">Eliminar módulo</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            ¿Estás seguro de eliminar <strong id="deleteNombre" class="text-gray-900"></strong>?
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancelar
            </button>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Modal de eliminación (se accede desde el show) --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50">
    <div class="bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-red-100 rounded-full">
                <i data-lucide="triangle-alert" class="w-5 h-5 text-red-600"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-900">Eliminar módulo</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            ¿Estás seguro de eliminar <strong id="deleteNombre" class="text-gray-900"></strong>? Esta acción no se puede deshacer.
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancelar
            </button>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>