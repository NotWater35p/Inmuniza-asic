lucide.createIcons();

    // Panel filtros
    function toggleFiltrosDespacho() {
        const panel   = document.getElementById('filtrosDespachoPanel');
        const overlay = document.getElementById('filtrosDespachoOverlay');
        const abierto = !panel.classList.contains('translate-x-full');
        panel.classList.toggle('translate-x-full', abierto);
        overlay.classList.toggle('hidden', abierto);
    }

    // Modal reportes
    function toggleReportesModal() {
        const modal   = document.getElementById('reportesModal');
        const overlay = document.getElementById('reportesOverlay');
        const visible = !modal.classList.contains('hidden');
        modal.classList.toggle('hidden', visible);
        overlay.classList.toggle('hidden', visible);
    }

    // Modal eliminar
    function abrirEliminarDespacho(id, vacuna, modulo) {
        document.getElementById('deleteDespachoVacuna').textContent = 'Vacuna: ' + vacuna;
        document.getElementById('deleteDespachoModulo').textContent  = 'Módulo destino: ' + modulo;
        document.getElementById('deleteDespachoForm').action = '{{ url("despachos") }}/' + id;
        document.getElementById('deleteDespachoModal').classList.remove('hidden');
    }

    // Form reporte por módulo: construir URL dinámicamente
    document.getElementById('formReporteModulo').addEventListener('submit', function(e) {
        e.preventDefault();
        const moduloId = this.querySelector('[name="modulo_id"]').value;
        if (!moduloId) { alert('Selecciona un módulo.'); return; }
        const params = new URLSearchParams();
        const mes  = this.querySelector('[name="mes"]').value;
        const anio = this.querySelector('[name="anio"]').value;
        if (mes)  params.append('mes', mes);
        if (anio) params.append('anio', anio);
        const base = '{{ url("despachos/reporte/modulo") }}/' + moduloId;
        window.location.href = base + (params.toString() ? '?' + params.toString() : '');
    });