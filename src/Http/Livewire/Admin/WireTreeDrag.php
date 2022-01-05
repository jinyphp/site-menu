<?php

namespace Jiny\Menu\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class WireTreeDrag extends Component
{
    public $actions = [];
    public $form=[];
    public $menu_id;
    public $tree;

    public function render()
    {
        $code = DB::table('menus')->where('id',$this->menu_id)->first();
        if ($code) {
            $rows = $this->dbFetch($this->actions);

            $tree = $this->toTree($rows); //전처리
            $this->tree = $tree;

            return view($this->actions['view_list'])
                ->with([
                    'code'=>$code,
                    'tree'=>$this->tree
                ]);
        }

        return <<<'blade'
        <div class="alert alert-danger">
            존재하지 않는 메뉴코드 입니다.
        </div>
    blade;
    }

    private function dbFetch($actions)
    {
        $rows = DB::table($this->actions['table'])
            ->where('menu_id', $this->menu_id)
            ->orderBy('level',"asc")
            ->orderBy('pos',"asc")
            ->get();
        return $rows;
    }

    private function toTree($rows)
    {
        // 배열변환
        $tree = [];

        foreach ($rows as $row) {
            $id = $row->id;
            foreach ($row as $key => $value) {
                $tree[$id][$key] = $value;
            }
        }

        // 계층이동
        foreach($tree as $i => $item) {
            if($item['level'] != 1) {
                $ref = $item['ref'];
                $tree[$ref]['sub'] []= $tree[$i];
                unset($tree[$i]);
            }
        }

        return $tree;
        return $this->sortByPos($tree);
    }

    private function sortByPos($items)
    {
        $tree = [];
        $pos = 1;
        foreach($items as $item) {
            if(isset($item['pos'])) {
                $pos = $item['pos'];
            } else {
                $pos++;
            }

            if(isset($item['sub'])) {
                $item['sub'] = $this->sortByPos($item['sub']);
            }
            $tree[$pos] = $item;
        }
        return $tree;
    }

    protected $listeners = ['refeshTable', 'encodeToJson'];
    public function refeshTable()
    {
        ## 페이지를 재갱신 합니다.
    }


    public function encodeToJson()
    {
        $path = resource_path('menus');
        if(!is_dir($path)) {
            mkdir($path,755,true);
        }

        $json = json_encode($this->tree,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($path.DIRECTORY_SEPARATOR.$this->menu_id.".json", $json);
    }



    public $drag = false;
    public function updateTaskOrder($items)
    {
        foreach($items as $item) {
            DB::table($this->actions['table'])
            ->where('id', $item['value'])->update(['pos'=>$item['order']]);
        }
    }

    public function sort_up($id)
    {
        $row = DB::table($this->actions['table'])->find($id);

        $target = DB::table($this->actions['table'])
            ->where('menu_id',$this->menu_id)
            ->where('ref',$row->ref)
            ->where('pos',"<",$row->pos)
            ->orderBy('pos',"desc")->first();

        if($target) {
            DB::table($this->actions['table'])->where('id',$id)->update(['pos'=>$target->pos]);
            DB::table($this->actions['table'])->where('id',$target->id)->update(['pos'=>$row->pos]);
        }
    }

    public function sort_down($id)
    {
        $row = DB::table($this->actions['table'])->find($id);

        $target = DB::table($this->actions['table'])
            ->where('menu_id',$this->menu_id)
            ->where('ref',$row->ref)
            ->where('pos',">",$row->pos)
            ->orderBy('pos',"asc")->first();

        if($target) {
            DB::table($this->actions['table'])->where('id',$id)->update(['pos'=>$target->pos]);
            DB::table($this->actions['table'])->where('id',$target->id)->update(['pos'=>$row->pos]);
        }
    }


}