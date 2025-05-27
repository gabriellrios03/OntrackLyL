<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApCamion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApCamionesController extends Controller
{
        /**
     * Obtener todos los camiones
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $camiones = ApCamion::all();
        return response()->json($camiones);
    }

    /**
     * Obtener camiones activos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $camiones = ApCamion::where('activo', 1)->get();
        return response()->json($camiones);
    }

    /**
     * Obtener un camión por ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $camion = ApCamion::find($id);
        
        if (!$camion) {
            return response()->json(['message' => 'Camión no encontrado'], 404);
        }
        
        return response()->json($camion);
    }

    /**
     * Crear un nuevo camión
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearCamion(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'noeconomico' => 'required',
                'placas' => 'required',
                'nmotor' => 'required',
                'fechaverifica' => 'required|date',
                'ultimokilom' => 'required|numeric',
                'verifhumos' => 'required',
                'activo' => 'required|integer|min:0',
            ]);
            
            $camion = new ApCamion();
            $camion->fill($validatedData);
            $camion->usuario = Auth::id();
            $camion->fechamodifica = now();
            $camion->save();
            
            return response()->json($camion);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Actualizar un camión existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarCamion(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'noeconomico' => 'required',
                'placas' => 'required',
                'nmotor' => 'required',
                'fechaverifica' => 'required|date',
                'ultimokilom' => 'required|numeric',
                'verifhumos' => 'required',
                'activo' => 'required|boolean',
            ]);
            
            $camion = ApCamion::find($id);
            
            if (!$camion) {
                return response()->json(['message' => 'Camión no encontrado'], 404);
            }
            
            $camion->fill($validatedData);
            $camion->usuario = Auth::id();
            $camion->fechamodifica = now();
            $camion->save();
            
            return response()->json($camion);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Desactivar un camión
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarCamion($id)
    {
        $camion = ApCamion::find($id);
        
        if (!$camion) {
            return response()->json(['message' => 'Camión no encontrado'], 404);
        }
        
        $camion->activo = 0;
        $camion->usuario = Auth::id();
        $camion->fechamodifica = now();
        $camion->save();
        
        return response()->json([
            'success' => true,
            'message' => "Camión {$id} desactivado correctamente"
        ]);
    }
}
