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

    /**
     * 
     */

}