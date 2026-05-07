{{-- ===== MODAL ELIMINAR ===== --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-900/40">
    <div class="relative p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl text-center p-6">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="mx-auto mb-4 w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="user-x" class="w-7 h-7 text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar este personal?</h3>
            <p id="deleteNombre" class="text-sm font-medium text-gray-700 mb-1"></p>
            <p id="deleteCedula" class="text-xs text-gray-400 font-mono mb-4"></p>

            {{-- Advertencia si tiene usuario activo --}}
            <div id="deleteWarning" class="hidden mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                <p class="text-xs text-orange-700 flex items-center justify-center gap-1.5">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                    Este personal tiene un usuario activo. Revoca su acceso primero desde el módulo de Usuarios.
                </p>
            </div>

            <p class="text-sm text-gray-500 mb-6">Esta acción es permanente y no se puede deshacer.</p>

            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" id="deleteBtn"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
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
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar este personal?</h3>
            <p id="deleteShowNombre" class="text-sm font-medium text-gray-700 mb-1"></p>
            <p id="deleteShowCedula" class="text-xs text-gray-400 font-mono mb-4"></p>
            <div id="deleteShowWarning" class="hidden mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                <p class="text-xs text-orange-700 flex items-center justify-center gap-1.5">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                    Tiene usuario activo. Revoca su acceso desde Usuarios primero.
                </p>
            </div>
            <p class="text-sm text-gray-500 mb-6">Esta acción es permanente y no se puede deshacer.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteShowModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <form id="deleteShowForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" id="deleteShowBtn"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>