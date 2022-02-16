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
    // bootstrap class name
    //const ITEM = "sidebar-item";
    /*
    const SIDEBAR_HEADER = "sidebar-header";
    const SIDEBAR_ITEM = "sidebar-item";
    const SIDEBAR_LINK = "sidebar-link";
    const SIDEBAR_ACTIVE = "active";
    const SIDEBAR_DROPDOWN = "sidebar-dropdown";
    */

    // 메뉴 타이틀
    public function menuHeader($value)
    {
        $obj = CMenuItem();
        $obj->addItem($value['title']);
        $obj->addClass($this->css['sidebar_header']);
        return $obj;
    }


    // 메뉴 아이템
    public function menuItem($value)
    {
        $item = CMenuItem();
        $item->addClass($this->css['sidebar-item']); // li테그에 붙는 이름

        // context menu 용
        $item->setAttribute('data-id', $value['id']);
        $item->setAttribute('data-ref', $value['ref']);

        // 서브메뉴 추가
        if(isset($value['sub'])) {
            $open = $this->checkCollapseStatus($value);

            //$sidebarItem = $this->sidebarItem($value, $open);
            $menuLink = $this->menuLink($value);
            $menuLink->addClass("submenu"); // 서브메뉴 항목으로 설정표기 -> javascript에서 사용됨
            if(isset($value['sub'])) {
                $this->collapse = uniqid("collapse_".$value['id']."_");
                $menuLink->setHref("#".$this->collapse);
                //$menuLink->setAttribute('href', "#".$this->collapse);

                $menuLink->setAttribute("data-bs-toggle","collapse");

                if($open) {
                    // 열린상태 true
                    $menuLink->setAttribute("aria-expanded","true");
                } else {
                    // 닫혀진 상태
                    $item->addClass("collapsed");
                    $menuLink->setAttribute("aria-expanded","false");
                }
                $menuLink->setAttribute("role","button");
                $menuLink->setAttribute("aria-controls",$this->collapse);

            }


            // 서브메뉴 트리 추가
            $submenu = $this->collapseContent($value['sub'], $open);
            /// 서브메뉴 show가 선택된 경우, li에도 같이 적용함
            if($submenu->isClass($this->css['sidebar-show'])){
                $item->addClass($this->css['sidebar-show']);
            }

            if(isset($submenu->setActive) && $submenu->setActive) {
                // 하위 메뉴에서 Active 선택됨.
                // Tree 에도 적용함
                $menuLink->addClass($this->css['active']);
                $item->addClass($this->css['active']); // 상위트리 active 전달
            } else {
                // 트리만 선택할 경우, Active 확인
                if($this->checkActive($value) ) {
                    $menuLink->addClass($this->css['active']);
                    $item->addClass($this->css['active']); // 상위트리 active 전달
                }
            }

            $item->addItem($menuLink);
            $item->addItem($submenu);


        } else {
            // 단일 메뉴항목

            //$sidebarItem = $this->sidebarItem($value);
            $menuLink = $this->menuLink($value);

            // 링크값 설정
            if(isset($value['href']) && $value['href']) {
                $menuLink->setUrl($value['href']);
            }

            // LI테그, Active 확인
            if($this->checkActive($value) ) {
                $menuLink->addClass($this->css['active']);
                $item->addClass($this->css['active']); // 상위트리 active 전달
            }

            $item->addItem($menuLink);
        }

        return $item;
    }

    private function menuLink($value)
    {
        $link = new \Jiny\Html\CLink();
        $link->addClass($this->css['sidebar-link']);

        // 대상타켓 설정
        if(isset($value['target']) && $value['target']) {
            $link->setAttribute('target', $value['target']);
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
        $link->setAttribute("data-id", $value['id']);

        return $link;
    }


    private function spanTitle($title)
    {
        return CSpan($title)->addClass("align-middle");
    }
    public function menuIcon($icon=null)
    {
        return null;
        return (new Icon($icon));
    }



    /** ----- ----- ----- ----- -----
     * 서브메뉴 항목
     */
    public $collapse;
    public function collapseContent($items, $open=false)
    {
        $content = CMenu();

        // bootstrap collapse 속성
        $content->addClass($this->css['sidebar-dropdown']);
        $content->addClass("list-unstyled");
        $content->addClass("collapse");

        if($open) {
            $content->addClass($this->css['sidebar-show']); //열린상태
        }

        $content->setAttribute("id",$this->collapse);
        $this->collapse = null; // 재초기화...

        foreach ($items as $item) {
            $li = $this->menuItem($item);

            if($li->isClass('active')) {
                $content->setActive = true;
            }

            $content->addItem($li);
        }

        return $content;
    }

    private function checkCollapseStatus($value)
    {
        // 쿠기 메뉴 collapse 상태 체크
        if(isset($_COOKIE['__menu_collapse'])) {
            $collapse = json_decode($_COOKIE['__menu_collapse']);
            foreach($collapse as $item) {
                if(isset($item->id) && $item->id == $value['id']) {
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

}
