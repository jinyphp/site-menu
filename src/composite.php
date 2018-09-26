<?php
namespace Jiny\Menu;

use \Jiny\Core\Registry;
/**
 * 디렉토리 설정
 */
class Composite extends Component
{
    public $_children;

    public function __construct($name)
    {
        echo __CLASS__."가 생성이 되었습니다.<br>";
        $this->setName($name);

        $this->ko = $name;
    }

    public function addNode(component $folder)
    {
        // 배열 원소 가합니다.
        $name = $folder->getName();
        echo "폴더 ".$name."를 추가합니다.<br>";
        $this->_children[$name] = $folder;
    }

    public function removeNode($component)
    {
        // 배열 원소를 제거합니다.
    }

    /**
     * 
     */

}