<?php
/*
 * jinyPHP
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Menu\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 *  Admin Page
 *  선택한 메뉴코드의 아이템을 관리합니다.
 */
use Jiny\Table\Http\Controllers\ResourceController;
class MenuItemController extends ResourceController
{
    const MENU_PATH = "menus";
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);

        ## 테이블 정보
        $this->actions['table'] = "menu_items";

        // 메인화면을 재지정합니다.
        $this->actions['view_main'] = "jinymenu::admin.menu_item.main";

        //$this->actions['view_title'] = "jinymenu::admin.menu_code.title";
        //$this->actions['view_filter'] = "jinymenu::admin.menu_code.filter";
        $this->actions['view_list'] = "jinymenu::admin.menu_item.tree";
        $this->actions['view_form'] = "jinymenu::admin.menu_item.form";

    }

    // index 오버라이딩,
    // 목록코드가 없는 경우 접속을 제한합니다.
    public function index(Request $request)
    {
        $code = DB::table('menus')->where('id',$request->id)->first();
        if ($code) {
            return parent::index($request);
        } else {
            return "존재하지 않는 메뉴 코드 입니다.";
        }
    }

    public function hookCreating($wire, $value)
    {

    }

}
