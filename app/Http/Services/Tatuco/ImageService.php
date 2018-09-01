<?php
namespace App\Http\Services\Tatuco;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
class ImageService 
{
	public $rules = array('image' => 'required|image|mimes:jpeg,jpg,png,gif|10000');
	public $route = "../storage/images/";

	public function images($images)
	{
		$images = json_decode($images);
		$now = Carbon::now();
		foreach ($images as $image) {
			$file = $image->file('images');
			$validation = Validator::make($file, $rules);
			if (!$validation) {
						return response()->json([
							"status" => false,
							"message" => "El Archivo no es una Imagen Valida: (jpeg,jpg,png,gif|10000)"
						],500);
					}		
			$nameOrigin = $file->getClientOriginName();
			$extend = $file->getClientOriginExtension();
			$newName = $now.'.'.$extend;
			$result = Storage::disk('images')->put($newName, \File::get($file));
			if(!$result){
				return response()->json([
							"status" => false,
							"message" => "La Imagen no pudo ser copiada al directorio establecido"
						],500);
			}

			$array = array_push($array, $newName);

		}

		return $array;
	}
}
