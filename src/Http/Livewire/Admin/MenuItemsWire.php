<?php

namespace Jiny\Menu\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\MenuItems;

use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class MenuItemsWire extends Component
{
    use WithFileUploads;

    public $form=[];
    public $menu_id;
    public $tree1;

    public function mount()
    {

    }

    public function render()
    {
        $code = DB::table('menus')->orderBy('id',"desc")->get();

        $rows = DB::table('menu_items')
            ->where('menu_id', $this->menu_id)
            ->orderBy('level',"desc")->get();

        $tree = $this->toTree($rows); //전처리
        $this->tree1 = $tree;

        return view("jinymenu::admin.menu.itemsWire")
            ->with([
                'code'=>$code,
                'tree'=>$tree
            ]);
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

        return $tree;
    }

    public function encodeToJson()
    {
        $path = resource_path('menus');
        if(!is_dir($path)) {
            mkdir($path,755,true);
        }

        $json = json_encode($this->tree1,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($path.DIRECTORY_SEPARATOR.$this->menu_id.".json", $json);
    }

    /**
     * 파일 업로드
     */
    public $filename;
    public function fileUpload()
    {
        $validatedData = $this->validate([
            'filename'=> 'required'
        ]);


        $filename = $this->filename->store('menus','public');

        $validatedData['filename'] = $filename;
        ## Upload::create($validatedData);

        if($this->menu_id) {
            $this->decodeTo($filename);
            session()->flash('message', "file Successfully uploaded!");
        } else {
            session()->flash('message', "메뉴코드가 지정되어 있지 않습니다!");
        }

        ## 파일 삭제
        Storage::disk('public')->delete($filename);
    }

    private function decodeTo($filename)
    {
        $path = storage_path('app/public');
        //dd($path.DIRECTORY_SEPARATOR.$filename);
        $json = file_get_contents($path.DIRECTORY_SEPARATOR.$filename);
        $rows = json_decode($json,true);

        $maxid = DB::table('menu_items')
            ->max('id');
        $this->treeToRows($rows, $maxid);

        //dd($this->rows);


    }

    private $rows = [];
    private function treeToRows($rows, $maxid)
    {


        $db = DB::table('menu_items');
        foreach($rows as &$item) {
            $item['id'] += $maxid;

            ## 루트가 아닌경우
            if($item['ref'] != 0) {
                $item['ref'] += $maxid;
            }

            $item['menu_id'] = $this->menu_id;

            if(isset($item['sub'])) {
                $this->treeToRows($item['sub'], $maxid);
                unset($item['sub']);
            }


            //unset($item['created_at']);
            //unset($item['updated_at']);
            unset($item['user_id']);

            //$this->rows []= $item;
            $db->insert($item);
        }
    }


    public function reorder($orderIds)
    {
        //dd($orderIds);
    }


    /**
     * PopUp 데이터 수정
     */
    public $popup = false;

    public function popupNew($ref=null)
    {
        ## 새로운 데이터 삽입

        $this->form = []; ## 데이터 초기화
        $this->form['menu_id'] = $this->menu_id;

        if($ref) {
            ## 서브트리 참조
            $this->form['ref'] = $ref;
        } else {
            ## 루트
            $this->form['ref'] = 0;
        }

        $this->popup = true;
    }

    public function popupNewSubmit()
    {
        ## 루트등록: ref=0
        ## 서브레벨 삽입
        $menu = new MenuItems();
        $menu->title = $this->form['title'];
        $menu->menu_id = $this->form['menu_id'];

        if($this->form['ref'] !=0) {
            //
            $ref = MenuItems::find($this->form['ref']);

            $menu->ref = $this->form['ref'];
            $menu->level = $ref->level + 1;
            $menu->pos = $ref->pos + 1;

            DB::table('menu_items')
                ->where('menu_id','=',$this->menu_id)
                ->where('pos','>=',$menu->pos)->increment('pos');

        } else {
            $menu->ref = 0;
            $menu->level = 1;
            $menu->pos = DB::table('menu_items')->count('pos')+1;
        }

        $menu->save();
        $this->popup = false;
    }

    public function popupRef($ref)
    {
        $this->form = [];
        $this->form['ref'] = $ref;
        $this->form['menu_id'] = $this->menu_id;
        $this->popup = true;
    }

    public function popupEdit($id)
    {
        ## 수정할 수 있는 폼을 출력합니다.
        ## 수정을 위한 데이터를 설정합니다.
        $menu = MenuItems::find($id);

        $this->form['title'] = $menu->title;

        $this->form['id'] = $menu->id;
        $this->form['ref'] = $menu->ref;

        $this->popup = true;
    }

    /**
     * 팝업: 데이터베이스에서 실제적인 데이터를 수정합니다.
     */
    public function popupEditSubmit()
    {
        ## 수정
        $id = $this->form['id'];
        $menu = MenuItems::find($id);
        $menu->update($this->form);

        $this->popup = false;
    }

    public function popupClose()
    {
        $this->popup = false;
    }

    public function popupDelete()
    {

        $id = $this->form['id'];
        $menu = MenuItems::find($id);
        $menu->delete();

        $this->popup = false;
    }


}
