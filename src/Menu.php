<?php
/**
 * 메뉴 생성을 처리할 수 있는 Loader
 */
namespace Jiny\Menu;



class Menu
{
    /**
     * 싱글턴 인스턴스를 생성합니다.
     */
    private static $Instance;
    public static function instance()
    {
        if (!isset(self::$Instance)) {
            // 자기 자신의 인스턴스를 생성합니다.
            self::$Instance = new self();

            return self::$Instance;
        } else {
            // 인스턴스가 중복
            return self::$Instance;
        }
    }

    ## 메뉴 경로를 설정합니다.
    public $path;
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public $tree;
    public $menu_id;

    // json 파일 읽기
    public function load()
    {
        if($this->path) {
            $file = resource_path($this->path);
            if(file_exists($file)) {
                $json = file_get_contents($file);
                $this->tree = json_decode($json, true);
            } else {
                $this->tree = [];
            }
        } else {
            // 경로가 없는 경우 빈 메뉴 배열을 반환합니다.
            $this->tree = [];
        }

        return $this;
    }

    // Html tree 코드를 생성합니다.
    public function build()
    {
        if (!empty($this->tree)) {

            // url 주소를 이용하여 쿠키값 변경
            /*
            $uri = "/".$this->detectURI();
            //dump($uri);
            if( $current = $this->checkMenuUrl($this->tree, $uri) ) {

                $active = [ 'id' => $current['id'] ];
                dump(json_encode($active));
                //setcookie('__menu_active', "abcd", 36000, '/');



                Cookie::queue(Cookie::make('__menu_active', json_encode($active), 36000));
                //Cookie::make('__menu_active', json_encode($active), 36000);
                //$_COOKIE['__menu_active'] == '{"id":940}';
                dd($_COOKIE['__menu_active']);

            }

            //
            */






            $obj = new \Jiny\Menu\Builder\Bootstrap();
            $obj->setData($this->tree);
            return $obj->make()->addClass("sidebar-nav");
        }
    }





}
