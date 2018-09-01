<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/prueba/operation', 'Inventary\OperationController@index_op');
Route::get('/prueba','Inventary\BrandController@json');
Route::get('us', 'Tatuco\ReportController@us');
Route::get('/', 'Tatuco\ApiController@api');
Route::get('/home', 'Tatuco\ApiController@api');
Route::post('/login', ['uses' => 'Tatuco\AuthController@login', 'as' => 'login']);
Route::post('/logout', ['middleware' => ['jwt.auth'], 'uses' => 'Tatuco\AuthController@logout', 'as' => 'logout']);
Route::get('/validate', ['middleware' => ['jwt.auth'], 'uses' => 'Tatuco\AuthController@validate', 'as' => 'validate']);
/**
 * grupo de rutas controladas por jwt (requieren token)
 */
Route::group([
    'middleware' => ['jwt.auth']
    ], function (){
    /**
     * grupo de rutas controladas por el rol sysadmin
     */
Route::group(['middleware' => ['role:sysadmin']], function (){
    Route::resource('users', 'Tatuco\UserController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::get('images', 'Tatuco\UserController@images');
    Route::resource('roles', 'Tatuco\RoleController', ['only' => ['index', 'store', 'update', 'destroy', 'show']]);
    Route::resource('permissions', 'Tatuco\PermissionController', ['only' => ['index', 'store', 'update', 'destroy', 'show']]);
    Route::resource('params', 'Tatuco\ParamController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::get('/backup', 'Tatuco\TatucoController@backup')->middleware('permission:run.basckup');
    Route::post('users/role', 'Tatuco\UserController@assignedRole');
    Route::get('users/role/{user}/{role}', 'Tatuco\UserController@revokeRole');
    Route::post('roles/permission', 'Tatuco\RoleController@assignedPermission');
    Route::get('roles/permission/{role}/{permission}', 'Tatuco\RoleController@revokePermission');
});
    /**
     * grupo de rutas controladas por los roles sysadmin y admin
     */
Route::group(['middleware' => ['role:admin']], function () {
            /**
             * rutas del rol administrador
             */
});

Route::group(['middleware' => ['role:sysadmin']], function () {

    Route::group(['prefix' => 'types'], function () {
        Route::resource('/clients', 'Inventary\ClientTypeController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
        Route::resource('/employees', 'Inventary\EmployeeTypeController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
        Route::resource('/products', 'Inventary\ProductTypeController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
        Route::resource('/suppliers', 'Inventary\SupplierTypeController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    });
    
    
    Route::resource('categories', 'Inventary\CategoryController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('employees', 'Inventary\EmployeeController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('clients', 'Inventary\ClientController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('inventary', 'Inventary\InventaryController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('measures', 'Inventary\MeasureController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('operations', 'Inventary\OperationController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('details/operations', 'Inventary\OperationDetailController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('products', 'Inventary\ProductController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('suppliers', 'Inventary\SupplierController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('taxs', 'Inventary\TaxController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
    Route::resource('brands', 'Inventary\BrandController', ['only' => ['index', 'store', 'update', 'destroy', 'show','create']]);
});

Route::group(['prefix' => 'reports'], function () {
    
        Route::get('users','Tatuco\UserController@report');
        Route::get('client','Inventary\ClientController@report');
        Route::get('brands','Inventary\BrandController@report');
        Route::get('categories','Inventary\CategoryController@report');
        Route::get('employees','Inventary\EmployeeController@report');
        Route::get('measures','Inventary\MeasuresController@report');
        Route::get('operations','Inventary\OperationController@report');
        Route::get('products','Inventary\ProductController@report');
        Route::get('suppliers','Inventary\SupplierController@report');
        Route::get('taxs','Inventary\TaxController@report');


});


});
