<?php
namespace Jiny\Menu;

use \Jiny\Core\Registry;

class Menu
{
    private $Application;
    public $_tree;

    public function __construct($app)
    {
        // echo __CLASS__."를 생성하였습니다.<br>";
        $this->Application = $app;
        
        // 메뉴 json 데이터를 읽어 옵니다.
        $this->loadData();
    }

    public function loadData()
    {
        //$this->_tree = \json_decode($this->jsonFile()); 
        $this->_tree = include(ROOT.DS."data".DS."menu".DS."menu.php");
        return $this;
    }

    public function jsonFile()
    {
        $filename = ROOT.DS."data".DS."menu".DS."menu.json";
        return file_get_contents($filename);
    }


    public function getTree($uri=NULL)
    {
        // echo "메뉴 트리를 값을 반환합니다.";
        if ($uri) {
            foreach ($uri as $key => $value) {

            }
        } else {
            // URI값이 없는 경우 전체반환
            // root
            return $this->_tree;
        }
    }

    public static function HTML($level=NULL)
    {
        // echo __METHOD__."<br>";

        $Menu = \Jiny\Core\Registry\Registry::get("Menu");

        $str = "<ul class=\"navbar-nav ml-auto\">";
        foreach ($Menu->_tree as $value) {
            // $list .= "<li></li>";
            $str .= "<li class='".$value->css_item."'><a class='".$value->css_link."' href='".$value->href."'>".$value->name."</a></li>";
        }
        $str .= "</ul>";
        return $str;
    }


  

}