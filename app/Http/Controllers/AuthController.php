<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 登入表單驗證
        $credential = $this->validate($request, [
            'email' => 'email|required|max:255',
            'password' => 'required|alpha_num:ascii|min:6|max:12'
        ]);

        // 換成 Jwt 以 api guard 方式進行登入驗證
        // Auth::attempt() 使用預設的guard 嘗試去拿驗證的東西，內建應該是email & password（包含hash)
        // 這邊告訴Auth要以guard('api')方是驗證，預設通常是default
        $token = Auth::guard('api')->attempt($credential);
        abort_if( !$token,Response::HTTP_BAD_REQUEST, "帳號密碼錯誤");
        //驗證成功會給予token、驗證不成功會給你false

        return response(['data' => $token]);
    }

    public function logout()
    {
        Auth::logout();
//        return "lougout";
        return response()->noContent(); // 204狀態，前端不需重新渲染
    }

    public function redirectToProvider() {

        return Socialite::driver('github')->redirect();
    }
    public function handleProviderCallback() {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'github_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]);

        Auth::login($user);

        return "Oauth login successful";
    }
}
