<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.')
->prefix('/admin')->group(function () {

    Route::prefix('/site')->name('site.')->group(function () {
        ## 메뉴구조
        //Route::resource('menu',\Jiny\Admin\Http\Controllers\Site\MenuListController::class);
        //return view('jinyadmin::site.menu.index');
        Route::view('menu', 'jinymenu::admin.menu.code');
        //Route::view('menu/items', 'jinyadmin::site.menu.items');
        Route::get('menu/{id}',[\Jiny\Menu\Http\Controllers\Admin\MenuItems::class,"index"]);

    });

});
