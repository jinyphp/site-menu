<?php

namespace Jiny\Menu\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\MenuItems;

use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class WireUpload extends Component
{
    use WithFileUploads;
    public $actions = [];
    public $menu_id;

    public function render()
    {
        return view("jinymenu::livewire.upload");
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

        if($this->menu_id) {
            $this->decodeTo($filename);

            session()->flash('message', "file Successfully uploaded!");
        } else {
            session()->flash('message', "메뉴코드가 지정되어 있지 않습니다!");
        }

        ## 파일 삭제
        Storage::disk('public')->delete($filename);

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');
    }

    private function decodeTo($filename)
    {
        $path = storage_path('app/public');
        $json = file_get_contents($path.DIRECTORY_SEPARATOR.$filename);

        $rows = json_decode($json,true);

        $maxid = DB::table('menu_items')->max('id');

        $this->treeToRows($rows, $maxid);
    }

    // 재귀호출
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

            unset($item['user_id']);

            $db->insert($item);
        }
    }

    /**
     * 파일 다운로드
     *
     */
    public function export()
    {
        session()->flash('message', "메뉴 설정 json 파일을 다운로드 합니다.");

        $path = resource_path('menus');
        $filePath = $path.DIRECTORY_SEPARATOR.$this->menu_id.".json";
        if(file_exists($filePath)) {
            return response()->download($filePath); // storage_path(storage_path())
        }
    }
}
