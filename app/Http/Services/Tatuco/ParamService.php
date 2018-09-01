<?php
namespace App\Http\Services\Tatuco;

use App\Models\Tatuco\Param;
use Illuminate\Http\Request;
use DB;
use Mockery\Exception;

class ParamService extends TatucoService
{
    public function __construct()
    {
        $this->name = 'param';
        $this->model = new Param();
        $this->namePlural = 'params';
    }

    public function store(Request $request){
        return $this->_store($request);
    }
    public function update($id,Request $request)
    {
        return $this->_update($id, $request);
    }

    public function findValueForKey($key)
    {
        try{
           $value = $this->model->select('value')
            ->where('key','=',$key)->first('value');
            return json_decode($value)->value;

        }catch(\Exception $e){
            return $this->errorException($e);
        }
    }
   
}
