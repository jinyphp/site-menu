<?php
use \Jiny\Core\Registry\Registry;

if (! function_exists('menu')) {
    /**
     * 메뉴의 객체를 생성후, 데이터를 읽어옵니다.
     */
    function menu() {
        $Menu = Registry::create(\Jiny\Menu\Menu::class,"Menu");
        return $Menu->getTree();
    }
}