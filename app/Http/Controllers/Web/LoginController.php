<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function __construct(){
        $this->middleware('guest',[
            'only'=>['showLoginForm']
        ]);
    }

    //登录页
    public function showLoginForm()
    {
        return view('web.users.login');
    }

    //登录操作
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        //验证邮箱密码是否匹配
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password],$request->has('remember'))){
            session()->flash('success','欢迎回来~');
//            return redirect()->route('users.show',[auth()->user()]);
            return redirect()->intended(route('users.show',[auth()->user()]));
        }else{
            session()->flash('danger','邮箱密码不匹配！');
            return redirect()->back()->withInput();
        }

    }

    public function logout()
    {
        Auth::logout();
        session()->flash('success','您已退出登录~');
        return redirect()->route('login');
    }
}
