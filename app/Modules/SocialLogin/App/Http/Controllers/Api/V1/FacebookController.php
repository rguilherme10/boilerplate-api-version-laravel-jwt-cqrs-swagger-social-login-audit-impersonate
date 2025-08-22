<?php
namespace Modules\SocialLogin\App\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Bus;
use Laravel\Socialite\Facades\Socialite;
use Modules\SocialLogin\App\Application\Commands\User\GetUserBySocialLoginQuery;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *      name="Facebook",
 *      description="Endpoints de autenticação com Facebook"
 * )
 * 
 */
class FacebookController extends Controller
{
    
    private $provider = 'facebook';
    /**
     * @OA\Get(
     *      path="/social-login/v1/auth/facebook",
     *      operationId="redirectFacebook",
     *      tags={"Facebook"},
     *      summary="Redireciona para a autenticação do Facebook",
     *      @OA\Response(
     *          response=302,
     *          description="Redireciona para a página de autenticação do Facebook"
     *      )
     * )
     */
    public function redirectFacebook()
    {
        return Socialite::driver($this->provider)->stateless()->redirect();
    }

    /**
     * @OA\Get(     
     *      path="/social-login/v1/auth/facebook/callback",
     *      operationId="handleFacebook",
     *      tags={"Facebook"},
     *      summary="Callback para autenticação do Facebook",
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
    public function handleFacebook()
    {
        
        $facebookUser = Socialite::driver($this->provider)->stateless()->user();

        $user = Bus::dispatchSync(new GetUserBySocialLoginQuery($this->provider, $facebookUser));

        $token = JWTAuth::fromUser($user);
        
        return redirect(config('app.frontend_social_callback')."?token={$token}");
    }
}