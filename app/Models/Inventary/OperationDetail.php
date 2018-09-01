<?php

namespace App\Models\Inventary;

use Illuminate\Database\Eloquent\Model;
use DB;
class OperationDetail extends Model
{
    protected $table = 'operation_detail';

      public function products()
    {
    	return $this->belongsTo('App\Models\Inventary\products','product','id');
    }

    public function list($operationId)
    {
    	 return DB::table('operation_detail as od')
            ->join('product as p','od.product','=','p.id')
            ->select('p.title as product','od.price','od.quantity')
            ->where('od.operation',$operationId)
            ->get();
    }
}
