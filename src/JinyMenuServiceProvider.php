<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Menu;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;

class JinyMenuServiceProvider extends ServiceProvider
{
    private $package = "jiny-menu";
    public function boot()
    {
        // 모듈: 라우트 설정
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package);

        // 데이터베이스
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // 설정파일 복사
        $this->publishes([
            __DIR__.'/../config/setting.php' => config_path('jiny/menu/setting.php'),
        ]);


        //Blade::component(\Jiny\Admin\View\Components\Tree::class, "admin-tree");

        //메뉴 빌더를 호출
        //Blade::component(\Jiny\Menu\View\Components\Menu::class, "menu-json");

        // 마우스 오른쪽 클릭메뉴
        // context
        //Blade::component(\Jiny\Menu\View\Components\Context::class, "context-menu");
    }

    public function register()
    {
        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {

            // Json 데이터를 이용한 트리메뉴 구현
            Livewire::component('Widget-TopMenu',
                \Jiny\Menu\Http\Livewire\WidgetTopMenu::class);

            Livewire::component('Widget-SubMenu',
                \Jiny\Menu\Http\Livewire\WidgetSubMenu::class);


            /*
            ## Livewire::component('WireTree', \Jiny\Menu\Http\Livewire\Admin\WireTree::class);
            Livewire::component('WireTreeDrag', \Jiny\Menu\Http\Livewire\Admin\WireTreeDrag::class);

            // PopupForm을 상속 재구현한 tree 입력폼 처리루틴
            Livewire::component('PopupTreeFrom', \Jiny\Menu\Http\Livewire\Admin\PopupTreeFrom::class);

            Livewire::component('WireUpload', \Jiny\Menu\Http\Livewire\Admin\WireUpload::class);
            //Livewire::component('Admin-SiteMenu-Code', \Jiny\Menu\Http\Livewire\Admin\MenuCodeWire::class);

            Livewire::component('menu-json', \Jiny\Menu\Http\Livewire\Menu::class);
            */
        });

    }

}
