@php
    $config = match((int)$nivel) {
        5 => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'icon' => 'shield',       'label' => 'Administrador'],
        3 => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'icon' => 'briefcase-medical',    'label' => 'Asist. Administrativo'],
        2 => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => 'house-heart',     'label' => 'Jefe de Módulo'],
        1 => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'icon' => 'syringe',      'label' => 'Vacunador'],
        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'user', 'label' => 'Sin cargo'],
    };
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
    <i data-lucide="{{ $config['icon'] }}" class="w-3 h-3"></i>
    {{ $config['label'] }}
</span>