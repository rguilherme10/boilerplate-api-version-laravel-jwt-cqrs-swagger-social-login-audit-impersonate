<?php 
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * 
 * @OA\Tag(
 *      name="Usuário",
 *      description="Endpoints relacionadas ao usuário autenticado"
 * )
 * 
 */
class UserController extends Controller
{

    
    /**
     * @OA\Get(
     *      path="/api/v1/me",
     *      operationId="me",
     *      tags={"Usuário"},
     *      summary="Retorna o usuário autenticado",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Dados do usuário autenticado",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example="1"),
     *              @OA\Property(property="name", type="string", example="Fulano"),
     *              @OA\Property(property="email", type="string", format="email", example="usuario@example.com")
     *          )
     *      )
     * )
     */
    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    
    /**
     * @OA\Get(
     *      path="/api/v1/user",
     *      operationId="user",
     *      tags={"Usuário"},
     *      summary="Retorna o usuário autenticado",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Dados do usuário autenticado",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example="1"),
     *              @OA\Property(property="name", type="string", example="Fulano"),
     *              @OA\Property(property="email", type="string", format="email", example="usuario@example.com")
     *          )
     *      )
     * )
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}