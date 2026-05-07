function setupQuickCreateMarca() {
    const form = document.getElementById('createMarcaForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        
        fetch('/marcas', {  // Ajusta la ruta si es diferente
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cerrar modal
                const modal = Flowbite.getInstance(document.getElementById('createMarcaModal'));
                if (modal) modal.hide();
                else document.getElementById('createMarcaModal').classList.add('hidden');

                form.reset();

                // Agregar opción al select de marca
                const select = document.getElementById('marca_id');
                const option = document.createElement('option');
                option.value = data.marca.id;
                option.text = data.marca.nombre;
                option.selected = true;
                select.appendChild(option);

                // Notificación (opcional)
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
}

document.addEventListener('DOMContentLoaded', function() {
    setupQuickCreateMarca();
});