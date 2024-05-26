<?php
namespace Jiny\Menu\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class WidgetTopMenu extends WidgetMenu
{
    public function mount()
    {
        parent::mount();


    }

    // Template Method Pattern
    protected function viewLayoutFile()
    {
        $viewFile = 'jiny-menu::topmenu.layout';

        if(isset($this->widget['view']['layout'])) {
            $viewFile = $this->widget['view']['layout'];
        }

        $this->viewFile['layout'] = $viewFile;
        return $viewFile;
    }

    // Template Method Pattern
    protected function viewListFile()
    {
        $viewFile = 'jiny-menu::topmenu.list';

        if(isset($this->widget['view']['list'])) {
            $viewFile = $this->widget['view']['list'];
        }

        $this->viewList = $viewFile;
        return $viewFile;
    }

    // Template Method Pattern
    protected function viewListFileItem()
    {
        $viewFile = 'jiny-menu::topmenu.item';

        if(isset($this->widget['view']['item'])) {
            $viewFile = $this->widget['view']['item'];
        }

        $this->viewListItem = $viewFile;
        return $viewFile;
    }

    // Template Method Pattern
    protected function viewFormFile()
    {
        $this->viewForm = "jiny-menu::topmenu.form";

        if(isset($this->widget['view']['form'])) {
            $this->viewForm = $this->widget['view']['form'];
        }

        return $this->viewForm;
    }

}
