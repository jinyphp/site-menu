<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Menu\View\Components;
use Illuminate\View\Component;

/**
 * json menu tree 생성하는 컴포넌트
 */
class Menu extends Component
{
    public $jsondata = [];
    public $filename;

    public function __construct($path=null)
    {
        // path 경로를 설정합니다.
        if ($path) {
            \Jiny\Menu\Menu::instance()->setPath($path);
            $this->filename = $path;
        }

        // 메뉴 데이터를 읽어 옵니다.
        $tree = \Jiny\Menu\Menu::instance()->load()->tree;
        $this->jsondata = $tree;
    }

    ## 메뉴 ui를 빌더합니다.
    ## jinymenu::components.menu.menu 안에서 호출되는 내부 함수 입니다.
    public function builder($slot)
    {
        $content = "";
        if (!empty($this->jsondata)) {
            $content = \Jiny\Menu\Menu::instance()->build();
        }

        return $content.$slot;
    }

    public function render()
    {
        return view('jinymenu::components.menu.menu');
    }
}
