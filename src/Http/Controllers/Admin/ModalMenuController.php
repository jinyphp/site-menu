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
 *  메뉴 목록을 관리합니다.
 */
use Jiny\Table\Http\Controllers\ResourceController;
class ModalMenuController extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);

        ## 테이블 정보
        $this->actions['table'] = "menus";

        $this->actions['view_main'] = "jinymenu::admin.modal.menu_code.main";
        $this->actions['view_filter'] = "jinymenu::admin.modal.menu_code.filter";
        $this->actions['view_list'] = "jinymenu::admin.modal.menu_code.list";
        $this->actions['view_form'] = "jinymenu::admin.modal.menu_code.create";

    }


    public function create(Request $request)
    {
        //return view("jinymenu::admin.modal.menu_code.create");
        return view($this->actions['view_form'],['actions'=>$this->actions]);
    }

    public function store(Request $request)
    {
        dump($request);
        return "aaa";
    }


    public function hookDeleting($row)
    {
        // 메뉴 아이템을 같이 삭제합니다.
        DB::table("menu_items")->where('menu_id', $row->id)->delete();
        return $row;
    }

}
