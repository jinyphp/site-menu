<?php
namespace Jiny\Menu\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class WidgetSubMenu extends Component
{
    use WithFileUploads;
    use \Jiny\WireTable\Http\Trait\Upload;

    public $code; // = "arduino";
    public $actions = [];
    public $widget=[]; // 위젯정보

    public $post_id;
    public $edit_id;
    public $rows = [];
    public $last_id;

    public $forms=[];

    public $popupForm = false;
    public $viewForm;
    public $viewList;
    public $viewListItem;

    public $popupDelete = false;
    public $confirm = false;


    public function mount()
    {
        $this->reply_id = 0;

        $this->viewFormFile();
        $this->viewListFile();
        $this->viewListFileItem();
    }

    public function render()
    {
        // DB에서 데이터를 읽어 옵니다.
        $rows = DB::table("menu_items")
                ->where('code',$this->code)
                ->orderBy('level',"desc")
                ->get();

        $this->rows = [];
        foreach($rows as $item) {
            $id = $item->id;
            $this->rows[$id] = get_object_vars($item); // 객체를 배열로 변환
        }

        // 트리로 변환합니다.
        $this->tree();

        // 기본값
        $viewFile = 'jiny-menu::widgets.layout';
        return view($viewFile);
    }


    private function tree()
    {
        foreach($this->rows as &$item) {
            $id = $item['id'];
            if($item['ref']) {
                $ref = $item['ref'];
                if(!isset($this->rows[$ref]['items'])) {
                    $this->rows[$ref]['items'] = [];
                }
                $this->rows[$ref]['items'] []= $item;

                unset($this->rows[$id]);
            }
        }
    }

    private function viewListFile()
    {
        $viewFile = 'jiny-menu::submenu.list';

        if(isset($this->widget['view']['list'])) {
            $viewFile = $this->widget['view']['list'];
        }

        $this->viewList = $viewFile;
        return $viewFile;
    }

    private function viewListFileItem()
    {
        $viewFile = 'jiny-menu::submenu.item';

        if(isset($this->widget['view']['item'])) {
            $viewFile = $this->widget['view']['item'];
        }

        $this->viewListItem = $viewFile;
        return $viewFile;
    }

    private function viewFormFile()
    {
        $this->viewForm = "jiny-menu::submenu.form";

        if(isset($this->widget['view']['form'])) {
            $this->viewForm = $this->widget['view']['form'];
        }

        return $this->viewForm;
    }


    protected $listeners = [
        'create','popupFormCreate',
        'edit','popupEdit','popupCreate'
    ];

    public function create($value=null)
    {
        $this->popupForm = true;
        $this->edit_id = null;

        // 데이터초기화
        $this->forms = [];
        $this->forms['code'] = $this->code;
    }

    public function store()
    {
        if($this->reply_id) {
            $this->forms['ref'] = $this->reply_id;

            $id = $this->reply_id;
            $this->forms['level'] = $this->level + 1;
        } else {
            $this->forms['ref'] = 0;
            $this->forms['level'] = 1;
        }


        // 2. 시간정보 생성
        $this->forms['created_at'] = date("Y-m-d H:i:s");
        $this->forms['updated_at'] = date("Y-m-d H:i:s");

        $form = $this->forms;

        $id = DB::table("menu_items")->insertGetId($form);
        $form['id'] = $id;
        $this->last_id = $id;

        $this->forms = []; // 초기화
        $this->reply_id = null;
        $this->level = null;

        $this->popupForm = false;
        $this->edit_id = null;
    }


    public $editmode=null;
    public function edit($id)
    {
        $this->editmode = "edit";
        $this->reply_id = $id;

        $node = $this->findNode($this->rows, $id);
        $this->forms = $node;

        $this->edit_id = $id;
        $this->popupForm = true;
    }

    public function update()
    {
        // 수정폼에서 하위메뉴가 있는경우,
        // 하위메뉴는 DB삽입이 되지 않기 때문에 삭제함
        if(isset($this->forms['items'])) {
            unset($this->forms['items']);
        }

        DB::table("menu_items")
            ->where('id',$this->reply_id)
            ->update($this->forms);

        $this->forms = [];
        $this->editmode = null;
        $this->reply_id = null;

        $this->edit_id = null;
        $this->popupForm = false;
    }

    // 수정메뉴에서, 하위 서브메뉴를 추가 생성모드로 변경
    public function submenu()
    {
        $this->level = $this->forms['level'];
        $this->edit_id = null;

        // 데이터초기화
        $this->forms = [];
        $this->forms['code'] = $this->code;
    }

    public $reply_id;
    public $level;
    public function reply($id, $level)
    {
        $this->reply_id = $id;
        $this->level = $level;

        // 데이터초기화
        $this->forms = [];
        $this->forms['code'] = $this->code;

        $this->popupForm = true;
    }



    public function delete($id=null)
    {
        $this->popupDelete = true;
    }

    public function deleteCancel()
    {
        $this->popupDelete = false;
        $this->popupForm = false;
        $this->setup = false;
    }

    public function deleteConfirm()
    {
        $id = $this->edit_id;
        $node = $this->findNode($this->rows, $id);
        $this->deleteNode($node);

        $this->popupDelete = false;
        $this->popupForm = false;
        $this->setup = false;

        //
        //$this->edit_id = null;

        // 이미지삭제
        //$this->deleteUploadFiles($this->rows[$id]);

        // 데이터삭제
        //unset($this->rows[$id]);
        //$this->dbDeleteRow($id);

        //$this->widget['items'] = $this->rows;
        //$this->phpSave($this->widget, $this->filename);
    }


    /*
    public function delete($id)
    {

        $node = $this->findNode($this->rows, $id);

        $this->deleteNode($node);
        //dd("done");
    }
    */

    private function findNode($items, $id)
    {
        foreach($items as $item) {

            if($item['id'] == $id) {
                return $item;
            }

            // 서브트리가 있는 경우, 재귀탐색
            if(isset($item['items'])) {
                $result = $this->findNode($item['items'], $id);
                if($result) { //탐색한 결과가 있으면
                    // 탐색결과를 확인
                    if($result['id'] == $id) return $result;
                }
            }

        }

        return false;
    }

    private function deleteNode($items)
    {
        if(isset($items['items'])) {

            foreach($items['items'] as $i => $leaf) {
                if(isset($leaf['items'])) {
                    $this->deleteNode($leaf['items']);
                }
                //dump("leaf");
                //dump(__LINE__);
                //($leaf);
                $id = $leaf['id'];
                $this->dbDeleteRow($id);
            }
        }

        //dump("node");
        //dump(__LINE__);
        if(isset($items['id'])) {
            $id = $items['id'];
            //dump($items);
            $this->dbDeleteRow($id);
        } else {
            if(isset($items[0]['id'])) {
                $id = $items[0]['id'];
                //dump($items[0]);
                $this->dbDeleteRow($id);
            }
        }


    }

    private function dbDeleteRow($id)
    {
        DB::table("menu_items")
            ->where('id',$id)
            ->delete();

    }

    public function cancel()
    {
        $this->forms = [];
        $this->editmode = null;
        $this->reply_id = null;
        $this->edit_id = null;
        $this->setup = false;

        $this->popupForm = false;
    }



    public $setup = false;
    public function setting()
    {
        $this->popupForm = true;
        $this->setup = true;
    }






}
