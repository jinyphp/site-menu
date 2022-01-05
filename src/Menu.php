<?php
/**
 * 메뉴 생성을 처리할 수 있는 Loader
 */
namespace Jiny\Menu;

class Menu
{
    public $path;
    public $tree;
    public $menu_id;

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

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
            $this->tree = [];
        }

        return $this;
    }

    public function build()
    {
        if (!empty($this->tree)) {
            return (new \Jiny\Menu\MenuBuilder($this->tree))->make()->addClass("sidebar-nav");
        }
    }


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
}
