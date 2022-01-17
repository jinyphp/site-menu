<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Menu\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;

class Menu extends Component
{
    //public $jsondata = [];
    public $filename;

    public function mount($path=null)
    {
        // path 경로를 설정합니다.
        if ($path) {
            \Jiny\Menu\Menu::instance()->setPath($path);
            $this->filename = $path;
        } else {
            $this->filename = \Jiny\Menu\Menu::instance()->path;
        }
    }


    public function render()
    {
        // 메뉴 데이터를 읽어 옵니다.
        $menu = \Jiny\Menu\Menu::instance();
        $menu->setPath($this->filename);
        $tree = $menu->load()->tree;
        //$this->jsondata = $tree;

        $menuTree = $menu->build();

        return view('jinymenu::livewire.menu', ['menuTree'=>$menuTree]);
    }


    public $adminDesign = false;

    public $popupEsayMenu = false;
    public function popupEasyMenuClose()
    {
        $this->popupEsayMenu = false;
    }

    public function popupEasyMenuOpen()
    {
        $this->popupEsayMenu = true;
    }

    public function create($ref)
    {
        $this->popupEasyMenuOpen();
    }


}
