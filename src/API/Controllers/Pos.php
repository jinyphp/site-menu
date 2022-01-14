<?php

namespace Jiny\Menu\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use \Jiny\Html\CTag;


class Pos extends Controller
{
    public function index()
    {

        foreach($_POST['menu'] as $id => $menu) {
            DB::table('menu_items')
                ->where('id', $id)
                ->update($menu);
        }




        return response()->json([
            'post'=>$_POST
        ]);
    }

}
