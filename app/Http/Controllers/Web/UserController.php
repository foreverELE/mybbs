<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('auth',[      //未登录用户仅可执行create、store操作；
            'except'=>['create','store']
        ]);

        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|unique:users|max:255|email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //登录
        Auth::login($user);

        //缓存仅读取一次的信息
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        try{
            //授权策略验证
            $this->authorize('update',$user);
        }catch(\Exception $e){
            abort('404');
        }

        //
        return view('web.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //授权策略验证
        $this->authorize('update',$user);
        //
        return view('web.users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {


        $this->validate($request,[
            'name'=>'required|max:50',
            'password'=>'nullable|min:6|max:255|confirmed',
        ]);

        //授权策略验证
        $this->authorize('update',$user);

        $data=[];
        $data['name']=$request->name;
        if($request->password){     //密码不为空则更新密码
           $data['password']=bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success','个人信息编辑成功~');
        return redirect()->route('users.show', $user->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
