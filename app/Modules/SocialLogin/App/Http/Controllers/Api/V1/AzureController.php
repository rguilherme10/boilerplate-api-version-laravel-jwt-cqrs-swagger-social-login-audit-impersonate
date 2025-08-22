<?php
namespace Modules\SocialLogin\App\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Bus;
use Laravel\Socialite\Facades\Socialite;
use Modules\SocialLogin\App\Application\Commands\User\GetUserBySocialLoginQuery;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *      name="Azure",
 *      description="Endpoints de autenticação com Azure"
 * )
 * 
 */
class AzureController extends Controller
{
    
    private $provider = 'microsoft';
    /**
     * @OA\Get(
     *      path="/social-login/v1/auth/azure",
     *      operationId="redirectAzure",
     *      tags={"Azure"},
     *      summary="Redireciona para a autenticação do Azure",
     *      @OA\Response(
     *          response=302,
     *          description="Redireciona para a página de autenticação do Azure"
     *      )
     * )
     */
    public function redirectAzure()
    {
        return Socialite::driver($this->provider)->stateless()->redirect();
    }

    /**
     * @OA\Get(     
     *      path="/social-login/v1/auth/azure/callback",
     *      operationId="handleAzure",
     *      tags={"Azure"},
     *      summary="Callback para autenticação do Azure",
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
    public function handleAzure()
    {
        
        $azureUser = Socialite::driver($this->provider)->stateless()->user();

        $user = Bus::dispatchSync(new GetUserBySocialLoginQuery($this->provider, $azureUser));

        $token = JWTAuth::fromUser($user);
        
        return redirect(config('app.frontend_social_callback')."?token={$token}");
    }
}