<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Personal;
use App\Models\User;
use App\Models\Representante;
use App\Models\Paciente;
use App\Models\Asic;
use App\Models\Cargo;
use App\Models\Etnia;
use App\Models\Sector;
use App\Models\Modulo;
use App\Models\Jornada;

/**
 * PruebaSeeder — Datos de prueba / demostración
 * ──────────────────────────────────────────────
 * Contiene datos ficticios para probar el sistema:
 *   · Personal del ASIC (con sus usuarios)
 *   · Representantes de pacientes
 *   · Pacientes de prueba
 *   · Jornadas de ejemplo
 *
 * ⚠️  ESTOS DATOS DEBEN BORRARSE ANTES DE ENTREGAR
 *     EL SISTEMA AL CLIENTE PARA USO REAL.
 *
 * Ejecutar: php artisan db:seed --class=PruebaSeeder
 * Limpiar:  ver tidb_truncate_prueba.sql
 *
 * NOTA: Requiere que MaestroSeeder ya haya corrido.
 */
class PruebaSeeder extends Seeder
{
    public function run(): void
    {
        $asic          = Asic::first();
        $cargoAdmin    = Cargo::where('nombre', 'Administrador')->first();
        $cargoAsist    = Cargo::where('nombre', 'Asistente Administrativo')->first();
        $cargoJefe     = Cargo::where('nombre', 'Jefe de Módulo')->first();
        $cargoVac      = Cargo::where('nombre', 'Vacunador')->first();
        $moduloPrados  = Modulo::where('nombre', 'Los Prados')->first();
        $moduloBarran  = Modulo::where('nombre', 'Barranquitas')->first();
        $etniaNoAplica = Etnia::where('nombre', 'No aplica')->first();
        $etniaWayuu    = Etnia::where('nombre', 'Wayúu')->first();
        $sectorJalisco = Sector::where('nombre', 'Sector Jalisco')->first();
        $sectorDelirio = Sector::where('nombre', 'Sector El Delirio')->first();
        $sectorPrados  = Sector::where('nombre', 'Sector Los Prados')->first();

        // ── Personal ─────────────────────────────────────────────
        $personales = [
            [
                'cedula'   => 30938548,
                'asic_id'  => $asic->id,
                'nombre'   => 'Dany',
                'apellido' => 'Herazo',
                'cargo_id' => $cargoAdmin->id,
                'telefono' => '0412-1111111',
                'correo'   => 'admin@ilapeca.gob.ve',
            ],
            [
                'cedula'   => 22222222,
                'asic_id'  => $asic->id,
                'nombre'   => 'Carmen',
                'apellido' => 'Hernández',
                'cargo_id' => $cargoAsist->id,
                'telefono' => '0414-2222222',
                'correo'   => 'asistente@ilapeca.gob.ve',
            ],
            [
                'cedula'   => 27345657,
                'asic_id'  => $asic->id,
                'nombre'   => 'Manolo',
                'apellido' => 'Campos',
                'cargo_id' => $cargoJefe->id,
                'telefono' => '0416-3333333',
                'correo'   => 'jefe.barranquitas@ilapeca.gob.ve',
            ],
            [
                'cedula'   => 33333333,
                'asic_id'  => $asic->id,
                'nombre'   => 'Carlos',
                'apellido' => 'García',
                'cargo_id' => $cargoJefe->id,
                'telefono' => '0416-4444444',
                'correo'   => 'jefe.prados@ilapeca.gob.ve',
            ],
            [
                'cedula'   => 44444444,
                'asic_id'  => $asic->id,
                'nombre'   => 'Elena',
                'apellido' => 'Suárez',
                'cargo_id' => $cargoVac->id,
                'telefono' => '0424-5555555',
                'correo'   => 'vacunador1@ilapeca.gob.ve',
            ],
        ];

        foreach ($personales as $p) {
            Personal::firstOrCreate(['cedula' => $p['cedula']], $p);
        }

        // Actualizar jefe en módulos de prueba
        $moduloPrados?->update(['jefe_cedula' => 33333333]);
        $moduloBarran?->update(['jefe_cedula' => 27345657]);

        // ── Usuarios del sistema ──────────────────────────────────
        // Contraseñas de prueba — cambiar antes de entregar al cliente
        $users = [
            ['cedula' => 30938548, 'name' => 'Dany Herazo',      'email' => 'admin@ilapeca.gob.ve',             'pass' => 'admin123'],
            ['cedula' => 22222222, 'name' => 'Carmen Hernández',  'email' => 'asistente@ilapeca.gob.ve',         'pass' => 'asistente123'],
            ['cedula' => 27345657, 'name' => 'Manolo Campos',     'email' => 'jefe.barranquitas@ilapeca.gob.ve', 'pass' => 'jefe123'],
            ['cedula' => 33333333, 'name' => 'Carlos García',     'email' => 'jefe.prados@ilapeca.gob.ve',       'pass' => 'jefe123'],
            ['cedula' => 44444444, 'name' => 'Elena Suárez',      'email' => 'vacunador1@ilapeca.gob.ve',        'pass' => 'vacunador123'],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'personal_cedula' => $u['cedula'],
                    'name'            => $u['name'],
                    'email'           => $u['email'],
                    'password'        => Hash::make($u['pass']),
                ]
            );
        }

        // ── Representantes ───────────────────────────────────────
        $representantes = [
            ['cedula' => 12345678, 'telefono' => '0412-1234567', 'relacion' => 'Madre'],
            ['cedula' => 87654321, 'telefono' => '0414-9876543', 'relacion' => 'Padre'],
            ['cedula' => 11223344, 'telefono' => '0426-5554433', 'relacion' => 'Abuela'],
        ];
        foreach ($representantes as $r) {
            Representante::firstOrCreate(['cedula' => $r['cedula']], $r);
        }

        // ── Pacientes de prueba ───────────────────────────────────
        $pacientes = [
            [
                'cedula'           => 20123456,
                'nombres'          => 'Juan Carlos',
                'apellidos'        => 'Márquez',
                'fecha_nacimiento' => '1985-05-12',
                'sexo'             => 'M',
                'telefono'         => '0412-9998887',
                'direccion'        => 'Calle Bolívar #45, Sector Jalisco',
                'etnia_id'         => $etniaNoAplica?->id,
                'representante_id' => null,
                'sector_id'        => $sectorJalisco?->id,
                'activo'           => true,
            ],
            [
                'cedula'           => 20987654,
                'nombres'          => 'Ana María',
                'apellidos'        => 'López',
                'fecha_nacimiento' => '1990-08-23',
                'sexo'             => 'F',
                'telefono'         => '0414-5554433',
                'direccion'        => 'Av. Principal, Casa 12, La Manga',
                'etnia_id'         => $etniaWayuu?->id,
                'representante_id' => null,
                'sector_id'        => $sectorDelirio?->id,
                'activo'           => true,
            ],
            [
                'cedula'           => 30111222,
                'nombres'          => 'Luisana',
                'apellidos'        => 'González',
                'fecha_nacimiento' => '2018-11-03',
                'sexo'             => 'F',
                'telefono'         => '0426-1239876',
                'direccion'        => 'Sector El Delirio, Calle 5',
                'etnia_id'         => null,
                'representante_id' => 12345678,
                'sector_id'        => null,
                'activo'           => true,
            ],
            [
                'cedula'           => 30222333,
                'nombres'          => 'Jesús',
                'apellidos'        => 'Martínez',
                'fecha_nacimiento' => '2020-01-15',
                'sexo'             => 'M',
                'telefono'         => null,
                'direccion'        => 'Av. Las Flores, Casa 5',
                'etnia_id'         => null,
                'representante_id' => 87654321,
                'sector_id'        => null,
                'activo'           => true,
            ],
            [
                'cedula'           => 15325436,
                'nombres'          => 'Sebastián José',
                'apellidos'        => 'Carmona Suárez',
                'fecha_nacimiento' => '1988-02-18',
                'sexo'             => 'M',
                'telefono'         => '0285-4444444',
                'direccion'        => 'Sector Los Prados, Casa 3',
                'etnia_id'         => $etniaNoAplica?->id,
                'representante_id' => null,
                'sector_id'        => $sectorPrados?->id,
                'activo'           => true,
            ],
        ];
        foreach ($pacientes as $p) {
            Paciente::firstOrCreate(['cedula' => $p['cedula']], $p);
        }

        // ── Jornadas de prueba ────────────────────────────────────
        $jornadas = [
            [
                'asic_id'              => $asic->id,
                'modulo_id'            => null,
                'fecha_jornada'        => '2025-04-15',
                'descripcion'          => 'Jornada de vacunación en Sector Centro — prueba',
                'personal_responsable' => 30938548,
            ],
            [
                'asic_id'              => $asic->id,
                'modulo_id'            => null,
                'fecha_jornada'        => '2025-04-22',
                'descripcion'          => 'Jornada casa por casa en La Manga — prueba',
                'personal_responsable' => 30938548,
            ],
        ];
        foreach ($jornadas as $j) {
            Jornada::firstOrCreate([
                'asic_id'       => $j['asic_id'],
                'fecha_jornada' => $j['fecha_jornada'],
            ], $j);
        }

        $this->command->info('✅ PruebaSeeder completado — datos de prueba cargados.');
        $this->command->warn('⚠️  Recuerda borrar estos datos antes de entregar el sistema al cliente.');
    }
}
