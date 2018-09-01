<?php

namespace App\Http\Services\Inventary;
use App\Http\Services\Tatuco\TatucoService;
use App\Http\Services\Tatuco\ImageService;
use App\Models\Inventary\Brand;

class BrandService extends TatucoService
{

	public function __construct()
    {
        $this->name = 'brand';
        $this->model = new Brand();
        $this->namePlural = 'brands';
        $this->imageService = new ImageService();
    }

    public function store($request)
    {
        $images = json_encode($request->images);
        $array = $this->imageService($images);
        $request->merge(['images' => $array]);
    	return $this->_store($request);
    }

    public function update($id, $request)
    {
    	return $this->_update($id, $request);
    }
	
}