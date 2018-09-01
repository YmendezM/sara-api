<?php

namespace App\Models\Tatuco;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    protected $table = 'params';
    protected $fillable = [
        'code', 'title', 'key','value','description','enable','disable'
    ];

    public function setKeyAttribute($value)
    {
    	$this->attributes['key'] = strtoupper($value);
    }

    public function setTitleAttribute($value)
    {
    	$this->attributes['title'] = strtoupper($value);
    }
}
