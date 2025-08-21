<?php 
namespace Modules\SocialLogin\App\Application\Commands\User;

class UserGetUserBySocialLoginCommand
{
    public string $provider;
    public \Laravel\Socialite\Contracts\User $socialLoginUser;

    public function __construct(string $provider, \Laravel\Socialite\Contracts\User $socialLoginUser)
    {
        $this->provider     = $provider;
        $this->socialLoginUser     = $socialLoginUser;
    }
}