<?php
namespace Modules\SocialLogin\App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * 
 * @OA\Tag(
 *      name="Social",
 *      description="Endpoints de autenticação com redes sociais"
 * )
 * 
 */
class SocialController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/v1/auth/linkedin",
     *      operationId="redirectLinkedin",
     *      tags={"Social"},
     *      summary="Redireciona para a autenticação do LinkedIn",
     *      @OA\Response(
     *          response=302,
     *          description="Redireciona para a página de autenticação do LinkedIn"
     *      )
     * )
     */
    public function redirectLinkedin()
    {
        return Socialite::driver('linkedin')->stateless()->redirect();
    }

    /**
     * @OA\Get(     
     *      path="/api/v1/auth/linkedin/callback",
     *      operationId="handleLinkedin",
     *      tags={"Social"},
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
    public function handleLinkedin()
    {
        $socialUser = Socialite::driver('linkedin')->stateless()->user();
        return $this->handleSocialCallback($socialUser, 'linkedin');
    }

    /**
     * @OA\Get(
     *      path="/api/v1/auth/google",
     *      operationId="redirectGoogle",
     *      tags={"Social"},
     *      summary="Redireciona para a autenticação do Google",
     *      @OA\Response(
     *          response=302,
     *          description="Redireciona para a página de autenticação do Google"
     *      )
     * )
     */
    public function redirectGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }


    /**
     * @OA\Get(     
     *      path="/api/v1/auth/google/callback",
     *      operationId="handleGoogle",
     *      tags={"Social"},
     *      summary="Callback para autenticação do Google",
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
    public function handleGoogle()
    {
        $socialUser = Socialite::driver('google')->stateless()->user();
        return $this->handleSocialCallback($socialUser, 'google');
    }


    /**
     * @OA\Get(
     *      path="/api/v1/auth/facebook",
     *      operationId="redirectFacebook",
     *      tags={"Social"},
     *      summary="Redireciona para a autenticação do Facebook",
     *      @OA\Response(
     *          response=302,
     *          description="Redireciona para a página de autenticação do Facebook"
     *      )
     * )
     */
    public function redirectFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    /**
     * @OA\Get(     
     *      path="/api/v1/auth/facebook/callback",
     *      operationId="handleFacebook",
     *      tags={"Social"},
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
        $socialUser = Socialite::driver('facebook')->stateless()->user();
        return $this->handleSocialCallback($socialUser, 'facebook');
    }

    protected function handleSocialCallback($socialUser, $provider)
    {
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                "{$provider}_id" => $socialUser->getId(),
                'oauth_type' => $provider,
                'oauth_token' => $socialUser->token,
                'password' => bcrypt(Str::random(16)),
                "{$provider}_avatar" => $socialUser->avatar??"",
            ]
        );

        switch($provider){
            case 'linkedin':
                $user->linkedin_firstname = $socialUser->first_name??'';
                $user->linkedin_lastname = $socialUser->last_name??'';
            case 'facebook':
                $user->facebook_name = $socialUser->name??'';
                $user->facebook_email = $socialUser->email??'';
                $user->facebook_nick = $socialUser->nickname??'';
            case 'google':
                $user->google_firstname = $socialUser->user["given_name"]??'';
                $user->google_lastname = $socialUser->user["family_name"]??'';
        }
        $user->save();

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => \Tymon\JWTAuth\Facades\JWTAuth::factory()->getTTL() * 60
        ]);
    }
}