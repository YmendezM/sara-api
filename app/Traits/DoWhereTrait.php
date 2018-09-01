<?php
namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Illuminate\Database\Query\Builder as QueryBuilder;
trait DoWhereTrait
{

public function sort($sort)
{
// example order=["id","desc"]
    if ($sort) {
        $sort = json_decode($sort);
        $this->orderBy($sort[0], $sort[1]);
    }
}


public function doWhere($where)
{
    // $query = (new Builder)->newQuery();
     /*   return $query->where(
            $this->getKeyName(), $this->getKey()
        )->{$method}($column, $amount, $extra);*/
    if (isset($where)) {
    $where = json_decode($where);
    if(is_array($where)){       
        for ($i=0; $i < count($where); $i++) { 
            $array = $this->evalWhere($where[$i]);
            $field =  $array[0];
            $operator = $array[1];
            $value = $array[2];
            $filter = $array[3];   
            switch ($where[$i]->op) {
                case 'bt':
                    $this->whereBetween($field,$value);     
                    break;
                case 'in':
                     $this->whereIn($field,$value,$filter);
                    break;
                case 'ct':
                    $this->where($field,$operator,'%'.$value.'%',$filter); 
                    break;
                case 'sw':
                    $this->where($field,$operator,$value.'%',$filter);
                    break;
                case 'ew':
                    $this->where($field,$operator,'%'.$value,$filter);
                    break;       
                default:
                    $this->where($field,$operator,$value,$filter);
                    break;
            }
                if (isset($where[$i]->type) && $where[$i]->type == "date") {
                    $this->whereDate($field,$operator,$value,$filter);
                }                                       
            }
        }
    }

    return $this;
}

public function forWhere($where){
         $where = json_decode($where);
    if(is_array($where)){       
        for ($i=0; $i < count($where); $i++) { 
            print_r($where[$i]);
            return $where[$i];
        }
    }
}

public function evalWhere($where)
{
    if(isset($where->op) && isset($where->field) && isset($where->value)) {
        $op = $this->getOp($where->op);
            if (!isset($where->filter)) {
                $where->filter = 'and';
            }
        
            $array = [$where->field, $op, $where->value, $where->filter]; 
                         //print_r($array);
            return $array;            
    }
}    

 
    
    public function evalMeWhere($string)
    {
         $array = [];
        $a = str_replace('"', "", $string);
        $b = str_replace('[', '', $a);
        $d = str_replace(']', '', $b);

        $val = [];
        $arrayWhere = explode(',', $d);

         for ($i=0; $i < count($arrayWhere) ; $i++) { 
              array_push($val,(explode(':', $arrayWhere[$i])));
         }
         $posicion=[];
         for ($i=0; $i < 3; $i++) { 
             $ope = $val[$i];
             for ($j=0; $j < 2; $j++) { 
                 array_push($posicion, $ope[$j]);

             }
         }

     
           return $arraglo = [
                $posicion[0] => $posicion[1],
                $posicion[2] => $posicion[3],
                $posicion[4] => $posicion[5]
            ];

         
    }

    public function getOp($op)
    {
        switch ($op) {
            case 'ct'://contiene algo en un string
                return 'like';
                break;
            case 'sw': //k% comienza con
                return 'like';
                break;
            case 'ew':   //%k temina con
                return 'like';
                break;        
            case 'eq':  // = igual ah
                return '=';
                break;
            case 'gt':  //mayor que
                return '>';
                break;
            case 'gte':  //mayor o igual 
                return '>=';
                break;
            case 'lte':  //menor o igual
                return '<=';
                break;
            case 'lt':   // menor que
                return '<';
                break;
            case 'in':  // en []
                return 'in';
                break;
            case 'bt': //entre
                return 'bt';
                break;              
            default:
                return '=';
                break;
        }   
    }

    public function doSelect($select = ['*'])
    {
        $this->select($select);
    }
    /**
    * join = [tabla, fk, op, pk, tipo de join]
    */
    public function doJoin($joins)
    {
        if (isset($joins) && is_array($joins)) {
            $joins = json_decode($joins);        
            for ($i=0; $i < count($joins); $i++) { 
                 $array = $this->evalJoin($joins[$i]);
                 $this->join($array[0], $array[1], $array[2], $array[4], $array[5])
            }
        }
    }

    public function evalJoin($join)
    {
        if(isset($join->table) && isset($join->first))
        {
            $array = [$join->table, $join->first, '=', $join->second?:null, $type?:'inner']; 
            return $array;            
        }
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

/**where=[{"op":"bt","field":"o.id","value":[1,3],"filter":"and"},{"op":"in","field":"o.id","value":[5,9],"filter":"or"},{"op":"eq","field":"o.id","value":4,"filter":"or"}]*/
}