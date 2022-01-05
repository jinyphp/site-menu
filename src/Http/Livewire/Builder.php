<?php

namespace Jiny\Menu\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;

class Builder extends Component
{
    public $json= "menus/1.json";
    public function mount()
    {

    }

    public function build()
    {
        if ($this->json) {
            $path = resource_path($this->json);
            $json = file_get_contents($path);
            $jsondata = json_decode($json,true);
        }

        // Active 선택, Cookie
        /*
        if ($_COOKIE['menu_gnb'] == $item['id']) {
            $xLi->addClass("active");
        }
        */

        ## 메뉴 트리객체를 생성합니다.
        return (new \Jiny\Menu\MenuBuilder($jsondata))->make()->addClass("sidebar-nav");

        $html = $this->menu($jsondata);
        $html->addClass("sidebar-nav");

        return $html;
    }


    private function menu($data)
    {
        $tag = new \Jiny\Html\CTag("ul",true);
        foreach ($data as $item) {
            $xLi = $this->menuItem($item);
            $tag->addItem($xLi);
        }

        return $tag;
    }

    private function menuItem($item)
    {
        $xLi = new \Jiny\Html\CTag("li",true);

            if (isset($item['header']) && $item['header']) {
                $xLi->addClass("sidebar-header");
                $xLi->addItem($item['title']);

            } else {
                $xLi->addClass("sidebar-item");
                $link = $this->menuLink($item);


                if (isset($item['sub'])) {
                    //$link->setAttribute("wire:click","link(".$item['id'].")");
                    $xLi->addItem($link);

                    $submenu = $this->menu($item['sub'])
                    ->setAttribute('id',"menu_".$item['id'])
                    ->setAttribute('data-bs-parent',"#sidebar");
                    $submenu->addClass("sidebar-dropdown");
                    $submenu->addClass("list-unstyled");
                    $submenu->addClass("collapse");

                    $xLi->addItem($submenu);
                } else {
                    $xLi->addItem($link);

                }
            }

        return $xLi;
    }

    private function menuLink($item)
    {

        $link = new \Jiny\Html\CTag("a",true);
        $link->addClass("sidebar-link");

        $span = (new \Jiny\Html\CTag("span",true))
        //->addItem($item['id']." ")
        ->addItem($item['title']);

        $span->addClass("align-middle");

        // ->addItem(" (".session('menu').")");

        $link->addItem($span);

        if (isset($item['sub'])) {
            $link->setAttribute("data-bs-target","#menu_".$item['id']);
            $link->setAttribute("data-bs-toggle","collapse");
            $link->addClass("collapsed");
        }

        //$link->setAttribute("wire:click","link(".$item['id'].")");

        // 메뉴 아이디 설정
        $link->setAttribute("data-menu", $item['id']);

        return $link;
    }


    public function link($id)
    {
        session(['menu'=>$id]);
        //dd(session()->all());
    }

    public function render()
    {
        return view("jinymenu::builder",['this'=>$this]);
    }
}
