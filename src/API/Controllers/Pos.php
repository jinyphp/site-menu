<?php

namespace Jiny\Menu\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use \Jiny\Html\CTag;


class Pos extends Controller
{
    public function index()
    {
        foreach($_POST['menu'] as $id => $menu) {
            DB::table('menu_items')
                ->where('id', $id)
                ->update($menu);
        }

        /*
        // 기본 테이블 그룹정보 읽기
        if(isset($_POST['target_id']) && $_POST['target_id']) {
            $row = DB::table('table_columns')->where('id', $_POST['target_id'])->first();
        } else {
            return response()->json([
                'message'=>"target_id 값이 없습니다."
            ]);
        }

        if(isset($row)) {
            // 목록순서 전체 읽기
            $cols = DB::table('table_columns')
                ->select('id','pos','title')
                ->where('uri',$row->uri)
                ->orderBy('pos',"asc")
                ->get();

            // 2개의 pos 번호 서로 교체
            $rows = [];
            for($i=0; $i<count($cols); $i++) {
                $rows[$i]['id'] = $cols[$i]->id;

                if($cols[$i]->id == $_POST['target_id']) {
                    $rows[$i]['pos'] = intval($_POST['start_pos']);
                } else if($cols[$i]->id == $_POST['start_id']) {
                    $rows[$i]['pos'] = intval($_POST['target_pos']);
                } else {
                    $rows[$i]['pos'] = $cols[$i]->pos;
                }

                $rows[$i]['title'] = $cols[$i]->title;
            }

            // 다차원 배열, pos 순서로 재정렬
            $sort = [];
            foreach ($rows as $key => $value) {
                $sort[$key] = $value['pos'];
            }
            array_multisort($sort, SORT_ASC, $rows);

            // 변경된 순서를 DB에 저장
            for($i=0; $i<count($rows);$i++) {


            }

        }
        */

        /*
        $cols = DB::table('table_columns')
            ->select('id','pos','title')
            ->where('uri',$row->uri)
            ->orderBy('pos',"asc")
            ->get();
        */

        return response()->json([
            'post'=>$_POST
        ]);
    }

}
