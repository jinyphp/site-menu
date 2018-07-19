<?php
namespace Jiny\Menu;

use \Jiny\Core\Registry;

class Menu
{
    private $Application;
    public $_tree;

    public function __construct()
    {        
        // 메뉴 json 데이터를 읽어 옵니다.
        $type = conf("ENV.Resource.menu.type");
        switch ($type) {
            case 'php':
            $this->loadData();
                break;

            case 'json':
            default:
            $this->jsonFile();
        }
        //         
    }

    public function loadData()
    { 
        $filename = $this->filename();
        $this->_tree = include($filename);
        return $this;
    }

    public function jsonFile()
    {
        $filename = $this->filename();
        $this->_tree = json_decode(file_get_contents($filename), TRUE);
        return $this;
    }

    public function filename()
    {
        $path = ROOT.conf("ENV.Resource.menu.path");
        $file = conf("ENV.Resource.menu.file");
        return str_replace("/", DS, $path.DS.$file);
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



    /**
     * 
     */ 

}