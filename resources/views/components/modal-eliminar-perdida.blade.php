{{--
    Componente reutilizable: Modal de confirmación para eliminar pérdida.
    Uso: @include('components.modal-eliminar-perdida')
    Requiere las funciones JS: abrirModalPerdida(url) y cerrarModalPerdida()
    que se incluyen al final de este archivo vía @push('scripts').
--}}
<div id="modalEliminarPerdida"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
    role="dialog" aria-modal="true" aria-labelledby="modalPerdidaTitulo">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
        onclick="cerrarModalPerdida()"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm z-10 p-6 text-center">

        {{-- Botón cerrar --}}
        <button type="button" onclick="cerrarModalPerdida()"
            class="absolute top-3 right-3 p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
            aria-label="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>
        </button>

        {{-- Icono --}}
        <div class="mx-auto w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-600" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/><path d="M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>

        {{-- Texto --}}
        <h3 id="modalPerdidaTitulo" class="text-lg font-bold text-gray-900 mb-1">¿Eliminar pérdida?</h3>
        <p class="text-sm text-gray-500 mb-6 leading-relaxed">
            Esta acción no se puede deshacer. El stock del módulo se ajustará
            automáticamente al eliminar el registro.
        </p>

        {{-- Acciones --}}
        <div class="flex gap-3">
            <button type="button" onclick="cerrarModalPerdida()"
                class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancelar
            </button>
            <form id="formEliminarPerdida" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-colors">
                    Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function abrirModalPerdida(url) {
    document.getElementById('formEliminarPerdida').action = url;
    document.getElementById('modalEliminarPerdida').classList.remove('hidden');
    document.getElementById('modalEliminarPerdida').classList.add('flex');
}
function cerrarModalPerdida() {
    document.getElementById('modalEliminarPerdida').classList.add('hidden');
    document.getElementById('modalEliminarPerdida').classList.remove('flex');
}
// Cerrar con Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarModalPerdida();
});
// Dropdown: cerrar al hacer clic fuera
document.addEventListener('click', function(e) {
    if (!e.target.closest('.perdida-dropdown-wrap')) {
        document.querySelectorAll('.perdida-dropdown-menu').forEach(m => m.classList.add('hidden'));
    }
});
function toggleDropdownPerdida(id) {
    const menu = document.getElementById('perdida-menu-' + id);
    // Cerrar todos los demás
    document.querySelectorAll('.perdida-dropdown-menu').forEach(m => {
        if (m.id !== 'perdida-menu-' + id) m.classList.add('hidden');
    });
    menu.classList.toggle('hidden');
}
</script>
@endpush
