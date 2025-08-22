<?php 

namespace Modules\SocialLogin\App\Application\Handlers\SocialUser;

use Modules\SocialLogin\App\Application\Commands\SocialUser\CreateSocialUserCommand;
use Modules\SocialLogin\App\Application\Commands\SocialUser\UpdateSocialUserCommand;
use Modules\SocialLogin\App\Events\LogonWithSocial;
use Modules\SocialLogin\App\Models\SocialUser;

class HandleCreateOrUpdateSocialUser
{
    public function handle(CreateSocialUserCommand|UpdateSocialUserCommand $command) : SocialUser
    {
        $socialUser = SocialUser::updateOrCreate(
            [
                'user_id' => $command->user_id, 
                'oauth_type' => $command->oauth_type
            ],
            [
                'oauth_id' => $command->oauth_id,
                'oauth_name' => $command->oauth_name,
                'oauth_email' => $command->oauth_email,
                'oauth_nickname' => $command->oauth_nickname,
                'oauth_token' => $command->oauth_token,
                'oauth_avatar' => $command->oauth_avatar
            ]
        );

        event(new LogonWithSocial($socialUser));

        // Auditoria com impersonação
        $realUser = auth('api')->user();
        $impersonatedUser = app()->has('impersonated_user') ? app('impersonated_user') : null;

        activity()
        ->causedBy($realUser)
        ->performedOn($socialUser)
        ->withProperties([
            'impersonated_as' => $impersonatedUser?->id,
            'impersonated_name' => $impersonatedUser?->name,
            'command' => 'CreateSocialUserCommand|UpdateSocialUserCommand',
            'data' => json_encode($command)
        ])
        ->log('SocialUser criado|atualizado via HandleCreateOrUpdateSocialUser');

        return $socialUser;
    }
}