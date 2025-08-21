<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class ImpersonationController extends Controller
{
    public function impersonate(Request $request, $id)
    {
        $realUser = auth()->user(); // quem está logado
        $impersonatedUser = User::findOrFail($id); // quem será impersonado

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