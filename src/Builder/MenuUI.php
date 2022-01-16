<?php
/**
 * 메뉴 HTML UI 코드를 생성합니다.
 */
namespace Jiny\Menu\Builder;
use Illuminate\Support\Facades\Route;
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
        //dd($this->menu);
        $uri = "/".$this->detectURI();
        //dump($uri);
        //dump($this->menu);
        //dump($this->checkMenuUrl($this->menu, $uri));

        if( $current = $this->checkMenuUrl($this->menu, $uri) ) {
            //dd($current);
            $this->active = [ 'id' => $current['id'] ];
        } else {
            if(isset($_COOKIE['__menu_active'])) {
                $this->active = json_decode($_COOKIE['__menu_active'],true);
            }
        }
        //dd($this->active);



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

    public $active;
    private function checkMenuUrl($trees, $uri)
    {
        foreach($trees as $tree) {
            //dump($tree);
            if(isset($tree['href']) && $tree['href']) {
                if($tree['href'] == $uri) {
                    //dd($tree);
                    return $tree;
                }
            }
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
