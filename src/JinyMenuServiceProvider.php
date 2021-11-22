<?php

namespace Jiny\Menu;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;

class JinyMenuServiceProvider extends ServiceProvider
{
    private $package = "jinymenu";
    public function boot()
    {
        // 모듈: 라우트 설정
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package);

        //Blade::component(\Jiny\Admin\View\Components\Tree::class, "admin-tree");

        //메뉴 빌더를 호출
        Blade::component(\Jiny\Menu\View\Components\Menu::class, "menu");


    }

    public function register()
    {
        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {
            //Livewire::component('LiveTreeJson', \Jiny\Admin\Http\Livewire\LiveTreeJson::class);

            Livewire::component('Admin-SiteMenu-Items', \Jiny\Menu\Http\Livewire\Admin\MenuItemsWire::class);
            Livewire::component('Admin-SiteMenu-Code', \Jiny\Menu\Http\Livewire\Admin\MenuCodeWire::class);
        });

    }

}
