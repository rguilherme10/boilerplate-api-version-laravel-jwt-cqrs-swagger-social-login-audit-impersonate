<?php 

namespace Modules\SocialLogin\App\Application\Handlers\User;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Modules\SocialLogin\App\Application\Commands\User\UserGetUserBySocialLoginCommand;
use Modules\SocialLogin\App\Models\SocialUser;

class HandleGetUserBySocialLogin
{
    public function handle(UserGetUserBySocialLoginCommand $query) : User
    {
        $cacheKey = "socialLoginUser:{$query->provider}:{$query->socialLoginUser->getId()}";

        return Cache::remember($cacheKey, now()->addDays(10), function () use ($query) {
            $user = User::firstOrCreate(
                ['email' => $query->socialLoginUser->getEmail()],
                [
                    'name' => $query->socialLoginUser->getName(),
                    'password' => bcrypt(Str::random(16)),
                ]
            );

            $socialUser = SocialUser::updateOrCreate(
                [ "user_id" => $user->id, 'oauth_type' => $query->provider ],
                [
                    "oauth_id" => $query->socialLoginUser->getId(),
                    'oauth_name' => $query->socialLoginUser->getName(),
                    'oauth_email' => $query->socialLoginUser->getEmail(),
                    'oauth_nickname' => $query->socialLoginUser->getNickname(),
                    'oauth_token' => encrypt($query->socialLoginUser->token),
                    "oauth_avatar" => $query->socialLoginUser->getAvatar()??""
                ]
            );

            return $user;
        });
    }
}