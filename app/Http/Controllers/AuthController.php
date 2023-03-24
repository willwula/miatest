<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
        // Auth::attempt()為嘗試登入
        $token = Auth::guard('api')->attempt($credential);
//        $token = Auth::attempt($credential);
        abort_if( !$token,Response::HTTP_BAD_REQUEST, "帳號密碼錯誤");
        //驗證成功會給予token、驗證不成功會給你false
//        return \response(['data' => $token,
//            'message' => "登入成功"]);

//         Auth::attempt() 使用預設的guard 嘗試去拿驗證的東西，內建應該是email & password（包含hash)
//        return Auth::attempt($credential);
        return response(['token' => $token]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->noContent(); // 204狀態，前端不需重新渲染
    }
}
