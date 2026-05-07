{{-- MODAL ELIMINAR --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-900/40">
    <div class="relative p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl text-center p-6">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="mx-auto mb-4 w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="user-x" class="w-7 h-7 text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar este paciente?</h3>
            <p id="deleteNombre" class="text-sm font-medium text-gray-700 mb-3"></p>
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                <p class="text-xs text-amber-700 flex items-center justify-center gap-1.5">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                    Si el paciente falleció, considera marcarlo como <strong>Inactivo</strong> en lugar de eliminar el registro.
                </p>
            </div>
            <p class="text-sm text-gray-500 mb-6">Esta acción es permanente y no se puede deshacer.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- Modal eliminar --}}
<div id="deleteShowModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-900/40">
    <div class="p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl text-center p-6">
            <button onclick="document.getElementById('deleteShowModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="mx-auto mb-4 w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="user-x" class="w-7 h-7 text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar este paciente?</h3>
            <p id="deleteShowNombre" class="text-sm font-medium text-gray-700 mb-3"></p>
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                <p class="text-xs text-amber-700">
                    💡 Si el paciente falleció, considera marcarlo como <strong>Inactivo</strong> en lugar de eliminar su historial.
                </p>
            </div>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteShowModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <form id="deleteShowForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>