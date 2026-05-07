    lucide.createIcons();

    const nivelLabels = {
        5: { label: 'Administrador',        bg: 'bg-red-100',    text: 'text-red-700',    icon: 'shield' },
        3: { label: 'Asist. Administrativo',bg: 'bg-blue-100',   text: 'text-blue-700',   icon: 'briefcase' },
        2: { label: 'Jefe de Módulo',       bg: 'bg-yellow-100', text: 'text-yellow-700', icon: 'building' },
        1: { label: 'Vacunador',            bg: 'bg-green-100',  text: 'text-green-700',  icon: 'syringe' },
    };

    // ---- Searchable personal ----
    const input    = document.getElementById('personal_search');
    const hidden   = document.getElementById('personal_cedula_hidden');
    const dropdown = document.getElementById('personal_dropdown');

    input.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        let vis = 0;
        dropdown.querySelectorAll('[data-id]').forEach(item => {
            const m = item.dataset.name.toLowerCase().includes(q) ||
                      item.dataset.id.toString().includes(q);
            item.style.display = m ? '' : 'none';
            if (m) vis++;
        });
        dropdown.classList.toggle('hidden', vis === 0);
        if (!this.value) { hidden.value = ''; ocultarCard(); }
    });

    input.addEventListener('focus', () => {
        dropdown.querySelectorAll('[data-id]').forEach(i => i.style.display = '');
        dropdown.classList.remove('hidden');
    });

    dropdown.querySelectorAll('[data-id]').forEach(item => {
        item.addEventListener('mousedown', function(e) {
            e.preventDefault();
            input.value  = this.dataset.name;
            hidden.value = this.dataset.id;
            dropdown.classList.add('hidden');
            mostrarCard(this.dataset);
            // Autocompletar email
            if (this.dataset.correo) {
                document.getElementById('email').value = this.dataset.correo;
            }
        });
    });

    document.addEventListener('click', e => {
        if (!input.contains(e.target) && !dropdown.contains(e.target))
            dropdown.classList.add('hidden');
    });

    function mostrarCard(data) {
        const card = document.getElementById('personalCard');
        card.classList.remove('hidden');
        document.getElementById('personalNombre').textContent = data.name;
        document.getElementById('personalCedula').textContent = data.id;
        document.getElementById('personalCargo').textContent  = data.cargo;
        document.getElementById('loginCedula').textContent    = data.id;
        document.getElementById('avatarLetra').textContent    = data.name.charAt(0).toUpperCase();

        // Badge nivel
        const cfg = nivelLabels[parseInt(data.nivel)] || { label:'Sin cargo', bg:'bg-gray-100', text:'text-gray-600', icon:'user' };
        document.getElementById('nivelBadge').innerHTML = `
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ${cfg.bg} ${cfg.text}">
                <i data-lucide="${cfg.icon}" class="w-3 h-3"></i>
                ${cfg.label}
            </span>`;
        lucide.createIcons();
    }

    function ocultarCard() {
        document.getElementById('personalCard').classList.add('hidden');
    }

    // Si hay personal preseleccionado, mostrar card
    @if($personalPresel)
    mostrarCard({
        name:  '{{ $personalPresel->nombre }} {{ $personalPresel->apellido }}',
        id:    '{{ $personalPresel->cedula }}',
        cargo: '{{ $personalPresel->cargo?->nombre }}',
        nivel: '{{ $personalPresel->cargo?->nivel_acceso ?? 0 }}',
    });
    @endif

    // ---- Fortaleza de contraseña ----
    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8)         score++;
        if (/[A-Z]/.test(val))       score++;
        if (/[0-9]/.test(val))       score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['', 'bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
        const labels = ['', 'Muy débil', 'Débil', 'Moderada', 'Fuerte'];

        for (let i = 1; i <= 4; i++) {
            const bar = document.getElementById('bar' + i);
            bar.className = 'h-1 flex-1 rounded-full transition-colors ' +
                (i <= score ? colors[score] : 'bg-gray-200');
        }
        const lbl = document.getElementById('strengthLabel');
        lbl.textContent = val.length > 0 ? 'Contraseña ' + labels[score] : '';
        lbl.className   = 'text-xs ' + (score >= 3 ? 'text-green-600' : score >= 2 ? 'text-yellow-600' : 'text-red-500');
    }

    function checkMatch() {
        const p1  = document.getElementById('password').value;
        const p2  = document.getElementById('password_confirmation').value;
        const lbl = document.getElementById('matchLabel');
        if (!p2) { lbl.classList.add('hidden'); return; }
        lbl.classList.remove('hidden');
        if (p1 === p2) {
            lbl.textContent  = '✓ Las contraseñas coinciden';
            lbl.className    = 'mt-1.5 text-xs text-green-600';
        } else {
            lbl.textContent  = '✗ Las contraseñas no coinciden';
            lbl.className    = 'mt-1.5 text-xs text-red-600';
        }
    }