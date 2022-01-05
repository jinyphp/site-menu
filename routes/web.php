<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** ----- ----- ----- ----- -----
 *  Site Menu Router
 */

Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.site.')
->prefix('/admin/site')->group(function () {

    ## 메뉴 코드
    Route::resource('menu/code', \Jiny\Menu\Http\Controllers\Admin\MenuController::class);

    ## 메뉴 아이템 설정
    Route::get('menus/items/{id}',[\Jiny\Menu\Http\Controllers\Admin\MenuItemController::class,"index"]);

    ## 메뉴 코드
    Route::resource('menu/file', \Jiny\Menu\Http\Controllers\Admin\MenuFileController::class);

    // 메뉴 설정
    //xMenu()->setPath("menus/6.json");

    /*
    Route::prefix('/site')->name('site.')->group(function () {


    });
    */

    /*


    if(isset($user->menu)) {
            ## 사용자 지정메뉴 우선설정
            xMenu()->setPath($user->menu);
        } else {
            ## 설정에서 적용한 메뉴
            if(isset($this->actions['menu'])) {
                $menuid = _getKey($this->actions['menu']);
                xMenu()->setPath($this->MENU_PATH . DIRECTORY_SEPARATOR . $menuid . ".json");
            }
        }

        */



});
