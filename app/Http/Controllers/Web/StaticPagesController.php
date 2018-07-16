<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticPagesController extends Controller
{
    //主页
    public function home(){
        return view('web.static_pages.home');
    }

    //帮助页
    public function help(){
        return view('web/static_pages/help');
    }

    //关于页
    public function about(){
        return view('web/static_pages/about');
    }
}
