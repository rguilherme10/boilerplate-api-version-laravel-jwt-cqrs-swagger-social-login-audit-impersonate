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

    /**
     * @OA\Post(
     *      path="/api/v1/register",
     *      operationId="register",
     *      tags={"Usuário"},
     *      summary="Registra um novo usuário",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password","password_confirmation"},
     *              @OA\Property(property="name", type="string", example="Novo Usuário"),
     *              @OA\Property(property="email", type="string", format="email", example="novo@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="senha123"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="senha123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Usuário registrado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example="2"),
     *              @OA\Property(property="name", type="string", example="Novo Usuário"),
     *              @OA\Property(property="email", type="string", format="email", example="novo@example.com")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erro de validação"
     *      )
     * )
     */
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($user, 201);
        
    }
}