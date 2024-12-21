<?php

use Illuminate\Http\Request;
use \Illuminate\Http\Response;
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

Route::middleware('auth:api')->get('/user', 'UserController@getUserInfo')->name('admin.userInfo');
Route::post('/login', 'Auth\LoginController@login')->name('login.login');
Route::post('/loginWithThree', 'Auth\LoginController@loginWithThree')->name('login.loginWithThree');
Route::post('/token/refresh', 'Auth\LoginController@refresh')->middleware('auth:api')->name('login.refresh');
Route::post('/logout', 'Auth\LoginController@logout')->name('login.logout');
//Route::post('test/{id}/remark', 'PackageController@remark')->name('soft.test');
Route::middleware('auth:api')->group(function() {
    // 用户管理
    Route::Resource('admin', 'UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('/admin/modify', 'UserController@modify' )->name('admin.modify');
    Route::post('/admin/{id}/reset', 'UserController@reset')->name('admin.reset');
    Route::post('/admin/uploadAvatar', 'UserController@uploadAvatar')->name('admin.uploadAvatar');
    Route::post('/admin/upload', 'UserController@upload')->name('admin.upload');
    Route::post('/admin/export', 'UserController@export')->name('admin.export');
    Route::post('/admin/exportAll', 'UserController@exportAll')->name('admin.exportAll');
    Route::post('/admin/deleteAll', 'UserController@deleteAll')->name('admin.deleteAll');

    // 角色管理
    Route::Resource('role', 'RoleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('getRoles', 'RoleController@getRoles')->name('role.get');

    // 其他支持API
    Route::get('/getSession', 'SessionController@getSession')->name('session.get'); // 获取所有学期
    Route::get('/getDefaultSession', 'SessionController@getDefaultSession')->name('session.getDefault'); //获得当前学期
    Route::get('/getClassNumByGrade', 'SessionController@getClassNumByGrade')->name('session.getClassNum'); // 根据年级获取最大班级数


    // 学期管理
    Route::Resource('session', 'SessionController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('/session/upload', 'SessionController@upload')->name('session.upload');

    // 程序功能管理
    Route::Resource('permissions', 'PermissionController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('/permissions/addGroup', 'PermissionController@addGroup')->name('permissions.addGroup');
    Route::post('/permissions/getGroup', 'PermissionController@getGroup')->name('permissions.getGroup');
    Route::post('/permissions/deleteAll', 'PermissionController@deleteAll')->name('permissions.deleteAll') ;
    Route::post('/permissions/getPermissionByTree', 'PermissionController@getPermissionByTree')->name('Permission.getPermissionByTree');

    // 手机信息管理
    Route::post('/sms/send', 'SmsController@send')->name('sms.send');
    Route::post('/sms/verify', 'SmsController@verify')->name('sms.verify');

    // 学生信息管理
    Route::resource('students', 'StudentController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('students/deleteAll', 'StudentController@deleteAll')->name('students.deleteAll');
    Route::post('students/upload', 'StudentController@upload')->name('students.upload');
    Route::post('students/export', 'StudentController@export')->name('students.export');
    Route::post('students/exportAll', 'StudentController@exportAll')->name('students.exportAll');

    // 日志管理API
    Route::get('logs/show', 'LogController@show')->name('logs.show'); // 操作日志
    Route::get('logs', 'LogController@index')->name('logs.index');  // 登录日志

    //智能提醒
    Route::apiResource('cacls', 'CaclController');
    Route::post('cacls/deleteAll', 'CaclController@deleteAll')->name('cacls.deleteAll');
    Route::post('cacls/upload', 'CaclController@upload')->name('cacls.upload');
    Route::post('cacls/export', 'CaclController@export')->name('cacls.export');
    // 商品分类
    Route::get('cates/getAll', 'CateController@getAll')->name('cates.getAll'); // 获取所有的种类信息
    Route::apiResource('cates', 'CateController');
    Route::post('cates/deleteAll', 'CateController@deleteAll')->name('cates.deleteAll');
    Route::post('cates/upload', 'CateController@upload')->name('cates.upload');

    // 导入商品的配置表
    Route::get('configs/getAll', 'ConfigController@getAll')->name('configs.getAll');
    Route::apiResource('configs', 'ConfigController');
    Route::post('configs/deleteAll', 'ConfigController@deleteAll')->name('configs.deleteAll');
    Route::post('configs/upload', 'ConfigController@upload')->name('configs.upload');
    // 商品表
    Route::get('goods/getInfo', 'GoodController@getInfo')->name('goods.getInfo');
    Route::apiResource('goods', 'GoodController');
    Route::post('goods/deleteAll', 'GoodController@deleteAll')->name('goods.deleteAll');
    Route::post('goods/upload', 'GoodController@upload')->name('goods.upload');

    // 字典参数表， 与智能算法配合使用
    Route::apiResource('params', 'ParamController');
    Route::post('params/deleteAll', 'ParamController@deleteAll')->name('params.deleteAll');
    Route::post('params/upload', 'ParamController@upload')->name('params.upload');
    // 销售表
    Route::apiResource('sells', 'SellController');
    Route::post('sells/deleteAll', 'SellController@deleteAll')->name('sells.deleteAll');
    Route::post('sells/upload', 'SellController@upload')->name('sells.upload');
    // 库存表
    Route::apiResource('stocks', 'StockController');
    Route::post('stocks/upload', 'StockController@upload')->name('sells.upload');
    Route::post('stocks/export', 'StockController@export')->name('sells.export');
    // 入库表
    Route::apiResource('storages', 'StorageController');
    Route::post('storages/deleteAll', 'StorageController@deleteAll')->name('storages.deleteAll');
    Route::post('storages/upload', 'StorageController@upload')->name('storages.upload');

    // 采购表
    Route::get('purchases/getInfo','PurchaseController@getInfo')->name('purchases.getInfo');
    Route::post('purchases/upload', 'PurchaseController@upload')->name('purchases.upload');
    Route::post('purchases/deleteAll', 'PurchaseController@deleteAll')->name('purchases.deleteAll');
    Route::post('purchases/importStorage', 'PurchaseController@importStorage')->name('purchases.importStorage');
    Route::apiResource('purchases', 'PurchaseController')->only(['index', 'show', 'update', 'destroy']);

    // 订单表
    Route::get('orders/{orderId}/packages', 'PackageController@packages')->name('orders.packages');  // 订单对应的包裹信息  一个订单有几个包裹

    Route::get('orders/{id}/products', 'OrderController@products')->name('orders.products');  // 获取指定的订单下所有的产品信息
    Route::get('orders/{id}/enable', 'OrderController@enable')->name('orders.enable');  // 获取指定的订单下没有发完的产品
    Route::get('orders/{id}/summary', 'OrderController@summary')->name('orders.summary');

    Route::apiResource('orders', 'OrderController');

    // 产品管理
    Route::apiResource('products', 'ProductController');
    Route::Post('packages/{id}/remark', 'PackageController@remark')->name('packages.remark');
    // 包裹管理
    Route::get('packages/{id}/info','PackageController@showPackage')->name('packages.info');  // 显示包裹的详细信息  一个包裹有多少个产品

    Route::apiResource('packages', 'PackageController');

    // 详情管理
    Route::apiResource('details', 'DetailController');

});
