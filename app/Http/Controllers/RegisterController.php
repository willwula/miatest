<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            // docs: available validation rules
            'name' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|alpha_num:ascii|min:6|max:255|confirmed'
        ]);

        // alpha_num:只允許英文字母及數字
        // 密碼 confirmed 條件是前端要多一個再次輸入密碼的欄位
        // 即使使用者多輸入欄位，也會被過濾只剩下這三個
//        dd($validated);

        abort_if(
            User::where('email', $request->input('email'))->first(),
            Response::HTTP_BAD_REQUEST,
            __('auth.duplicate email')
        );
//        abort_if(
//            (bool)User::where('email', $request->input('email'))->first(),
//            Response::HTTP_BAD_REQUEST,
//            __("此 email 已經註冊過")
//        );
//
//        $validated['password'] = \Hash::make($request->input('password'));

        $user = User::create(
            array_merge(
                $validated, ['password' => Hash::make($validated['password'])]
            )
        );

//        return 'registered';
//        return response([
//            'data' => $user,
//            'message' => "註冊成功"
//            ], 201);
        return \response($user,201);
    }

}
