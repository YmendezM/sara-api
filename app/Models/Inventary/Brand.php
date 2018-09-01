<?php

namespace App\Models\Inventary;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tatuco\TatucoModel;

class Brand extends Model
{
	protected $table = 'brand';
    protected $fillable = [
        'code', 'title', 'image','description','enable','disable'
    ];
    protected $attributes = [
    	"id","code","title","image","description","enable","disable","created_at","updated_at"
    ];
    public function products()
   {
   	 return $this->hasMany('App\Models\Inventary\Product','product_type','id');
   }

}
