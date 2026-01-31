<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CreateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea la base de datos configurada en pgsql si no existe.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $databaseName = trim(config('database.connections.pgsql.database'), "'\"");
        
        if (!$databaseName) {
            $this->error('No se ha configurado ninguna base de datos en config/database.php para pgsql.');
            return 1;
        }

        $this->info("Verificando existencia de la base de datos: {$databaseName}");

        try {
            // Intentamos conectar para ver si ya existe
            DB::connection('pgsql')->getPdo();
            $this->info("La base de datos [{$databaseName}] ya existe.");
            return 0;
        } catch (\Exception $e) {
            // Si falla la conexión, probablemente es porque no existe la base de datos
            $this->warn("No se pudo conectar a [{$databaseName}]. Intentando crearla...");
            
            try {
                // Configuramos una conexión temporal a la base de datos 'postgres' (por defecto en Laragon/PG)
                $config = config('database.connections.pgsql');
                $config['database'] = 'postgres';
                $config['username'] = trim($config['username'], "'\"");
                $config['password'] = trim($config['password'], "'\"");
                
                Config::set('database.connections.pgsql_temp', $config);

                // Comprobar si existe (por si acaso fue otro error de conexión)
                $exists = DB::connection('pgsql_temp')->select("SELECT 1 FROM pg_database WHERE datname = ?", [$databaseName]);

                if (empty($exists)) {
                    // PostgreSQL no soporta parámetros en CREATE DATABASE, así que usamos el nombre directamente.
                    // Usamos comillas dobles para evitar problemas con nombres que empiecen con números o tengan guiones.
                    DB::connection('pgsql_temp')->statement("CREATE DATABASE \"{$databaseName}\"");
                    $this->info("Base de datos [{$databaseName}] creada con éxito.");
                } else {
                    $this->info("La base de datos [{$databaseName}] ya existe (detectada vía postgres).");
                }

                return 0;
            } catch (\Exception $innerException) {
                $this->error("Error crítico al intentar crear la base de datos: " . $innerException->getMessage());
                return 1;
            }
        }
    }
}
