<?php

namespace App\Http\Services\Inventary;
use App\Http\Services\Tatuco\TatucoService;
use App\Models\Inventary\Operation;
use App\Models\Inventary\OperationDetail;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class OperationService extends TatucoService
{

	public function __construct()
    {
        $this->name = 'operation';
        $this->model = new Operation();
        $this->namePlural = 'operations';
        $this->select = $this->model::from('operation as o')
        ->join('client as c','o.client','=','c.id')
        ->join('client_type as ct', 'c.client_type', '=', 'ct.id')
        ->join('employee as e', 'o.employee','=','e.id')
        ->select('o.id','o.code as invoice','o.total','o.created_at as dateEmision','c.title as clientName','c.id as clientId', 'ct.title as clientType', 'e.id as employeeId','e.title as employeeName')
        ->where('o.enable',true)
        ->Where('o.disable',false)
        ->get();

        // $count = count($op);
        foreach ($this->select as $o) {
            $o->dateEmision = Carbon::parse($o->dateEmision)->format('d-m-Y');
            $o->details = DB::table('operation_detail as od')
            ->join('product as p','od.product','=','p.id')
            ->select('p.title as product','od.price','od.quantity')
            ->where('od.operation',$o->id)
            ->get();
             }
        
    }

    public function index_op($request)
    {
        $op = $this->model->list($request);
      //  $a = (new Operation)->getQuery()->doWhere($request['where']);

        $count = count($op);
        foreach ($op as $o) {
            $o->dateEmision = Carbon::parse($o->dateEmision)->format('d-m-Y');
             $o->details = (new OperationDetail)->list($o->id);
        }
        if ($count<1) {
        return response()->json([
            'status' => false,
            'data' => [],
            'count' => $count
        ],200);
    }
        return response()->json([
            'data' => $op,
          'status' => true,
        ],200);
    }

    public function store(Request $request)
    {
    	try{
            
            DB::beginTransaction();
            $operation = new Operation();
            $operation->code = $request->code;
            $operation->title = $request->title;
            $operation->description = $request->description;
            $operation->total = $request->total;
            $operation->tax = $request->tax;
            $operation->operation_type = $request->operation_type;
            $operation->client = $request->client;
            $operation->employee = $request->employee;
            $operation->save();

            $json = json_encode($request->details);
            $details = json_decode($json);
            $i = 0;

            while($i < count($details)){
                $detail = new OperationDetail();
                $detail->operation = $operation->id;
                $detail->product = $details[$i]->product;
                $detail->quantity = $details[$i]->quantity;
                $detail->price = $details[$i]->price;
                $detail->save();
                $i++;
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'operation' => $operation
            ],200);

        }catch(\Exception $e){
            DB::rollback();
            return $this->errorException($e);
        }
    }

    public function update($id, $request)
    {
    	return $this->_update($id, $request);
    }

    public function existsCode($code)
    {
        if(Operation::findByCode($code))
            return true;
         else
            return false;   
    }
}