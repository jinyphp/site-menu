<?php

namespace Jiny\Menu\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Jiny\Table\Http\Controllers\ResourceController;
class MenuFileController extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);
    }
}
