<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;

class PasswordResetLinkController extends Controller
{
    public function store(Request $request) {
        //驗證欄位
        $validate = $request -> validate([
           'email' => ['required', 'email'],
        ]);
        //透過broker 找出使用者的email
        //透過sendResetLink實施發信
        $result = Password::broker('users')->sendResetLink($validate);
        //$result 的結果會有幾種狀態 其中成功發信的狀態是 Password::RESET_LINK_SENT
        //如果不是得到 RESET_LINK_SENT  返回BAD_REQUEST
        //  __()是多語言的用法
//        dd(Response::HTTP_BAD_REQUEST);
//        dd($result);
//        dd(__($result));
        abort_if($result !== Password::RESET_LINK_SENT,
            Response::HTTP_BAD_REQUEST,
        __($result)
        );


        //這個多語言是吃laravel 內建寫好的
        return response(['data' =>  __($result)]);

    }
}
