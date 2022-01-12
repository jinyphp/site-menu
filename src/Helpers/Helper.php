<?php
use \Jiny\Html\CTag;

function xMenu() {
    return \Jiny\Menu\Menu::instance();
}

function xMenuPath() {

}

function xMenuJson($json) {
    return (new \Jiny\Menu\MenuBuilder($json))->make()->addClass("sidebar-nav");
}


function xMenuTree($tree) {
    // 루트 ul은 패딩 0로 설정
    return (new \Jiny\Menu\Builder\Tree($tree))->make()->addClass('p-0');
}







if (!function_exists('xEnableText')) {
    function xEnableText($item, $obj)
    {
        if(is_object($item)) {
            if(isset($item->enable) && $item->enable) {
                return $obj;
            } else {
                return xSpan($obj)->style("text-decoration:line-through;");
            }
        } else {
            if(isset($item['enable']) && $item['enable']) {
                return $obj;
            } else {
                return xSpan($obj)->style("text-decoration:line-through;");
            }
        }
    }
}


