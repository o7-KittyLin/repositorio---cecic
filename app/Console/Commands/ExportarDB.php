<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ExportarDB extends Command
{
    protected $signature = 'exportarDB';
    protected $description = 'Clona la DB, enmascara y exporta sin tocar la original';

    public function handle()
    {
        $realDb = env('DB_DATABASE');
        $tempDb = $realDb . '_masked_' . now()->format('His');

        $this->info("Base real: $realDb");
        $this->info("Creando copia temporal: $tempDb");

        DB::statement("CREATE DATABASE `$tempDb`");

        $structure = sprintf(
            'mysqldump -u%s -p%s --no-data %s | mysql -u%s -p%s %s',
            env('DB_USERNAME'), env('DB_PASSWORD'), $realDb,
            env('DB_USERNAME'), env('DB_PASSWORD'), $tempDb
        );
        system($structure);

        DB::statement("SET FOREIGN_KEY_CHECKS=0");

        $tables = DB::select("SHOW TABLES");
        foreach ($tables as $t) {
            $table = array_values((array) $t)[0];
            DB::statement("INSERT INTO `$tempDb`.`$table` SELECT * FROM `$realDb`.`$table`");
        }

        DB::statement("SET FOREIGN_KEY_CHECKS=1");

        $this->info("Copia creada. Enmascarando datos…");

        config(['database.connections.mysql.database' => $tempDb]);
        DB::purge('mysql');
        DB::reconnect('mysql');

        $faker = Faker::create();

        // --- USERS ---
        DB::table('users')->orderBy('id')->chunk(100, function ($users) use ($faker) {
            foreach ($users as $user) {
                DB::table('users')->where('id', $user->id)->update([
                    'name' => $faker->name(),
                    'email' => $faker->unique()->userName() . '@local.test',
                    'password' => bcrypt('prueba123'),
                    'profile_photo_path' => null,
                ]);
            }
        });

        // --- PASSWORD RESET TOKENS ---
        DB::table('password_reset_tokens')->truncate();

        // --- SESSIONS ---
        DB::table('sessions')->orderBy('id')->chunk(200, function ($sessions) {
            foreach ($sessions as $session) {
                DB::table('sessions')->where('id', $session->id)->update([
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'FakeAgent',
                ]);
            }
        });

        $this->info("Enmascarado completado.");

        // 5. Exportar dump sin pedir contraseña
        $filename = 'db_enmascarada_' . now()->format('Ymd_His') . '.sql';
        $this->info("Exportando dump…");

        $dump = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            env('DB_USERNAME'), env('DB_PASSWORD'), $tempDb, $filename
        );
        system($dump);

        $this->info("Dump generado: $filename");

        DB::statement("DROP DATABASE `$tempDb`");
        $this->info("Base temporal eliminada.");

        return 0;
    }
}
