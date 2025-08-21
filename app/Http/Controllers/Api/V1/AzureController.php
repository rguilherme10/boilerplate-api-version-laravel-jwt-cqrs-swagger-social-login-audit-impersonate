<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
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
    /**
     * @OA\Get(
     *      path="/api/v1/auth/azure",
     *      operationId="redirectAzure",
     *      tags={"Social"},
     *      summary="Redireciona para a autenticação do Azure",
     *      @OA\Response(
     *          response=302,
     *          description="Redireciona para a página de autenticação do Azure"
     *      )
     * )
     */
    public function redirectAzure()
    {
        return Socialite::driver('azure')->stateless()->redirect();
    }

    /**
     * @OA\Get(     
     *      path="/api/v1/auth/azure/callback",
     *      operationId="handleAzure",
     *      tags={"Social"},
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
        $azureUser = Socialite::driver('azure')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $azureUser->getEmail()],
            [
                'name' => $azureUser->getName(),
                'azure_id' => $azureUser->getId(),
                'oauth_type' => 'azure',
                'oauth_token' => $azureUser->token,
                'password' => bcrypt(Str::random(16)),
            ]
        );

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => \Tymon\JWTAuth\Facades\JWTAuth::factory()->getTTL() * 60
        ]);
    }
}