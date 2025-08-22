<?php 

namespace Modules\SocialLogin\App\Application\Handlers\User;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Modules\SocialLogin\App\Application\Queries\SocialUser\GetSocialUserByUserIdAndProviderQuery;
use Modules\SocialLogin\App\Models\SocialUser;

class HandleGetSocialUserByUserIdAndProvider
{
    public function handle(GetSocialUserByUserIdAndProviderQuery $query) : User
    {
        $cacheKey = "socialUser:user_id:{$query->user_id}:provider:{$query->provider}";

        return Cache::remember($cacheKey, now()->addDays(10), function () use ($query) {
            $socialUser = SocialUser::where(['user_id'=>$query->user_id,'oauth_type'=>$query->provider])->first();
            return $socialUser;
        });
    }
}