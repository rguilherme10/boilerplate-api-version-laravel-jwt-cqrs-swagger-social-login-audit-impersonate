<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;
use Spatie\Health\ResultStores\ResultStore;

/**
 * @OA\Tag(
 *     name="Health",
 *     description="Verificação de saúde da API"
 * )
 */
class HealthController extends HealthCheckJsonResultsController

{
    /**
     * @OA\Get(
     *     path="/api/v1/health",
     *     summary="Verifica a saúde da API",
     *     tags={"Health"},
     *     @OA\Response(
     *         response=200,
     *         description="Status dos checks de saúde",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ok"),
     *             @OA\Property(
     *                 property="checks",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="Database connection"),
     *                     @OA\Property(property="status", type="string", example="ok")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(Request $request, ResultStore $resultStore): Response
    {
        return parent::__invoke($request, $resultStore);
    }
}