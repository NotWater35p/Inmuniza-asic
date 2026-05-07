{{-- Modal crear marca — estilo consistente con el sistema --}}
<div id="createMarcaModal" class="hidden fixed inset-0 z-60 flex justify-center items-center bg-gray-900/40">
    <div class="relative p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl">

            {{-- Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-blue-100 rounded-lg">
                        <i data-lucide="flask-conical" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Nueva Marca / Fabricante</h3>
                </div>
                <button type="button" onclick="cerrarModalMarca()"
                    class="text-gray-400 hover:bg-gray-100 hover:text-gray-900 rounded-lg p-1.5">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Alerta resultado --}}
            <div id="marcaAlerta" class="hidden mx-5 mt-4 p-3 rounded-lg text-sm"></div>

            {{-- Body --}}
            <div class="p-5 space-y-4">
                <div>
                    <label for="marca_nombre" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Nombre de la Marca <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="flask-conical" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" id="marca_nombre"
                            placeholder="Ej: Pfizer, Sinovac, AstraZeneca..."
                            class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <p id="marcaNombreError" class="hidden mt-1.5 text-xs text-red-600"></p>
                </div>

                <div>
                    <label for="marca_descripcion" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Descripción <span class="text-gray-400 font-normal">(opcional)</span>
                    </label>
                    <textarea id="marca_descripcion" rows="2"
                        placeholder="País de origen, notas del fabricante..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 resize-none"></textarea>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                <button type="button" onclick="cerrarModalMarca()"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="button" id="btnGuardarMarca" onclick="guardarMarca()"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span id="btnGuardarMarcaText">Guardar Marca</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function abrirModalMarca() {
        document.getElementById('createMarcaModal').classList.remove('hidden');
        document.getElementById('marca_nombre').focus();
        document.getElementById('marca_nombre').value       = '';
        document.getElementById('marca_descripcion').value  = '';
        document.getElementById('marcaNombreError').classList.add('hidden');
        document.getElementById('marcaAlerta').classList.add('hidden');
        lucide.createIcons();
    }

    function cerrarModalMarca() {
        document.getElementById('createMarcaModal').classList.add('hidden');
    }

    // Cerrar al hacer click en overlay
    document.getElementById('createMarcaModal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalMarca();
    });

    function guardarMarca() {
    const nombre      = document.getElementById('marca_nombre').value.trim();
    const descripcion = document.getElementById('marca_descripcion').value.trim();
    const errorEl     = document.getElementById('marcaNombreError');
    const alertaEl    = document.getElementById('marcaAlerta');
    const btn         = document.getElementById('btnGuardarMarca');
    const btnText     = document.getElementById('btnGuardarMarcaText');

    // Limpiar 
    errorEl.classList.add('hidden');
    document.getElementById('marca_nombre').classList.remove('border-red-500');
    alertaEl.classList.add('hidden');

    if (!nombre) {
        errorEl.textContent = 'El nombre de la marca es obligatorio.';
        errorEl.classList.remove('hidden');
        document.getElementById('marca_nombre').classList.add('border-red-500');
        return;
    }

    // Deshabilitar botón
    btn.disabled = true;
    btnText.textContent = 'Guardando...';

    fetch('{{ route("vacunas.marca.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ nombre, descripcion }),
    })
    .then(async response => {
        const txt = await response.text();
        let data;
        try {
            data = JSON.parse(txt);
        } catch (e) {
            throw new Error('El servidor respondió con un error inesperado. Revisa la consola.');
        }

        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                const firstError = Object.values(data.errors)[0]?.[0] || 'Error de validación.';
                throw new Error(firstError);
            }
            throw new Error(data.message || 'Error al guardar la marca.');
        }

        return data;
    })
    .then(data => {
        if (data.success) {
            const select = document.getElementById('marca_id');
            const option = document.createElement('option');
            option.value    = data.marca.id;
            option.text     = data.marca.nombre;
            option.selected = true;
            select.appendChild(option);

            alertaEl.className = 'mx-5 mt-4 p-3 rounded-lg text-sm bg-green-50 border border-green-200 text-green-700 flex items-center gap-2';
            alertaEl.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Marca "<strong>${data.marca.nombre}</strong>" creada y seleccionada.`;
            alertaEl.classList.remove('hidden');

            setTimeout(() => cerrarModalMarca(), 1700);
        } else {
            throw new Error(data.message || 'Error desconocido.');
        }
    })
    .catch(error => {
        console.error('Error al guardar marca:', error);
        alertaEl.className = 'mx-5 mt-4 p-3 rounded-lg text-sm bg-red-50 border border-red-200 text-red-700';
        alertaEl.textContent = error.message || 'Error de conexión. Intenta de nuevo.';
        alertaEl.classList.remove('hidden');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Guardar Marca';
    });
}
</script>
@endpush