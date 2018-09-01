<?php

namespace App\Http\Controllers\Tatuco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Tatuco\ParamService;

class ParamController extends TatucoController
{
  public function __construct()
    {
        $this->service = new ParamService();
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
