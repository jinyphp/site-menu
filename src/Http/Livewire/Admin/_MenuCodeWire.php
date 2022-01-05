<?php

namespace Jiny\Menu\Http\Livewire\Admin;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;

class MenuCodeWire extends Component
{
    //public $menu=[];
    public $form=[];


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
        $this->form = []; ## 데이터 초기화
        $this->popup = true;
    }

    public function popupClose()
    {
        $this->popup = false;
    }

    public function popupNewSubmit()
    {
        $menu = new Menus();
        foreach ($this->form as $key => $value) {
            $menu->$key = $value;
        }
        /*
        $menu->code = $this->form['code'];
        $menu->description = $this->form['description'];
        */
        $menu->save();

        $this->popup = false;
    }

    public function popupEdit($id)
    {
        $menu = Menus::find($id);
        foreach ($menu as $key => $value) {
            $this->form[$key] = $menu->$key;
        }
        /*
        $this->form['id'] = $menu->id;
        $this->form['code'] = $menu->code;
        $this->form['description'] = $menu->description;
        */

        $this->popup = true;
    }

    public function popupEditSubmit()
    {
        //dd($this->form);
        ## 수정
        $id = $this->form['id'];

        $menu = Menus::find($id);
        $menu->update($this->form);

        $this->popup = false;
    }




}
