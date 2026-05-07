<div id="createEtniaModal" class="hidden fixed inset-0 z-[60] flex justify-center items-center bg-gray-900/40">
    <div class="relative p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-purple-100 rounded-lg">
                        <i data-lucide="globe" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Nueva Etnia</h3>
                </div>
                <button onclick="cerrarModalEtnia()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div id="etniaAlerta" class="hidden mx-5 mt-4 p-3 rounded-lg text-sm"></div>

            <div class="p-5">
                <label for="etnia_nombre_modal" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Nombre de la Etnia <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="globe" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" id="etnia_nombre_modal"
                        placeholder="Ej: Wayuu, Pemón, Yanomami..."
                        class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <p id="etniaError" class="hidden mt-1.5 text-xs text-red-600"></p>
            </div>

            <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                <button onclick="cerrarModalEtnia()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <button onclick="guardarEtnia()" id="btnGuardarEtnia"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span id="btnGuardarEtniaText">Guardar Etnia</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function abrirModalEtnia() {
        document.getElementById('createEtniaModal').classList.remove('hidden');
        document.getElementById('etnia_nombre_modal').value = '';
        document.getElementById('etniaError').classList.add('hidden');
        document.getElementById('etniaAlerta').classList.add('hidden');
        document.getElementById('etnia_nombre_modal').focus();
        lucide.createIcons();
    }

    function cerrarModalEtnia() {
        document.getElementById('createEtniaModal').classList.add('hidden');
    }

    document.getElementById('createEtniaModal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalEtnia();
    });

    function guardarEtnia() {
        const nombre  = document.getElementById('etnia_nombre_modal').value.trim();
        const errorEl = document.getElementById('etniaError');
        const alertEl = document.getElementById('etniaAlerta');
        const btn     = document.getElementById('btnGuardarEtnia');
        const btnText = document.getElementById('btnGuardarEtniaText');

        if (!nombre) {
            errorEl.textContent = 'El nombre es obligatorio.';
            errorEl.classList.remove('hidden');
            return;
        }
        errorEl.classList.add('hidden');
        btn.disabled = true;
        btnText.textContent = 'Guardando...';

        fetch('{{ route("etnias.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nombre }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Agregar al dropdown
                const dropdown = document.getElementById('etnia_dropdown');
                const div = document.createElement('div');
                div.setAttribute('data-id', data.etnia.id);
                div.setAttribute('data-name', data.etnia.nombre);
                div.className = 'px-4 py-2.5 cursor-pointer hover:bg-blue-50 hover:text-blue-700 text-gray-700 text-sm';
                div.textContent = data.etnia.nombre;
                dropdown.appendChild(div);

                // Seleccionar
                document.getElementById('etnia_search').value   = data.etnia.nombre;
                document.getElementById('etnia_id_hidden').value = data.etnia.id;

                alertEl.className = 'mx-5 mt-4 p-3 rounded-lg text-sm bg-green-50 border border-green-200 text-green-700';
                alertEl.textContent = `Etnia "${data.etnia.nombre}" creada y seleccionada.`;
                alertEl.classList.remove('hidden');

                setTimeout(() => cerrarModalEtnia(), 1200);
            } else {
                const msg = data.errors?.nombre?.[0] || 'Error al guardar.';
                alertEl.className = 'mx-5 mt-4 p-3 rounded-lg text-sm bg-red-50 border border-red-200 text-red-700';
                alertEl.textContent = msg;
                alertEl.classList.remove('hidden');
            }
        })
        .catch(() => {
            alertEl.className = 'mx-5 mt-4 p-3 rounded-lg text-sm bg-red-50 border border-red-200 text-red-700';
            alertEl.textContent = 'Error de conexión. Intenta de nuevo.';
            alertEl.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled     = false;
            btnText.textContent = 'Guardar Etnia';
        });
    }
</script>
@endpush