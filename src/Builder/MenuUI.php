<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Menu\Builder;
use Illuminate\Support\Facades\Route;

/**
 * 메뉴 UI를 위한 HTML 코드를 생성합니다.
 * 템플릿 메소드로 UI 코드를 추출합니다.
 */
abstract class MenuUI
{
    public $menu;
    public $menu_id;
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
        // Active 설정
        $uri = "/".$this->detectURI();
        if( $current = $this->checkMenuUrl($this->menu, $uri) ) {
            // uri 주소가 있는 경우, 우선적용
            $this->active = [ 'id' => $current['id'] ];
        } else {
            if(isset($_COOKIE['__menu_active'])) {
                // 쿠키값이 있는 경우, 적용
                $this->active = json_decode($_COOKIE['__menu_active'],true);
            }
        }


        // menu 데이터를 기반으로 HTML Ul tree 테그를 생성합니다.
        $obj = $this->tree($this->menu);
        $obj->setAttribute('data-code',$this->menu_id);


        if($slot) {
            // 추가 컨덴츠가 있는 경우, 덧부침
            $obj->addHtml($slot);
        }

        // menu ul테그 반환
        return $obj;
    }

    ## 재귀호출 메소드
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


    public $active;
    private function checkMenuUrl($trees, $uri)
    {
        foreach($trees as $tree) {
            // uri 경로조건 체크
            if(isset($tree['href']) && $tree['href']) {
                if($tree['href'] == $uri) {
                    return $tree;
                }
            }

            // 서브메뉴 검사
            if(isset($tree['sub']) && $tree['sub']) {
                $sub = $this->checkMenuUrl($tree['sub'], $uri);
                if($sub) {
                    return $sub;
                }
            }
        }
        return false;
    }

    private function detectURI()
    {
        // 라우터에서 uri 정보 확인
        $uri = Route::current()->uri;

        // uri에서 {} 매개변수 제거
        $slug = explode('/', $uri);
        foreach($slug as $key => $item) {
            if($item[0] == "{") unset($slug[$key]);
        }

        // resource 컨트롤러에서 ~/create 는 삭제.
        $last = count($slug)-1;
        if($slug[$last] == "create") {
            unset($slug[$last]);
        }

        $slugPath = implode("/",$slug); // 다시 url 연결.

        return $slugPath;
    }

}
