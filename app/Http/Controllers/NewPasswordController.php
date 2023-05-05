<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class NewPasswordController extends Controller
{
    public function store(Request $request)
    {

        // 驗證所需欄位
        $credentials = $this->validate($request, [
            'email'    => ['required', 'email'],
            'token'    => ['required', 'string'],
            'password' => [
                // PasswordRule::min(6) 跟 min:6 差異
                //PasswordRule::min(6) 會檢查不能與舊密碼相同且不必須要包含英文跟數字組成的密碼
                'required', 'regex:/^(?=.*[0-9])(?=.*[a-zA-Z])([\w]+)$/u', 'confirmed', PasswordRule::min(6),
            ],
        ]);
        //透過broker 找出使用者的email
        //透過reset 完成 更新密碼這件事,並刪除使用者token
        $result = Password::broker('users')->reset($credentials, function (User $user, $password) {
            $user->update(
            // ['password' => Hash::make($password)]);
            //明碼檢查比較好看有沒有更新成功
                ['password' => $password]);

        });
        return response(['data' => __($result)]);
    }
}
