<?php
namespace Jiny\Menu\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

/**
 * 트리형 데이터를 이용하여 위젯을 구현합니다.
 */
class WidgetMenu extends Component
{
    use WithFileUploads;
    use \Jiny\WireTable\Http\Trait\Upload;

    use \Jiny\Widgets\Http\Trait\DesignMode;

    public $code; // = "arduino";
    public $actions = [];
    public $widget=[]; // 위젯정보

    public $post_id;
    public $edit_id;
    public $rows = [];
    public $refs = []; // 트리노트 참조
    public $max_idx = 0; //
    public $last_id;

    public $forms=[];

    public $popupForm = false;
    public $viewForm;
    public $viewList;
    public $viewListItem;

    public $popupDelete = false;
    public $confirm = false;

    public $viewFile = [];


    public function mount()
    {
        $this->reply_id = 0;

        $this->load(); //데이터를 읽어옴

        //dd($this->widget);

        $this->viewFormFile();
        $this->viewListFile();
        $this->viewListFileItem();
        $this->viewLayoutFile();

    }


    // 데이터를 읽어 옵니다.
    private function load()
    {
        $path = resource_path("/menus"); // 메뉴리소스가 저장되어 있는 경로
        if(!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if($this->code) {
            $filename = $path.DIRECTORY_SEPARATOR.$this->code.".json";
            if(file_exists($filename)) {
                $json = file_get_contents($filename);
                $widget = json_decode($json,true);
            }

            $widget['code'] = $this->code;
        }

        // 외부설정값 + 파일정보값 처리
        if($widget) {
            foreach($widget as $key => $item) {
                // 외부에서 설정한 값, 우선적용
                if(!isset($this->widget[$key])) {
                    $this->widget[$key] = $item;
                }
            }
        }

        // items 데이터 읽기
        if($this->widget) {
            if(isset($this->widget['items'])) {
                $this->refs = $this->widget['items'];
            }
        }

    }


    // 메뉴 설정값 저장
    protected function save()
    {
        $path = resource_path("/menus");
        if(!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // 1차원 메뉴 목록을 저장
        $this->widget['items'] = $this->refs;

        // 파일저장
        if($this->code) {
            $filename = $path.DIRECTORY_SEPARATOR.$this->code.".json";
            $jsonBody = json_encode($this->widget, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            file_put_contents($filename, $jsonBody);
        }
    }


    // 화면 랜더링
    public function render()
    {
        // 트리로 변환합니다.
        $this->rows = $this->tree($this->refs);

        // 기본값
        //$viewFile = 'jiny-menu::widgets.layout';
        //dd($this->viewFile['layout']);
        return view($this->viewFile['layout']);
    }

    private function tree($refs)
    {
        // 배열 정렬
        usort($refs, function($a, $b) {
            return $b['level'] - $a['level'];
        });

        $rows = [];
        foreach($refs as $item) {
            $id = $item['id'];
            $rows[$id] = $item;
        }

        foreach($rows as &$item) {
            $id = $item['id'];
            if($item['ref']) {
                $ref = $item['ref'];
                if(!isset($rows[$ref]['items'])) {
                    $rows[$ref]['items'] = [];
                }
                $rows[$ref]['items'] []= $item;

                unset($rows[$id]);
            }
        }

        return $rows;
    }




    protected $listeners = [
        'create','popupFormCreate',
        'edit','popupEdit','popupCreate'
    ];

    /**
     * 신규 추가
     */
    public function create($value=null)
    {
        $this->popupForm = true;
        $this->edit_id = null;

        $this->forms = []; // 데이터초기화
        $this->forms['code'] = $this->code;
    }

    public function store()
    {
        // 서브트리 추가
        if($this->reply_id) {
            $this->forms['ref'] = $this->reply_id;

            $id = $this->reply_id;
            $this->forms['level'] = $this->level + 1;
        }
        // 신규 루트 추가
        else {
            $this->forms['ref'] = 0;
            $this->forms['level'] = 1;
        }

        // 2. 시간정보 생성
        $this->forms['created_at'] = date("Y-m-d H:i:s");
        $this->forms['updated_at'] = date("Y-m-d H:i:s");

        $form = $this->forms;

        $id = count($this->refs)+1;
        $form['id'] = $id;
        $this->refs[$id] = $form;

        //저장
        $this->save();
        $this->popupClose();
    }




    // 트리의 데이터를 수정합니다.
    public $editmode=null;
    public function edit($id)
    {
        $this->editmode = "edit";
        $this->reply_id = $id;

        $this->forms = $this->refs[$id];

        $this->edit_id = $id;
        $this->popupForm = true;
    }

    public function update()
    {
        $id = $this->reply_id;
        $this->refs[$id] = $this->forms;

        //저장
        $this->save();
        $this->popupClose();
    }



    /**
     * 수정메뉴에서,
     * 하위 서브메뉴를 추가 생성모드로 변경
     */
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
    /*
    public function reply($id, $level)
    {
        $this->reply_id = $id;
        $this->level = $level;

        // 데이터초기화
        $this->forms = [];
        $this->forms['code'] = $this->code;

        $this->popupForm = true;
    }
    */



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
        unset($this->refs[$id]);
        $this->save(); //저장
        $this->popupClose();
    }

    public function popupClose()
    {
        $this->forms = []; // 초기화
        $this->reply_id = null;
        $this->level = null;
        $this->editmode = null;
        $this->popupForm = false;
        $this->edit_id = null;

        $this->popupDelete = false;

        $this->setup = false;
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

    // 메뉴클릭 릉크이동
    public function goToPage($shiftKey, $id)
    {
        if($shiftKey) {
            //dd("shiftKey 클릭=".$id);
            $this->edit($id);
        } else {
            //dd("클릭");
            $item = $this->refs[$id];
            //dd($item);
            if($item && isset($item['link'])) {
                return redirect()->to($item['link']);
            }

        }
    }

    // 메뉴 변경
    public function shiftEdit($shiftKey, $id)
    {
        if($shiftKey) {
            $this->edit($id);
        } else {
            $item = $this->refs[$id];
        }
    }










}
