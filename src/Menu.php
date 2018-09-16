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

    public function __construct()
    {        
        //         
    }

    /**
     * 사이트 환경설정 파일의 메뉴를 읽어 옵니다.
     */
    public function filename()
    {
        $file = conf("site.menu_path");
        return str_replace("/", DS, $file);
    }

    /**
     * 메뉴타입 : 사이트 환경설정의 값을 반환합니다.
     */
    private function type($type=null)
    {
        return conf("site.menu_type");
    }

    /**
     * 메뉴 배열트리를 반환합니다.
     */
    public function getTree($uri=NULL)
    {
        // 플라이웨이트 패턴
        if(empty($this->_tree)) {
            $this->_tree = $this->load();
        }

        return $this->_tree;
    }

    /**
     * 데이터를 읽어 옵니다.
     */
    public function load()
    { 
        // 설정의 상태값을 읽어 옵니다.
        $type = $this->type();

        // 타입을 대문자로 변경, 클래스명을 일치합니다.
        $strategy = "\Jiny\Menu\Drivers\\".strtoupper( $type );

        // 전략패턴으로 객체 로드
        // 알고리즘 클래스 적용
        $d = new $strategy;
        $filename = $this->filename();
        return $d->load($filename);
    }

    /**
     * 
     */ 

}