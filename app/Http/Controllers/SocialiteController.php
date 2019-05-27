<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
class SocialiteController extends Controller
{
    public function githublogin()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function githubCallback()
    {
        $user = Socialite::driver('github')->user();
//        dd($user);
        $data = User::where('email',$user->email)->first();
        if(!$data){
            $data = User::create([
                'email'=>$user->email,
                'name'=>$user->nickname,
                'password'=>bcrypt('xiaoyi'),//真实项目给个随机数
            ]);
        }
        Auth::login($data);
        return redirect('/');
        // $user->token;
    }
    //qq，微信一样
    public function wxCallback()
    {
        $user = Socialite::driver('github')->user();
        //判断这个用户是否存在，不存在则创建一个
        $data = User::firstOrCreate([
            'wx_id'=>$user->id,
        ],[
            'name'=>$user->nickname,
            'wx_id'=>$user->id,
            'avatar'=>$user->avatar,
        ]);
        Auth::login($data);
        return redirect('/');
        // $user->token;
    }
}
