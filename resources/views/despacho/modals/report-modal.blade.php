{{-- ===== MODAL REPORTES ===== --}}
<div id="reportesOverlay" onclick="toggleReportesModal()" class="hidden fixed inset-0 z-40 bg-gray-900/40"></div>

<div id="reportesModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="flex items-center justify-between p-5 border-gray-200 bg-success">
            <div class="flex items-center gap-2 text-white">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <h3 class="text-base font-semibold">Generar Reporte PDF</h3>
            </div>
            <button onclick="toggleReportesModal()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-5 space-y-3">
            {{-- Por módulo --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i data-lucide="building-2" class="w-4 h-4 text-purple-500"></i>
                    Por Módulo
                </h4>
                <form method="GET" id="formReporteModulo" class="space-y-3">
                    <select name="modulo_id" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <option value="">Seleccionar módulo...</option>
                        @foreach($modulos as $m)
                        <option value="{{ $m->id }}" {{ request('modulo_id')==$m->id ? 'selected' : '' }}>
                            {{ $m->nombre }}
                        </option>
                        @endforeach
                    </select>
                    <div class="grid grid-cols-2 gap-2">
                        <select name="mes"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg p-2">
                            <option value="">Todos los meses</option>
                            @for($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ now()->month == $m ? 'selected' :
                                '' }}>
                                {{ Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                                </option>
                                @endfor
                        </select>
                        <select name="anio"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg p-2">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Descargar PDF
                    </button>
                </form>
            </div>

            {{-- Por vacuna --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i data-lucide="syringe" class="w-4 h-4 text-green-500"></i>
                    Por Vacuna
                </h4>
                <form method="GET" action="{{ route('despachos.reporte.vacuna') }}" class="space-y-3">
                    <select name="vacuna_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <option value="">Todas las vacunas</option>
                        @foreach($vacunas as $v)
                        <option value="{{ $v->id }}">{{ $v->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Descargar PDF
                    </button>
                </form>
            </div>

            {{-- Por período --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i data-lucide="calendar-range" class="w-4 h-4 text-blue-500"></i>
                    Por Período General
                </h4>
                <form method="GET" action="{{ route('despachos.reporte.periodo') }}" class="space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block mb-1 text-xs text-gray-500">Desde</label>
                            <input type="date" name="fecha_desde"
                                class="bg-gray-50 border border-gray-300 text-xs rounded-lg block w-full p-2">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs text-gray-500">Hasta</label>
                            <input type="date" name="fecha_hasta"
                                class="bg-gray-50 border border-gray-300 text-xs rounded-lg block w-full p-2">
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Descargar PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
