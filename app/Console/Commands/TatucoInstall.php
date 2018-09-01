<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tatuco\User;
use DB;
use Illuminate\Support\Facades\Artisan;
use PhpSpec\Exeception\Exeception;
use App\Models\Tatuco\Param;
use Carbon\Carbon;

class TatucoInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tatuco:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instalar ApiTatuco';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try{
            DB::connection();
        }catch (\Exception $e){
            $this->error('Coneccion con la base de datos desabilitada');
            $this->error('Verifique las credenciales de usuario para a base de datos');
            return;
        }
       
        $this->comment('------------------------------------------------');
        $this->comment('         Instalacion de ApiTatuco               ');
        $this->comment('------------------------------------------------');
         if(env('DB_CONNECTION') == 'pgsql'){
        if(env('DB_DATABASE')){
         $array = [
            "Si",
            "No"
        ];
          $ask = $this->askWithCompletion('Desea Crear la Base de Datos? : '.env('DB_DATABASE'), $array);
          if($ask == $array[0]){
             DB::statement('CREATE DATABASE "'.env('DB_DATABASE').'"');
            $this->info('Creando Base de Datos Postgresql ...');
            $this->info('Base de datos : '.env('DB_DATABASE').' creada.');
        }else{
             $ask2 = $this->askWithCompletion('Desea Crear una base de Datos? : ', $array);
              if($ask2 == $array[0]){
             $bd = $this->ask('Ingrese Nombre de la Base de Datos: ');
             DB::statement('CREATE DATABASE "'.$bd.'"');
             $this->info('Creando Base de Datos Postgresql ...');
             $this->info('Base de datos : '.$bd.' creada.'); 
             $this->alert('No olvides igualar la variable de entorno DB_DATABASE = '.$bd);
             }
           
        }
 
       

        if(!env('APP_KEY')) {
            $this->info('Generando Key de Laravel');
            Artisan::call('key:generate');
        }

        $this->info('Ejecutando Migraciones ...');
        Artisan::call('migrate');
        $this->info('Migracion de la Base de Datos Existosa');

        $this->info('Ejecutando Seeders');
        Artisan::call('db:seed');
        $this->info('Data Insertada.');

        DB::beginTransaction();
        try{
            $this->comment('------------------------------------------------');
            $this->comment('                 Configuracion                  ');
            $this->comment('------------------------------------------------');

            $dir = $this->ask('Ingrese directorio para los backups: ');
            $name = $this->ask('Ingrese nombre de usuario : ');
            $email = $this->ask('Ingrese correo : ');
            $pass = $this->secret('Ingrese contraseña : ');
            $now = Carbon::now();
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($pass),
                'created_at'=> $now,
                'updated_at'=> $now
            ]);

            DB::table('role_user')->insert([
            'user_id'=> $user->id,
            'role_id'=> 1,
            'created_at'=> $now,
            'updated_at'=> $now
            ]);

            DB::table('role_user')->insert([
            'user_id'=> $user->id,
            'role_id'=> 2,
            'created_at'=> $now,
            'updated_at'=> $now
            ]);

            DB::table('role_user')->insert([
            'user_id'=> $user->id,
            'role_id'=> 3,
            'created_at'=> $now,
            'updated_at'=> $now
            ]);

            DB::table('role_user')->insert([
            'user_id'=> $user->id,
            'role_id'=> 4,
            'created_at'=> $now,
            'updated_at'=> $now
            ]);

            Param::create([
                'code' => 'BACKUP',
                'title' => 'DIR_BACKUP_BD',
                'key' => 'DIR_BACKUP_BD',
                'value' => $dir,
                'description' => 'directorio donde se guardaran los respaldos de la base de datos',
                'created_at'=> $now,
                'updated_at'=> $now
            ]);

            DB::commit();

            $this->info('Configuracion Existosa');

        }catch(Exception $e){
            DB::rollBack();
            $this->error(' Error en el guardado de la configuracion.');
            $this->error($e);
        }



        $this->comment('------------------------------------------------');
        $this->comment('         ApiTatuco Instalada Existosamente      ');
        $this->comment('------------------------------------------------');
         }else{
               $this->alert('La variable de entorno DB_DATABASE esta vacia');
        }

       }else{//si la bd no es postgres
          $this->alert('Esta Instalacion esta configurada solo para Bases de Datos Postgresql');
       }

    }
}
