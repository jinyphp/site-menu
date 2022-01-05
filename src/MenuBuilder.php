<?php
/**
 * 메뉴 HTML 코드를 생성합니다.
 */
namespace Jiny\Menu;

use Jiny\UI\View\Components\Icon;

class MenuBuilder
{
    const HEADER = "sidebar-header";
    const ITEM = "sidebar-item";

    public $menu; //json-menu-data

    public function __construct($data=null)
    {
        if ($data) $this->menu = $data;
    }

    public function setData($data)
    {
        $this->menu = $data;
        return $this;
    }

    public function make($slot=null)
    {
        //Json Array Parsing
        $tree = $this->tree($this->menu);

        if($slot) {
            // 추가 컨덴츠가 있는 경우, 덧부침
            $tree->addHtml($slot);
        }

        // menu ul테그 반환
        return $tree;
    }

    // 재귀호출 메소드
    private function tree($data = [])
    {
        $menu = CMenu();
        foreach($data as $key => $value) {
            if(isset($value['header'])) {
                $item = $this->menuHeader($value)->addClass(self::HEADER);//"sidebar-header");
            } else {
                $item = $this->menuItem($value)->addClass(self::ITEM);//"sidebar-item");
            }
            $menu->add($item);
        }
        return $menu;
    }


    // 메뉴 타이틀
    public function menuHeader($value)
    {
        return CMenuItem()->addItem($value['header']);
    }


    // 메뉴 아이콘
    public function menuItem($value)
    {
        //서브메뉴 재귀호출
        if(isset($value['sub'])) {
            return $this->menuSub($value);
        }

        // 아이템
        $link = $this->itemLink($value);
        if(isset($value['icon']) && $value['icon'] ) {
            $icon = $this->menuIcon($value['icon']);
            $link->addItem($icon);
        }
        if(isset($value['title'])) {
            $link->addItem( $this->spanTitle($value['title']) );
        } else {
        }


        $item = CMenuItem()->addItem( $link );

        // 선택처리
        if(isset($value['selected']) && $value['selected'] == true) {
            $item->addClass("active");
        }

        return $item;
    }


    public function menuSub($value)
    {
        // collapse id 생성
        $this->collapse = uniqid("collpase_");

        $link = $this->collpaseLink();
        if(isset($value['icon']) && $value['icon'] ) {
            $icon = $this->menuIcon($value['icon']);
            $link->addItem($icon);
        }
        $link->addItem( $this->spanTitle($value['title']) );

        $submenu = $this->collpaseContent($value['sub']);

        return CMenuItem($link)->addItem($submenu);
    }


    // 메뉴 링크
    private function itemLink($value)
    {
        $link = CLink()->addClass("sidebar-link");
        if(isset($value['href']) && $value['href']) $link->setUrl($value['href']);
        if(isset($value['target']) && $value['target']) $link->setUrl($value['target']);

        $link->setAttribute("data-menu",$value['id']);

        return $link;
    }

    private function spanTitle($title)
    {
        return CSpan($title)->addClass("align-middle");
    }


    public function collpaseLink($title=null)
    {
        return CLink($title)->addClass("sidebar-link")->addClass("collapsed")
            ->setAttribute("data-bs-toggle","collapse")
            ->setUrl("#".$this->collapse)
            ->setAttribute("role","button")
            ->setAttribute("aria-expanded","false")
            ->setAttribute("aria-controls",$this->collapse);
    }


    public function collpaseContent($item)
    {
        $content = CMenu()
            ->addClass("sidebar-dropdown")->addClass("list-unstyled")->addClass("collapse")
            ->setAttribute("id",$this->collapse);

        foreach ($item as $value) {
            $content->addItem($this->menuItem($value));
        }

        return $content;
    }


    public function menuIcon($icon=null)
    {
        return (new Icon($icon));
    }

}
