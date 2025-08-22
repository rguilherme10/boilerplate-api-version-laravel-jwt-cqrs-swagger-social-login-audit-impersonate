<?php
namespace App\Http\Controllers\Api\V1;

use App\Application\Buses\QueryBusInterface;
use App\Application\Queries\GetUserByIdQuery;
use App\Application\Queries\User\GetUserByIdQuery as UserGetUserByIdQuery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Bus;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * 
 * @OA\Tag(
 *      name="Autenticação",
 *      description="Endpoints de autenticação de usuários"
 * )
 * 
 */
class ImpersonationController extends Controller
{
    
    /**
     * @OA\Post(     
     *      path="/api/v1/impersonate/{id}",
     *      operationId="impersonate",
     *      tags={"Autenticação"},
     *      summary="Assume papel de outro usuário",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Token de personificação atualizado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *              @OA\Property(property="token_type", type="string", example="bearer"),
     *              @OA\Property(property="expires_in", type="integer", example="3600"),
     *              @OA\Property(property="acting_as", type="object", example="{'id':2, 'name':'Fulano', 'email':'fulano@example.com'}"),
     *              @OA\Property(property="real_user", type="object", example="{'id':1, 'name':'Admin', 'email':'admin@example.com'}")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autorizado"
     *      )
     * )
     */
    public function impersonate(Request $request, $id)
    {
        $realUser = auth('api')->user(); // quem está logado
        $impersonatedUser = Bus::dispatchSync(new UserGetUserByIdQuery($id)); // quem será impersonado


        // Verifica se o usuário tem permissão para impersonar
        if (!$realUser || !$realUser->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Gera o token com rastreabilidade
        $token = JWTAuth::claims(['impersonated_id' => $impersonatedUser->id])
                        ->fromUser($realUser);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'acting_as' => $impersonatedUser->only(['id', 'name', 'email']),
            'real_user' => $realUser->only(['id', 'name', 'email']),
        ]);
    }
}