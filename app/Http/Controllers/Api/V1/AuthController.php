<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *      title="API V1",
 *      version="1.0",
 *      description="API em Laravel com Swagger, Versionamento, JWT-Auth, CQRS, Impersonate, Auditoria, ActivityLogs, Social Login, Chat Gemini AI"
 * )
 * 
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 * 
 * @OA\Tag(
 *      name="Autenticação",
 *      description="Endpoints de autenticação de usuários"
 * )
 */
class AuthController extends Controller
{
    
    /**
     * @OA\Post(     
     *      path="/api/v1/login",
     *      operationId="login",
     *      tags={"Autenticação"},
     *      summary="Autentica um usuário e retorna um token JWT",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="senha123")
     *          )
     *      ),
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
     *          description="Credenciais inválidas"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erro de validação"
     *      )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth('api')->attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/refresh",
     *      operationId="refresh",
     *      tags={"Autenticação"},
     *      summary="Atualiza um token JWT",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Token atualizado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *              @OA\Property(property="token_type", type="string", example="bearer"),
     *              @OA\Property(property="expires_in", type="integer", example="3600")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autorizado"
     *      )
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * @OA\Post(     
     *      path="/api/v1/logout",
     *      operationId="logout",
     *      tags={"Autenticação"},
     *      summary="Invalida um token JWT",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Logout realizado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Logout realizado com sucesso")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autorizado"
     *      )
     * )
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => \Tymon\JWTAuth\Facades\JWTAuth::factory()->getTTL() * 60
        ]);
    }

    
}