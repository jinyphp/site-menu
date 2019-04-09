<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Menu;

use \Jiny\Core\Registry;

class Menu
{
    public $_tree;

    // 메뉴 리소스 경로가 저장되어 있습니다.
    public $_path;
    public $_type;

    public function __construct($path=NULL)
    {        
        if ($path) {
            $this->_path = $path;
        } else {
            // 기본값 설정
            $_ = "..".DS."..".DS."..".DS."..";
            $this->_path = __DIR__.DS.$_.conf("site.menu_path");
            
         
            $this->_type = conf("site.menu_type");
        }
    }


    /**
     * 메뉴의 리소스 페스를 설정합니다.
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }


    /**
     * 메뉴의 리소스 페스를 읽어합니다.
     */
    public function getPath()
    {
        return $this->_path;
    }


    /**
     * 사이트 환경설정 파일의 메뉴를 읽어 옵니다.
     */
    public function filename()
    {
        return str_replace("/", DIRECTORY_SEPARATOR, $this->_path);
    }


    /**
     * 메뉴타입 : 사이트 환경설정의 값을 반환합니다.
     */
    public function getType()
    {
        return $this->_type;
    }


    /**
     *  메뉴타입 설정
     */
    public function setType($type)
    {
        $this->_type = $type;
    }


    /**
     * 메뉴 배열트리를 반환합니다.
     */
    public function getTree($uri=NULL)
    {
        // 플라이웨이트 패턴
        // 데이터로드를 동적으로 처리합니다.
        if(empty($this->_tree)) {
            // 팩토리패턴으로 객체를 생성하여
            // 전략 패턴으로 드라이버를 전달합니다.
            $strategy = $this->driver();
            $this->_tree = $this->load($strategy);

            // 메뉴 확장 검사
            $this->_tree = $this->extend($this->_tree);
            //echo "<pre>";
            //print_r($this->_tree);
            //echo "</pre>";
        }

        return $this->_tree;
    }

    /**
     * 메뉴 확장을 처리합니다.
     */
    public function extend($menus)
    {
        // 확장검사
        for ($i=0; $i<count($menus); $i++) {

            //확장 처리
            if (isset($menus[$i]['extend'])) {

                // 메뉴타입 검사, 확장자 확인
                // 메뉴타입에 대한 드라이버 
                $path_parts = pathinfo($menus[$i]['extend']);
                $strategy = $this->driver($path_parts['extension']);

                // 메뉴를 읽어 재귀결합
                $tree = $strategy->load($menus[$i]['extend']);
                $menus[$i]['menu'] = $this->extend($tree);
            }
        }
        return $menus;
    }

    /**
     * 데이터를 읽어 옵니다.
     */
    public function load($strategy)
    { 
        $filename = $this->filename();
        return $strategy->load($filename);
    }


    /**
     * 메뉴 드라이버: 팩토리 생성패턴입니다.
     */
    public function driver($type=null)
    {
        // 설정의 상태값을 읽어 옵니다.
        if(!$type) $type = $this->getType();

        // 타입을 소문자로 변경, 클래스명을 일치합니다.
        $factory = "\Jiny\Menu\Drivers\\".strtolower( $type );

        // 전략패턴으로 객체 로드
        // 알고리즘 클래스 적용
        return new $factory;
    }

    /**
     * 
     */
}