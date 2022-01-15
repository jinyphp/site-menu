<?php
/**
 * 메뉴 HTML UI 코드를 생성합니다.
 */
namespace Jiny\Menu\Builder;

abstract class MenuUI
{


    public $menu;
    public function __construct($data=null)
    {
        // 메뉴 데이터를 설정합니다.
        if ($data) {
            $this->menu = $data;
        }
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
    protected function tree($data = [])
    {
        $menu = CMenu();

        foreach($data as $key => $value) {
            if(isset($value['header'])) {
                $item = $this->menuHeader($value);
            } else {
                $item = $this->menuItem($value);
            }

            $menu->add($item);
        }

        return $menu;
    }

    abstract public function menuHeader($value);
    abstract public function menuItem($value);

}
