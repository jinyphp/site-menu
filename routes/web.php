<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** ----- ----- ----- ----- -----
 *  Site-Menu 관리자
 */
Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.site.')
->prefix('/admin/site')->group(function () {

    ## 메뉴 코드
    Route::resource('menu/code', \Jiny\Menu\Http\Controllers\Admin\MenuController::class);

    ## 메뉴 아이템 설정
    Route::get('menus/items/{menu_id}',[\Jiny\Menu\Http\Controllers\Admin\MenuItemController::class,"index"]);

    ## 메뉴 파일
    Route::resource('menu/file', \Jiny\Menu\Http\Controllers\Admin\MenuFileController::class);

});

/** ----- ----- ----- ----- -----
 *  Design UI mode
 */

Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.easy.')
->prefix('/admin/easy')->group(function () {

    Route::resource('/menu/{menu_id}/items',
        \Jiny\Menu\Http\Controllers\Admin\EasyMenuItem::class);

    /*
    Route::get('/menu/{menu_id}/items/create/{ref}',
        [\Jiny\Menu\Http\Controllers\Admin\EasyMenuItem::class,"create"])->name("menu.create");

    Route::post('/menu/{menu_id}/items/create/{ref}',
        [\Jiny\Menu\Http\Controllers\Admin\EasyMenuItem::class,"store"])->name("menu.store");
    */
});


/** ----- ----- ----- ----- -----
 *  menu ajax api 설정
 */
Route::middleware(['web','auth:sanctum', 'verified'])
->prefix('/api')->group(function () {
    Route::post('menu/pos',[\Jiny\Menu\API\Controllers\Pos::class,"index"]);
});
