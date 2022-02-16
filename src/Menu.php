<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Menu;

/**
 *  메뉴 헬퍼 클래스
 *  메뉴트리를 생성을 처리할 수 있는 Loader
 */
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
    public $tree;
    public $menu_id;

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    // json 파일 읽기
    public function load()
    {
        if($this->path) {

            // 메뉴 리소스 읽기
            $file = resource_path($this->path);
            if(file_exists($file)) {
                $json = file_get_contents($file);
                $this->tree = json_decode($json, true);
            } else {
                $this->tree = [];
            }

            // 메뉴 code id 확인
            $temp = explode(DIRECTORY_SEPARATOR, $this->path);
            $t = array_key_last($temp);
            $this->menu_id = str_replace(".json", "", $temp[$t]);
            //dd($this->menu_id);

        } else {
            // 경로가 없는 경우 빈 메뉴 배열을 반환합니다.
            $this->tree = [];
        }

        return $this;
    }

    // Html UI tree를 생성합니다.
    // 인자로 생성되는 알고리즘을 선택합니다.
    public function build($algo="Bootstrap")
    {
        $nameSpace = "\Jiny\Menu\Builder\\".$algo;
        if (!empty($this->tree)) {
            $obj = new $nameSpace ();
            $obj->setData($this->tree);
            $obj->menu_id = $this->menu_id;
            return $obj->make()->addClass("sidebar-nav");
        }
    }

}
