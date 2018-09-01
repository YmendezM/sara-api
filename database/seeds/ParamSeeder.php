<?php

use Illuminate\Database\Seeder;

class ParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('params')->insert([
            'code' => "P-001",
            'title' => 'DIR_DOWNLOAD_REPORT',
            'key' => 'DIR_DOWNLOAD_REPORT',
            'value' => '/home',
            'description' => 'directorio donde se guardaran los reportes',
            'created_at'=> new DateTime,
            'updated_at'=> new DateTime
        ]);


         DB::table('params')->insert([
            'code' => "P-003",
            'title' => 'LOGO',
            'key' => 'LOGO',
            'value' => '../storage/app/public/tatuco.png',
            'description' => 'logotipo de la empresa',
            'created_at'=> new DateTime,
            'updated_at'=> new DateTime
        ]);

          DB::table('params')->insert([
            'code' => "P-004",
            'title' => 'IP_SYSTEM',
            'key' => 'IP_SYSTEM',
            'value' => 'http://localhost:8000',
            'description' => 'direccion ip del sistema',
            'created_at'=> new DateTime,
            'updated_at'=> new DateTime
        ]);
    }
}
