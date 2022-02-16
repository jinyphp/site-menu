<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Menu\Builder;
use Jiny\UI\View\Components\Icon;
use \Jiny\Html\CTag;
/**
 *  탬플릿 메소드 패턴
 *  부트스트랩 스타일 메뉴 HTML UI 코드를 생성합니다.
 */
class Bootstrap extends MenuUI
{
    const ITEM = "sidebar-item";


    // 메뉴 타이틀
    public function menuHeader($value)
    {
        $obj = CMenuItem();

        if($this->admin) {
            $obj->addItem(
                xDiv()
                ->addItem($value['title'])
                ->addItem(xDiv($this->btnEditMenu($value['id']))->addClass('admin'))
                ->addClass("flex")
            ); // li item
        } else {
            //$item->addItem( $link ); //li 컨덴츠 추가
            $obj->addItem($value['title']);
        }

        $obj->addClass("sidebar-header"); //bootstrap

        return $obj;
    }


    // 메뉴 아이템
    public function menuItem($value)
    {
        //서브메뉴 존재
        if(isset($value['sub'])) {
            // 서브 메뉴출력
            $open = $this->checkCollapseStatus($value);
            $item = $this->collapseMenu($value, $open);
        } else {
            // 단일 항목
            $item = $this->singleMenu($value);
        }

        $item->setAttribute('data-id', $value['id']);
        $item->setAttribute('data-ref', $value['ref']);

        $item->addClass("sidebar-item");

        return $item;
    }

    // 항목, active, collapse 클릭 대상
    private function sidebarItem($value)
    {
        $item = new CTag('div',true);

        return $item;
    }

    private function sidebarLink($value)
    {

    }


    private function checkCollapseStatus($value)
    {
        // 쿠기 메뉴 collapse 상태 체크
        if(isset($_COOKIE['__menu_collapse'])) {
            $collapse = json_decode($_COOKIE['__menu_collapse']);
            foreach($collapse as $item) {
                if($item->id == $value['id']) {
                    // 펼침 메뉴출력
                    return true;
                }
            }
        }
        return false;
    }

    private function checkActive($value)
    {
        if(isset($this->active['id'])) {
            if($this->active['id'] == $value['id']) {
                return true;
            }
        }
        return false;
    }

    /** ----- ----- ----- ----- -----
     *  단일 메뉴항목
     */
    private function singleMenu($value)
    {
        // 아이템
        $item = CMenuItem();
        $link = $this->menuLink($value);


        if ($this->admin) {
            $adminFlex = xDiv()
                ->addItem($link)
                ->addItem(
                    xDiv()
                    ->addItem($this->btnSubMenu($value['id'])) // 추가버튼
                    ->addItem($this->btnEditMenu($value['id'])) // 수정버튼
                    ->addClass('admin')
                )
                ->addClass("flex");

            $item->addItem(
                $adminFlex //->addClass("menu-item")
            ); // li item
        } else {
            $item->addItem(
                xDiv()
                ->addItem($link)
                //->addClass("menu-item")
            ); // li item
        }


        // 쿠키, 활성 선택값 체크
        if($this->checkActive($value) ) {
            //dump($value);
            $item->addClass("active");
        } else
        // 기본값 선택처리
        {
            if(isset($value['selected']) && $value['selected'] == true) {
                $item->addClass("active");
            }
        }

        return $item;
    }

    // 메뉴 링크
    private function menuLink($value)
    {
        $link = new \Jiny\Html\CLink();
        $link->addClass("sidebar-link"); //bootstrap

        // 링크값 설정
        if(isset($value['href']) && $value['href']) {
            $link->setUrl($value['href']);
        }

        // 대상타켓 설정
        if(isset($value['target']) && $value['target']) {
            $link->setUrl($value['target']);
        }

        // 아이콘 추가
        if(isset($value['icon']) && $value['icon'] ) {
            $icon = $this->menuIcon($value['icon']);
            $link->addItem($icon);
        }

        // 타이틀 추가
        if(isset($value['title'])) {
            $title = $this->spanTitle($value['title']);
            $link->addItem( $title );
        }

        // 메뉴 id 설정
        $link->setAttribute("data-menu", $value['id']);

        return $link;
    }

