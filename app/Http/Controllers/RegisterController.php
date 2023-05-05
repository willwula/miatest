<?php

namespace App\Http\Controllers;

use App\Mail\RegisterSuccessMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            // docs: available validation rules
            'name' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'password' => 'required|alpha_num:ascii|min:6|max:255|confirmed'
        ]);
        // validator 預設會回傳422 status code (給前端工程師看的)，前端看不出來是因為email格式錯誤還是email重複註冊問題

        // alpha_num:只允許英文字母及數字
        // 密碼 confirmed 條件是前端要多一個再次輸入密碼的欄位
        // 即使使用者多輸入欄位，也會被過濾只剩下這三個
//        dd($validated);

        abort_if(
            User::where('email', $request->input('email'))->first(),
            Response::HTTP_BAD_REQUEST,
            __('auth.duplicate email')
        );
        // 讓使用者資料知道註冊不通過的原因，並把狀態碼改成顯示為400


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
        event(new Registered($user));
        // 寄送註冊成功通知信
        Mail::to($user->email)->send(new RegisterSuccessMail($user));
dd($user);
//        return view('emails.register-success', ['user' => $user]);
//        Auth::login($user);

//        return redirect('/home');

//        return 'registered';
//        return response([
//            'data' => $user,
//            'message' => "註冊成功"
//            ], 201);
//        return \response($user,201);
    }

}
