<?php 

namespace Modules\SocialLogin\App\Application\Handlers\User;

use App\Application\Commands\User\CreateUserCommand;
use App\Application\Queries\User\GetUserByEmailQuery;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\SocialLogin\App\Application\Commands\SocialUser\CreateSocialUserCommand;
use Modules\SocialLogin\App\Application\Commands\SocialUser\UpdateSocialUserCommand;
use Modules\SocialLogin\App\Application\Commands\User\GetUserBySocialLoginQuery;
use Modules\SocialLogin\App\Application\Queries\SocialUser\GetSocialUserByUserIdAndProviderQuery;

class HandleGetUserBySocialLogin
{
    public function handle(GetUserBySocialLoginQuery $query) : User
    {
        $cacheKey = "socialLoginUser:{$query->provider}:{$query->socialLoginUser->getId()}";

        return Cache::remember($cacheKey, now()->addDays(10), function () use ($query) {
            
            $email = $query->socialLoginUser->getEmail();
            $user = Bus::dispatchSync(new GetUserByEmailQuery($email));
            if($user===null){
                $user = Bus::dispatchSync(new CreateUserCommand(
                    name: $query->socialLoginUser->getName(),
                    email: $email,
                    password: Hash::make(Str::random(16)),
                    email_verified_at: now()
                ));
            }

            
            $socialUser = Bus::dispatchSync(new GetSocialUserByUserIdAndProviderQuery($user->id, $query->provider));
            if($socialUser===null){
                $socialUser = Bus::dispatchSync(new CreateSocialUserCommand(
                    user_id: $user->id, 
                    oauth_type: $query->provider,
                    oauth_id: $query->socialLoginUser->getId(),
                    oauth_name: $query->socialLoginUser->getName(),
                    oauth_email: $query->socialLoginUser->getEmail(),
                    oauth_nickname: $query->socialLoginUser->getNickname(),
                    oauth_token: encrypt($query->socialLoginUser->token),
                    oauth_avatar: $query->socialLoginUser->getAvatar()??""
                ));
                return $user;
            }
            
            $socialUser = Bus::dispatchSync(new UpdateSocialUserCommand(
                user_id: $user->id, 
                oauth_type: $query->provider,
                oauth_id: $query->socialLoginUser->getId(),
                oauth_name: $query->socialLoginUser->getName(),
                oauth_email: $query->socialLoginUser->getEmail(),
                oauth_nickname: $query->socialLoginUser->getNickname(),
                oauth_token: encrypt($query->socialLoginUser->token),
                oauth_avatar: $query->socialLoginUser->getAvatar()??""
            ));
            return $user;
        });
    }
}