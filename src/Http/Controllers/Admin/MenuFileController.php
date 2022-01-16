<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Menu\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Jiny\Table\Http\Controllers\ResourceController;
class MenuFileController extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);
    }
}
