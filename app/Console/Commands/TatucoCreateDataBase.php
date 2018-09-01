<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class TatucoCreateDataBase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tatuco:db {db}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear Base de Datos';

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
        DB::statement('CREATE DATABASE "'.$this->argument('db').'"');
            $this->info('Creando Base de Datos Postgresql ...');
            $this->info('Base de datos : '.$this->argument('db').' creada.');
    }
}
