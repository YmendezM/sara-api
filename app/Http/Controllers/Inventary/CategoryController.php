<?php

namespace App\Http\Controllers\Inventary;

use Illuminate\Http\Request;
use App\Http\Controllers\Tatuco\TatucoController;
use App\Http\Services\Inventary\CategoryService;

class CategoryController extends TatucoController
{
      public function __construct()
    {
        $this->service = new CategoryService();
        $this->columns = [
            'ID' => 'id',
            'Nombre' => 'title'
        ];
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function update($id, Request $request)
    {
      return $this->service->update($id, $request);
    }
}
