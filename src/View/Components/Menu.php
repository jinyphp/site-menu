<?php

namespace Jiny\Menu\View\Components;

use Illuminate\View\Component;
/**
 * json menu tree 생성
 */
class Menu extends Component
{
    public $jsondata = [];
    public $filename;
    public function __construct($json=null)
    {
        // json 파일 읽기
        if ($json) {
            $path = resource_path($json);
            $json = file_get_contents($path);
            $jsondata = json_decode($json,true);

            $this->jsondata = $jsondata;
            $this->filename = $json;
        }

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
