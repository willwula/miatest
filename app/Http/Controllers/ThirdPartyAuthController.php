<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ThirdPartyAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
//        dd($provider);
//        dd(Socialite::driver($provider));
        // 轉址到第三方認證
        return Socialite::driver($provider)->redirect();
    }


    public function handleProviderCallback($provider)
    {
//        dd(Socialite::driver($provider)->user());
        $providerUser = Socialite::driver($provider)->user();
//        dd($providerUser);

        $user = User::updateOrCreate([
            'email' => $providerUser->email, //這邊用email當key值查詢，官方文件是用github_id
        ], [
            'name' => $providerUser->name,
            $provider . '_id' => $providerUser->id,
            $provider . '_token' => $providerUser->token, // 暫時先沒存進資料庫
            $provider . '_refresh_token' => $providerUser->refreshToken, // 暫時先沒存進資料庫
            'email_verified_at' => now(), // 放入fillable會有問題嗎
        ]);

        Auth::login($user);
        return $user;

//        return $this->loginToken($providerUser->token, $provider);
    }

    public function loginToken($token, $provider)
    {
        $value = Socialite::driver($provider)
            ->stateless()
            ->userFromToken($token);
        return $value;
    }

    public function facebookLogin(Request $request)
    {
        $thirdParty = 'facebook';

        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            $thirdPartyUser = Socialite::driver($thirdParty)
                ->stateless() //此次驗證請求不需使用 Session，使請求更輕量化
                ->userFromToken($request->input('access_token')); // 這裡的access_token是前端另外處理的嗎？
            // 用前端傳過來（第三方傳給User）的 token，重新向第三方服務驗證此Token是否正確，並取得user資料
        } catch (\Throwable $exception) { //Throwable可同時捕捉到 exception 和 error
            logger()->error('取得第三方使用者失敗', ['exception' => $exception]);
            // 可以用 terminal 指令 tail -f storage/logs/laravel.log 查閱
            abort(Response::HTTP_UNAUTHORIZED);
        }
        return $this->getFinalValidToken($thirdPartyUser, 'facebook_user_id');

    }

    private function getFinalValidToken($thirdPartyUser, $thirdPartyUserKey)
    {
        $existingUser = User::where($thirdPartyUserKey, $thirdPartyUser->getId())->first();

        // 已經有連結第三方帳戶
        if (filled($existingUser)) {
            return $this->respondWithToken($existingUser);
        }

        // 沒有連結過此第三方帳戶，看有沒有重複email
        $duplicatedUser = User::whereEmail($thirdPartyUser->getEmail())->first();
        if (isset($duplicatedUser)) {
            $duplicatedUser->forceFill([
                'email_verified_at' => now(),
                $thirdPartyUserKey =>$thirdPartyUser->getId(),
            ])->save();

            return $this->respondWithToken($duplicatedUser);
        }

        // 沒有連結第三方帳戶，也沒有重複email
        $newUser = User::forceCreate([
            'name' => $thirdPartyUser->name ?? "user-$thirdPartyUserKey",
            'email' => $thirdPartyUser->email,
            'email_verified_at' => now(),
            $thirdPartyUserKey => $thirdPartyUser->id,
        ]);

        return $this->respondWithToken($newUser);

    }

    private function respondWithToken(User $user)
    {
        $guard = Auth::guard('api');
        $tokenLife = 60 * 24 * 365; //unit:minute (for one year)
        $token = $guard->setTTL($tokenLife)->login($user);

        return \response()->json([
            'data' => [
                'access_token' => $token,
                'expires_in' => $tokenLife * 60,
                'has_verified_email' => $user->hasVerifiedEmail(),
                'token_type' => 'bearer',
            ],
        ]);
    }
}
