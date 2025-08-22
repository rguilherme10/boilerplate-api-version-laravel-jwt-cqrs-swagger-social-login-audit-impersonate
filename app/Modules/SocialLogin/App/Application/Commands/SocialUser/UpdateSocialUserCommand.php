<?php 
namespace Modules\SocialLogin\App\Application\Commands\SocialUser;

class UpdateSocialUserCommand
{
    public function __construct(
        public string $user_id,
        public string $oauth_type,
        public string $oauth_id,
        public string $oauth_name,
        public string $oauth_email,
        public string $oauth_nickname,
        public string $oauth_token,
        public string $oauth_avatar
    )
    {
    }
}