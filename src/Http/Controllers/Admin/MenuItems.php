<?php

namespace Jiny\Menu\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuItems extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('jinymenu::admin.menu.items')
            ->with([
                'id'=>$id
            ]);
    }

}
