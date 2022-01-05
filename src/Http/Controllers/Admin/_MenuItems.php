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
    public function index(Request $request,$id)
    {
        //$request->session()->push('user.teams','developer');
        //$request->session()->push('user.teams','developer');

        //session('a',"b");
        //session(['key'=>"default"]);
        //session(['a.b'=>"default"]);
        //echo session()->push('user.a','developer');;
        //dd($request->session()->all());

        return view('jinymenu::admin.menu.items')
            ->with([
                'id'=>$id
            ]);
    }

}
