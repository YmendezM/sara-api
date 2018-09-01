<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tatuco:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->error('ejemplo de error');
        $this->alert('ejemplo de alerta');
        $this->question(' ejemplo pregunta ');
        $this->warn(' ejemplo alerta 2');
        $array = [
            "pregunta 1",
            "pregunta 2"
        ];
        $a = $this->askWithCompletion('preguntas', $array);
        if($a == $array[0]){
                $this->alert('seleccionas la opcion uno');
        }else{
             $this->alert('seleccionas la opcion dos');
        }
    }
}
