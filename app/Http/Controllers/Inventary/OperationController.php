<?php

namespace App\Http\Controllers\Inventary;

use Illuminate\Http\Request;
use App\Models\Inventary\Operation;
use App\Http\Controllers\Tatuco\TatucoController;
use App\Http\Services\Inventary\OperationService;

class OperationController extends TatucoController
{
      public function __construct()
    {
        $this->service = new OperationService();
        $this->columns = [
            'ID' => 'id',
            'Nombre' => 'title',
            'Cliente' => 'client',
            'Empleado' => 'employee',
            'Total' => 'total',
            'Fecha' => 'created_at'
        ];

        $this->consulta = Operation::select("id","title","client","employee","total","created_at");
    }

    public function index_op(Request $request)
    {
        return $this->service->index_op($request);
    }

    public function store(Request $request)
    {
     /* $code = $request->json(['code']);
        if(Operation::findByCode($code)){
            return response()->json([
                'status' => false,
                'message' => 'Ya Existe una Operacion con ese codigo'
            ], 500);
        }*/
        return $this->service->store($request);
    }

    public function update($id, Request $request)
    {
      return $this->service->update($id, $request);
    }
}