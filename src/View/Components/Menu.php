<?php
/**
 * json menu tree 생성하는 컴포넌트
 */
namespace Jiny\Menu\View\Components;

use Illuminate\View\Component;

class Menu extends Component
{
    public $jsondata = [];
    public $filename;

    public function __construct($path=null)
    {
        // json 파일 읽기
        if ($path) {
            \Jiny\Menu\Menu::instance()->setPath($path);
            $this->filename = $path;
        }

        $tree = \Jiny\Menu\Menu::instance()->load()->tree;
        $this->jsondata = $tree;
    }

    public function builder($slot)
    {
        $content = "";
        if (!empty($this->jsondata)) {
            $content .= (new \Jiny\Menu\MenuBuilder($this->jsondata))->make()->addClass("sidebar-nav");
        }

        return $content.$slot;
    }

    public function render()
    {
        return view('jinymenu::components.menu.menu');
    }
}
