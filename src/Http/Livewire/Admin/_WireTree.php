<?php

namespace Jiny\Menu\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class WireTree extends Component
{
    public $actions = [];
    public $forms=[];
    public $menu_id;
    public $tree;

    public function render()
    {
        $code = DB::table('menus')->orderBy('id',"desc")->get();
        $rows = $this->dbFetch($this->actions);

        $tree = $this->toTree($rows); //전처리
        $this->tree = $tree;


        return view($this->actions['view_list'])
            ->with([
                'code'=>$code,
                'tree'=>$this->tree
            ]);
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

        return $this->sortByPos($tree);
    }

    private function sortByPos($items)
    {
        $tree = [];
        foreach($items as $item) {
            $pos = $item['pos'];
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

        //dd($items);
    }




}
