<?php

namespace Jiny\Menu\Http\Livewire\Admin;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;

class MenuCodeWire extends Component
{
    //public $menu=[];
    public $forms=[];


    public function mount()
    {

    }

    public function render()
    {
        $menus = Menus::all();

        return view("jinymenu::admin.menu.codeWire")->with(['menus'=>$menus]);
    }


    public $popup = false;
    public function popupNew()
    {
        $this->forms = []; ## 데이터 초기화
        $this->popup = true;
    }

    public function popupClose()
    {
        $this->popup = false;
    }

    public function popupNewSubmit()
    {
        $menu = new Menus();
        foreach ($this->forms as $key => $value) {
            $menu->$key = $value;
        }
        /*
        $menu->code = $this->forms['code'];
        $menu->description = $this->forms['description'];
        */
        $menu->save();

        $this->popup = false;
    }

    public function popupEdit($id)
    {
        $menu = Menus::find($id);
        foreach ($menu as $key => $value) {
            $this->forms[$key] = $menu->$key;
        }
        /*
        $this->forms['id'] = $menu->id;
        $this->forms['code'] = $menu->code;
        $this->forms['description'] = $menu->description;
        */

        $this->popup = true;
    }

    public function popupEditSubmit()
    {
        //dd($this->forms);
        ## 수정
        $id = $this->forms['id'];

        $menu = Menus::find($id);
        $menu->update($this->forms);

        $this->popup = false;
    }




}
