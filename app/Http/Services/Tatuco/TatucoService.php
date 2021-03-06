<?php

namespace App\Http\Services\Tatuco;

use App\Http\Requests\UserRequest;
use App\Models\Tatuco\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Optimus\Bruno\EloquentBuilderTrait;
use Optimus\Bruno\LaravelController;
//use Tymon\JWTAuth\JWTAuth;
use Carbon\Carbon;
use App\Http\Services\Tatuco\ReportService;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Routing\ControllerMiddlewareOptions;
use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;
class TatucoService extends LaravelController
{
    use EloquentBuilderTrait;
    public $model;
    public $object;
    public $name;
    public $namePlural;
    public $paginate = false;
    public $limit = null;
    public $data = [];
    public $request;
    public $middleware = [];
    public $reportService;
    public $select;

    /** token de sysadmin eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvbG9naW4iLCJpYXQiOjE1MTg0NzM5MjYsImV4cCI6MTUxODQ3NzUyNiwibmJmIjoxNTE4NDczOTI2LCJqdGkiOiIwQ2RBZ1JPS3N6ZFR1ZU1DIn0.4X9UtMLM5EwrLG_aSZ_3QEZ4oK0IZgbnMwoTpQUmmd0
     * @return \Illuminate\Http\JsonResponse
     * consultar todos los registros de un modelo
     * uso de parseresource y applyresource para el uso de los parametros
     * incude[] limits sorts etc ...
     * leer paquetes
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    public function index(){ 
     /*   if (($this->checkPermission('index.'.$this->name)) == false ) {
             return response()->json([
                        'status'=> false,
                        'message' => 'no tienes permiso para => index.'.$this->name
                    ],403);
        }*/
        try{
        if ($this->select) {
            return response()->json([
                $this->namePlural => $this->select
            ],200);   
        }
         $resourceOptions = $this->parseResourceOptions();
        $query = $this->model::query();
        $this->applyResourceOptions($query, $resourceOptions);
        $items = $query->get();

        $parsedData = $this->parseData($items, $resourceOptions, $this->namePlural);

        if(!$this->response($parsedData))
        {
            return response()->json([
                "message"=> "no hay registros"
            ], 200);
        }

        return response()->json($parsedData, 200);
        }catch(\Exception $e){
           return errorException($e);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * consultar un registro por medio de un id
     */
    public function show($id)
    {
        /*if (($this->checkPermission('show.'.$this->name)) == false ) {
             return response()->json([
                        'status'=> false,
                        'message' => 'no tienes permiso para => show.'.$this->name
                    ],403);
        }*/
        try{
            $resourceOptions = $this->parseResourceOptions();

            $this->data = $this->model::find($id);

            if(!$this->data)
            {
                return response()->json(['message'=>$this->name. ' no existe'], 404);
            }
            $parsedData = $this->parseData($this->data, $resourceOptions, $this->name);

            Log::info('Encontrado');
            return response()->json([
                'status'=>true,
                'message'=> $this->name. ' Encontrado',
                'data' => $parsedData,
            ], 200);
        }catch (\Exception $e){
             return $this->errorException($e);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonRespon
     * guardar un registro nuevo
     */
    public function _store(Request $request)
    {
      /*  if (($this->checkPermission('store.'.$this->name)) == false ) {
             return response()->json([
                        'status'=> false,
                        'message' => 'no tienes permiso para => store.'.$this->name
                    ],403);
        }*/
        try{

            if (count($this->data) == 0)
            {
               $this->data = $request->all();
            }

            Log::info('Guardado');
            if($this->object = $this->model::create($this->data)){
                return response()->json(['status'=>true,
                    'message'=>$this->name. ' Guardado',
                    $this->name=>$this->object], 201);
            }

        }catch (\Exception $e){
            return $this->errorException($e);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * acualizar registro
     */
    public function _update($id,Request $request)
    {
         /*  if (($this->checkPermission('update.'.$this->name)) == false ) {
             return response()->json([
                        'status'=> false,
                        'message' => 'no tienes permiso para => update.'.$this->name
                    ],403);
        }*/
         try {
            $this->object = $this->model->findOrFail($id);

            if (count($this->data) == 0) {
                $this->data = $request->all();
            }

            $this->object->update($this->data);

            return response()->json([
                'startus'=>true,
                'message'=>$this->name. ' Modificado',
                $this->name=>$this->object
            ], 200);

        }catch(\Exception $e){
            return $this->errorException($e);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * metodo para eliminar un registro
     */
    public function destroy($id)
    {
         /*  if (($this->checkPermission('destroy.'.$this->name)) == false ) {
             return response()->json([
                        'status'=> false,
                        'message' => 'no tienes permiso para => destroy.'.$this->name
                    ],403);
        }*/
         try {
             $this->object = $this->model->findOrFail($id);

             if (!$this->object) {
                 return response()->json([
                     'message' => $this->name . ' no existe'
                 ], 404);
             }
            if($this->object->enable)
             $this->object->enable = false;
            if($this->object->enable)
             $this->object->disable = true;
            if($this->object->code)
             $this->object->code = $this->object->code.'-D-'.Carbon::now();
            /*else
             $this->object->delete();*/
             $this->object->update();
             return response()->json([
                 'status' => true,
                 'message' => $this->name . ' Eliminado'
             ], 206);
        }catch (\Exception $e){
           return $this->errorException($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * metodo para realizar respaldo a la base de datos
     * llamando al comando schedule:run de laravel
     * para que ejecute las tareas pendientes
     */
   public function backup()
   {

      try {
            if((\Artisan::call('schedule:run', array()))==0){
                return response()->json([
                    'status' => true,
                    'message' => 'Backup Existoso'
                ],200);
            }else{
               return response()->json([
                    'status' => false,
                    'message' => 'Backup Fallido'
                ],500); 
            }
        }catch (\Exception $e){
            return $this->errorException($e);
        }
   }

     /**
     * listar registros del objeto
     */
   public function prueba(Request $request){
         return response()->json([
             'message' => 'registros de usuarios',
             'model' => $this->name?:null,
             'request' => $request
         ],200);
   }

   public function createJson(){
        return $this->model->createJson();
   }

    /**
     * @param $id
     * @return bool
     * metodo para verificar si un registro existe o no
     * con el fin de devolver una respues de no encontrado
     */
   public function findByItem($id)
   {
       if(!$this->model->find($id))
           return false;
       else
           return true;
   }

    /**
     * @param null $item
     * @return \Illuminate\Http\JsonResponse
     * respuesta de no encontrado en formato json
     * puede recibir un string para escificar que cosa no se encontro
     */
   public function notFound($item = null)
   {
       return response()->json([
           'message'=> $item.' no Encontrado'
       ],404);
   }

   public function report(Request $request, $columns, $joins = null)
   {
        return (new ReportService)->report($request, $this->model, $this->namePlural, $columns, $joins!=null?$joins:null);
       //return response()->json(['modelo' => $this->model->all()]);
   }

   public function errorException(\Exception $e)
   {
         Log::critical("Error, archivo del peo: {$e->getFile()}, linea del peo: {$e->getLine()}, el peo: {$e->getMessage()}");
              return response()->json([
            "message" => "Error de servidor",
            "exception" => $e->getMessage(),
            "file" => $e->getFile(),
            "line" => $e->getLine(),
            "code" => $e->getCode()
            ], 500);     
   }

  public function checkPermission($permissions)
  {
     
            if (!JWTAuth::parseToken()->authenticate()->can($permissions?:$this->permissions)) {
                 /*   return response()->json([
                        'status'=> false,
                        'message' => 'no tienes permiso para => '.$permissions
                    ],403);*/
                    return false;
            }

        return true;
  }

}
