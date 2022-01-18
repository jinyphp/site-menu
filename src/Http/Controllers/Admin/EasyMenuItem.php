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
class EasyMenuItem extends ResourceController
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
        $this->actions['view_form'] = "jinymenu::admin.menu_item.create";

    }

    // index 오버라이딩,
    // 목록코드가 없는 경우 접속을 제한합니다.
    public function index(Request $request)
    {
        $menu_id = $request->menu_id;
        $code = DB::table('menus')->where('id',$menu_id)->first();
        if ($code) {
            return parent::index($request);
        } else {
            return $menu_id." 존재하지 않는 메뉴 코드 입니다.";
        }
    }


    /** ----- ----- ----- ----- -----
     * Easy Create 오버라이딩 및 후킹
     */
    public function create(Request $request)
    {
        return parent::create($request);
    }

    public function hookCreating($wire, $value)
    {
        //dd($wire->request());
        $req = $wire->request();
        if(isset($req['query']['ref'])) {
            $wire->forms['ref'] = $req['query']['ref'];
        }

        $wire->forms['menu_id'] = $wire->actions['nesteds']['menu_id'];
        //dd($wire->forms);
    }

    public function hookStoring($wire,$forms)
    {
        $forms['pos'] = $this->maxPos($forms['menu_id']);
        $ref = $this->refRow($forms['ref']);
        $forms['level'] = $ref->level + 1;

        return $forms;
    }

    private function refRow($ref)
    {
        //참조하는 상위 데이터를 읽어옵니다.
        return DB::table($this->actions['table'])
            ->find($ref);
    }

    private function maxPos($menu_id)
    {
        // 선택한 메뉴의 최대 아이템값
        $pos = DB::table($this->actions['table'])
        ->where('menu_id',$menu_id)
        ->count('pos')+1;

        return $pos;
    }

    /** ----- ----- ----- ----- -----
     * Easy store
     */
    public function store(Request $request)
    {
        $forms = [];
        foreach($request->request as $key => $item) {
            $forms[$key] = $item;
        }



        return '<script type="text/javascript">history.go(-2);</script>';
    }

}
