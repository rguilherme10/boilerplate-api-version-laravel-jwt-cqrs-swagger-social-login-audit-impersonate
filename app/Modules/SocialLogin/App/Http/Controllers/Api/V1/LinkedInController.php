<?php
namespace Modules\SocialLogin\App\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Bus;
use Laravel\Socialite\Facades\Socialite;
use Modules\SocialLogin\App\Application\Commands\User\UserGetUserBySocialLoginCommand;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *      name="LinkedIn",
 *      description="Endpoints de autenticação com LinkedIn"
 * )
 * 
 */
class LinkedInController extends Controller
{
    
    private $provider = 'linkedin-openid';
    /**
     * @OA\Get(
     *      path="/social-login/v1/auth/linkedin",
     *      operationId="redirectLinkedIn",
     *      tags={"LinkedIn"},
     *      summary="Redireciona para a autenticação do LinkedIn",
     *      @OA\Response(
     *          response=302,
     *          description="Redireciona para a página de autenticação do LinkedIn"
     *      )
     * )
     */
    public function redirectLinkedIn()
    {
        return Socialite::driver($this->provider)->stateless()->redirect();
    }

    /**
     * @OA\Get(     
     *      path="/social-login/v1/auth/linkedin/callback",
     *      operationId="handleLinkedIn",
     *      tags={"LinkedIn"},
     *      summary="Callback para autenticação do LinkedIn",
     *      @OA\Response(
     *          response=200,
     *          description="Autenticação bem-sucedida",
     *          @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *              @OA\Property(property="token_type", type="string", example="bearer"),
     *              @OA\Property(property="expires_in", type="integer", example="3600")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Falha na autenticação"
     *      )
     * )
     */
    public function handleLinkedIn()
    {
        
        $linkedinUser = Socialite::driver($this->provider)->stateless()->user();

        $user = Bus::dispatchSync(new UserGetUserBySocialLoginCommand($this->provider, $linkedinUser));

        $token = JWTAuth::fromUser($user);
        
        return redirect(config('app.frontend_social_callback')."?token={$token}");
    }
}