<?php

namespace App\Models\Inventary;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tatuco\TatucoModel;
use Carbon\Carbon;
use DB;
use App\Traits\DoWhereTrait;
use App\Query\QueryBuilder;
class Operation extends TatucoModel
{

    //use DoWhereTrait;

    protected $table = 'operation';
    protected $dates = [
    	'created_at',
    	'update_at'
    ];

    public function taxs()
    {
    	return $this->belongsTo('App\Models\Inventary\Tax','tax','id');
    }
     public function details()
    {
        return $this->hasMany('App\Models\Inventary\OperationDetail','operation','id');
    }
    public function getCreatedAtAttribute($date)
    {
    	 return $this->attributes['created_at'] = Carbon::parse($date); 
    }
    public function setCreatedAtAttribute($date)
    {
         return $this->attributes['created_at'] = Carbon::parse($date); 
    }
    public function list($request = null)
    {
        return QueryBuilder::for(Operation::class)
        ->join('client as c','operation.client','=','c.id')
        ->join('client_type as ct', 'c.client_type', '=', 'ct.id')
        ->join('employee as e', 'operation.employee','=','e.id')
        ->select('operation.id','operation.code as invoice','operation.total','operation.created_at as dateEmision','c.title as clientName','c.id as clientId', 'ct.title as clientType', 'e.id as employeeId','e.title as employeeName')
        ->where('operation.enable',true)
        ->Where('operation.disable',false)
        ->doWhere($request['where'])
        ->get();
    }
}
