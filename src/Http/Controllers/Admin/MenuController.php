<?php

namespace Jiny\Menu\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 *  Admin Page
 *  메뉴 목록을 관리합니다.
 */
use Jiny\Table\Http\Controllers\ResourceController;
class MenuController extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);

        /*
        ## 테이블 정보
        $this->actions['table'] = "menus";

        //$this->actions['view_title'] = "jinymenu::admin.menu_code.title";
        $this->actions['view_filter'] = "jinymenu::admin.menu_code.filter";
        $this->actions['view_list'] = "jinymenu::admin.menu_code.list";
        $this->actions['view_form'] = "jinymenu::admin.menu_code.form";
        */
    }


    public function hookDeleting($row)
    {
        // 메뉴 아이템을 같이 삭제합니다.
        DB::table("menu_items")->where('menu_id', $row->id)->delete();
        return $row;
    }

}