    private function spanTitle($title)
    {
        return CSpan($title)->addClass("align-middle");
    }


    /** ----- ----- ----- ----- -----
     * 서브메뉴 항목
     */
    public $collapse;
    public function collapseMenu($value, $open=false)
    {
        // collapse id 생성
        $this->collapse = uniqid("collapse_");

        // li 테그
        $item = CMenuItem();
        $item->addClass("submenu");

        // 서브메뉴 링크생성
        $link = $this->menuLink($value);

        $collapseBox = xDiv();
        if($this->admin) {
            $collapseBox->addItem(
                xDiv()
                ->addItem($link)
                ->addItem(xDiv($this->btnEditMenu($value['id']))->addClass('admin'))
                ->addClass("flex")
            ); // li item
        } else {
            $collapseBox->addItem( $link ); //li 컨덴츠 추가
        }

        //li에 토클, collapse 속성 추가
        //$item->addClass("menu-collapse"); // jiny 서브메뉴 체크용

        if($open) {
            // 열린상태 true
            $collapseBox->setAttribute("aria-expanded","true");
        } else {
            // 닫혀진 상태
            $collapseBox->addClass("collapsed");
            $collapseBox->setAttribute("aria-expanded","false");
        }

        $collapseBox->setAttribute("data-bs-toggle","collapse");
        $collapseBox->setAttribute('href', "#".$this->collapse);

        $collapseBox->setAttribute("role","button");
        $collapseBox->setAttribute("aria-controls",$this->collapse);

        //$collapseBox->addClass("menu-item");


        $item->addItem($collapseBox);


        // 서브메뉴 트리 추가
        $submenu = $this->collapseContent($value['sub'], $open);
        if(isset($submenu->setActive) && $submenu->setActive) {
            // 하위 메뉴에서 Active 선택됨.
            // Tree 에도 적용함
            $item->addClass("active");
        } else {
            // 트리만 선택할 경우, Active 확인
            if($this->checkActive($value) ) {
                $item->addClass("active");
            }
        }

        $item->addItem($submenu);

        return $item;
    }

    public function collapseContent($items, $open=false)
    {
        $content = CMenu();

        // bootstrap collapse 속성
        $content->addClass("sidebar-dropdown");
        $content->addClass("list-unstyled");
        $content->addClass("collapse");

        if($open) {
            $content->addClass("show"); //열린상태
        }

        $content->setAttribute("id",$this->collapse);
        $this->collapse = null; // 재초기화...

        foreach ($items as $item) {
            $menu = $this->menuItem($item);
            if($menu->isClass('active')) {
                $content->setActive = true;
            }

            $content->addItem($menu);
        }

        return $content;
    }


    public function menuIcon($icon=null)
    {
        return null;
        return (new Icon($icon));
    }


    /** ----- ----- ----- ----- -----
     *  ui admin edit
     */
    public $admin = false;
    private function btnSubMenu($ref)
    {
        $_a = new CTag('a',true);
        $icon_plus = xIcon($name="plus-circle-dotted", $type="bootstrap")->setClass("w-1 h-1");

        $data = ['ref'=>$ref, 'menu_id'=>$this->menu_id];

        $create = (clone $_a)
            ->addItem( $icon_plus )
            //->setAttribute('wire:click',"create(".$ref.")");
            ->setAttribute('href',"/admin/easy/menu/".$this->menu_id."/items/create?ref=".$ref);

        $create->addClass("btn-create");
        return $create;
    }

    private function btnEditMenu($id)
    {
        $_a = new CTag('a',true);
        $icon_gear = xIcon($name="gear", $type="bootstrap")->setClass("w-2 h-2");

        $edit = (clone $_a)
            ->addItem( $icon_gear )
            //->setAttribute('wire:click',"$"."emit('popupFormEdit','".$id."')");
            ->setAttribute('href',"/admin/easy/menu/".$this->menu_id.'/items/'.$id."/edit");
        $edit->addClass("btn-create");
        return $edit;
    }

}
