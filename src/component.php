<?php
namespace Jiny\Menu;

use \Jiny\Core\Registry;
/**
 * 메뉴 항목구성
 */
abstract class Component
{
    private $_name;
    private $_link;

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        echo $name."을 설정합니다.<br>";
        $this->_name = $name;
    }

    public function getLink()
    {
        return $this->_link;
    }

    public function setLink($link)
    {
        $this->_link = $link;
    }

}