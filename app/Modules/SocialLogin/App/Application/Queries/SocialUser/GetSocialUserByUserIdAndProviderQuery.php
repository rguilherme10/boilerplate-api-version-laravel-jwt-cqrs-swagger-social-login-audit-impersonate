<?php
namespace Modules\SocialLogin\App\Application\Queries\SocialUser;

class GetSocialUserByUserIdAndProviderQuery{

    public function __construct(
        public int $user_id,
        public string $provider
    )
    {
    }
}