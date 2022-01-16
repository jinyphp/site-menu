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
class Context extends Component
{
    public function render()
    {
        return view('jinymenu::components.context');
    }
}
