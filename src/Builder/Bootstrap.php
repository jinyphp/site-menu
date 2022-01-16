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
        $obj->addItem($value['title']);
        $obj->addClass("sidebar-header"); //bootstrap

        return $obj;
    }

    // 메뉴 아이템
    public function menuItem($value)
    {
        //서브메뉴 존재
        // collapse 재귀호출
        if(isset($value['sub'])) {
            // 쿠기 메뉴 collapse 상태 체크
            if(isset($_COOKIE['__menu_collapse'])) {
                $collapse = json_decode($_COOKIE['__menu_collapse']);
                foreach($collapse as $item) {
                    if($item->id == $value['id']) {
                        // 펼침 메뉴출력
                        return $this->collapseMenu($value, true);
                    }
                }
            }

            // 닫힘 메뉴출력
            return $this->collapseMenu($value, false);
        }

        // 단일 항목
        return $this->singleMenu($value);
    }

    /** ----- ----- ----- ----- -----
     *
     */
    private function singleMenu($value)
    {
        // 아이템
        $link = $this->menuLink($value);

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


        $item = CMenuItem()->addItem( $link );
        $item->setAttribute('data-id', $value['id']);
        $item->setAttribute('data-ref', $value['ref']);

        // 선택처리
        if(isset($value['selected']) && $value['selected'] == true) {
            $item->addClass("active");
        }

        if(isset($this->active['id'])) {
            if($this->active['id'] == $value['id']) {
                $item->addClass("active");
            }
        }

        return $item->addClass("sidebar-item");//bootstrap
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

        // 메뉴 id 설정
        $link->setAttribute("data-menu", $value['id']);

        return $link;
    }

    private function spanTitle($title)
    {
        return CSpan($title)->addClass("align-middle");
    }


    /** ----- ----- ----- ----- -----
     *
     */
    public function collapseMenu($value, $open=false)
    {
        // 서브메뉴 링크생성
        $link = $this->collapseLink($value, $open);

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

        $item = CMenuItem()->addItem( $link );
        $item->setAttribute('data-id', $value['id']);
        $item->setAttribute('data-ref', $value['ref']);
        $item->addClass("sidebar-item");

        // 서브메뉴 체크용
        $item->addClass("menu-collapse");



        // 서브메뉴 트리 추가
        $submenu = $this->collapseContent($value['sub'], $open);

        if(isset($submenu->setActive) && $submenu->setActive) {
            // 하위 메뉴에서 Active 선택됨.
            // Tree 에도 적용함
            $item->addClass("active");
        } else {
            // 트리만 선택할 경우, Active 확인
            if(isset($this->active['id'])) {
                if($this->active['id'] == $value['id']) {
                    $item->addClass("active");
                }
            }
        }

        $item->addItem($submenu);

        return $item;
    }


    public $collapse;
    public function collapseLink($value, $open=false)
    {
        // collapse id 생성
        $this->collapse = uniqid("collapse_");

        $link = new \Jiny\Html\CLink();
        $link->addClass("sidebar-link"); //bootstrap

        // collapse 버튼
        if($open) {
            // 열린상태 true
            $link->setAttribute("aria-expanded","true");
        } else {
            // 닫혀진 상태
            $link->addClass("collapsed");
            $link->setAttribute("aria-expanded","false");
        }


        $link->setAttribute("data-bs-toggle","collapse")
            ->setUrl("#".$this->collapse);

        $link->setAttribute("role","button");
        $link->setAttribute("aria-controls",$this->collapse);

        return $link;
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
}
