<?php

namespace App\Http\Controllers\Tatuco;

use App\Models\Tatuco\User;
use Illuminate\Http\Request;

use App\Reports\src\ReportMedia\ExcelReport;
use App\Reports\src\ReportMedia\PdfReport;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Tatuco\ReportService;


class ReportController extends Controller
{
    // PdfReport Aliases
    // use PdfReport;

    /**
     * @param Request $request
     * @return mixed
     */
    public $service;
    public $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function users(Request $request)
    {
        $columns = [
            'Nombre' => 'name',
            'Email' => 'email',
            'Fecha Ingreso' => 'created_at',
        ];

        $query = DB::table('users')
        ->select('name','email','created_at')
        ->get();

        return $this->reportService->prep($request,'Reporte de los Usuarios', $columns, $query);
    }

    /** ejemplo de metodo que llama al servicio padre
    *   para generar reports pdf cargando vistas
    *laravel
    *  
    */
    public function us(Request $request)
    {
         $query = DB::table('users')
        ->select('name','email','created_at')
        ->get();
        return $this->reportService->prepView($request, 'Reports.ReportTest', $query, 'users.pdf');
    }    

}