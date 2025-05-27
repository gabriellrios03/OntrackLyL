<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApCajas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *     name="Cajas",
 *     description="Operaciones con cajas de transporte"
 * )
 */
class ApCajasController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    /**
     * @OA\Get(
     *     path="/api/cajas",
     *     summary="Listar todas las cajas activas",
     *     tags={"Cajas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de cajas activas"
     *     ),
    *     @OA\Response(
    *         response=401,
    *         description="No autorizado"
    *     )
     * )
     */
    public function index()
    {
        $cajas = ApCajas::where('activo', 1)->get();
        return response()->json($cajas);
    }

    /**
     * @OA\Post(
     *     path="/api/cajas/crear",
     *     summary="Crear un nueva cajas",
     *     tags={"Cajas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"noeconomico","placas","inspeccionmecanica","ultimaubicacion","usuario","fechamodifica","activo"},
     *             @OA\Property(property="noeconomico", type="string", example="ABC123"),
     *             @OA\Property(property="placas", type="string", example="Volvo"),
     *             @OA\Property(property="inspeccionmecanica", type="date", example="2025-01-15"),
     *             @OA\Property(property="ultimaubicacion", type="string", example="25000"),
     *             @OA\Property(property="usuario", type="string", example="usuario"),
     *             @OA\Property(property="fechamodifica", type="string", format="date", example="2025-01-15"),
     *             @OA\Property(property="activo", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Caja creada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos no válidos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noeconomico' => 'required|string|max:250|unique:camions',
            'placas' => 'required|string|max:250',
            'inspeccionmecanica' => 'required|date',
            'ultimaubicacion' => 'required|string|max:250',
            'usuario' => 'required|string|max:180',            
            'fechamodifica' => 'required|date',
            'activo' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $caja = ApCajas::create($request->all());

        return response()->json($caja, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/cajas/{id}",
     *     summary="Obtener una caja específica",
     *     tags={"Cajas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la Caja",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos de caja"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Caja no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $caja = ApCajas::find($id);
        if (!$caja) {
            return response()->json(['message' => 'Caja no encontrada'], 404);
        }
        return response()->json($caja);
    }

    /**
     * @OA\Post(
     *     path="/api/cajas/actualizar/{id}",
     *     summary="Actualizar una caja",
     *     tags={"Cajas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la caja",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"noeconomico","placas","inspeccionmecanica","ultimaubicacion","usuario","fechamodifica","activo"},
     *             @OA\Property(property="noeconomico", type="string", example="ABC123"),
     *             @OA\Property(property="placas", type="string", example="Volvo"),
     *             @OA\Property(property="inspeccionmecanica", type="date", example="2025-01-15"),
     *             @OA\Property(property="ultimaubicacion", type="string", example="25000"),
     *             @OA\Property(property="usuario", type="string", example="usuario"),
     *             @OA\Property(property="fechamodifica", type="string", format="date", example="2025-01-15"),
     *             @OA\Property(property="activo", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Caja actualizada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Caja no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos no válidos"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $caja = ApCajas::find($id);
        if (!$caja) {
            return response()->json(['message' => 'Caja no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'noeconomico' => 'required|string|max:250|unique:camions',
            'placas' => 'required|string|max:250',
            'inspeccionmecanica' => 'required|date',
            'ultimaubicacion' => 'required|string|max:250',
            'usuario' => 'required|string|max:180',            
            'fechamodifica' => 'required|date',
            'activo' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $caja->update($request->all());

        return response()->json($caja);
    }

    /**
     * @OA\POST(
     *     path="/api/cajas/eliminar/{id}",
     *     summary="Eliminar una caja",
     *     tags={"Cajas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del camión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Camión eliminado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Camión no encontrado"
     *     )
     * )
     */
    public function desactivar($id)
    {
        $caja = ApCajas::find($id);
        if (!$camion) {
            return response()->json(['message' => 'Caja no encontrada'], 404);
        }

        $caja->update([
            'activo' => 0,
            'fechamodifica' => now()
        ]);

        return response()->json(['message' => 'Caja eliminada']);
    }
}
