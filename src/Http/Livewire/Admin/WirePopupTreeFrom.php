<?php

namespace Jiny\Menu\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\MenuItems;

use Jiny\Table\Http\Livewire\PopupForm;
class WirePopupTreeFrom extends PopupForm
{
    public $menu_id;

    public function create($ref=null)
    {
        $this->form['menu_id'] = intval($this->menu_id);

        if($ref) {
            ## 서브트리 참조
            $this->form['ref'] = intval($ref);
        } else {
            ## 루트
            $this->form['ref'] = 0;
        }

        return parent::create();
    }

    public function store()
    {
        if($this->form['ref'] !=0) {
            $ref = DB::table($this->actions['table'])->find($this->form['ref']);
            $this->form['level'] = $ref->level + 1;
            $this->form['pos'] = $ref->pos + 1;

            $this->increasePositionAll($this->form['pos'], ['menu_id'=>$this->menu_id]);

        } else {
            $this->form['ref'] = 0;
            $this->form['level'] = 1;
            $this->form['pos'] = DB::table($this->actions['table'])->count('pos')+1;
        }

        return parent::store();
    }

    public function increasePositionAll($pos, $where=[])
    {
        $db = DB::table($this->actions['table']);
        foreach($where as $key => $value) {
            $db->where($key,$value);
        }

        $db->where('pos','>=',$pos)->increment('pos');
    }
}
