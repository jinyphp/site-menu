<?php
/**
 * json menu tree 생성하는 컴포넌트
 */
namespace Jiny\Menu\View\Components;

use Illuminate\View\Component;

class Context extends Component
{
    public function render()
    {
        return view('jinymenu::components.context');
    }
}
