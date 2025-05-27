<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApDiesel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApDieselController extends Controller
{
    /**
     * Obtiene todos los registros de diesel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $diesel = ApDiesel::all();
        return response()->json($diesel);
    }

    /**
     * Obtiene registros activos de diesel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $diesel = ApDiesel::where('activo', true)->get();
        return response()->json($diesel);
    }

    /**
     * Obtiene un registro específico de diesel
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $diesel = ApDiesel::find($id);
        
        if (!$diesel) {
            return response()->json(['message' => 'Registro de diesel no encontrado'], 404);
        }
        
        return response()->json($diesel);
    }

    /**
     * Crea un nuevo registro de diesel
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearDiesel(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'Id_camion' => 'required|integer',
                'km_inicial' => 'required|numeric|min:0',
                'km_final' => 'required|numeric|min:0|gt:km_inicial',
                'sitio_recarga' => 'required|string',
                'costo_litro' => 'required|numeric|min:0',
                'total_litros' => 'required|numeric|min:0',
                'fecha' => 'required|date',
                'activo' => 'required|integer|min:0'
            ]);

            $diesel = new ApDiesel();
            $diesel->fill($validatedData);
            
            // Calcular rendimiento y bandera
            $calculo = $diesel->calcularRendimiento();
            $diesel->rendimiento = $calculo['rendimiento'];
            $diesel->bandera = $calculo['bandera'];
            
            // Calcular costo total
            $diesel->total_costo = $diesel->costo_litro * $diesel->total_litros;
            
            // Datos de auditoría
            $diesel->usuario = Auth::id();
            $diesel->fechamodifica = now();
            
            $diesel->save();
            
            return response()->json($diesel, 201);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un registro de diesel existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarDiesel(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'Id_camion' => 'required|integer',
                'km_inicial' => 'required|numeric|min:0',
                'km_final' => 'required|numeric|min:0|gt:km_inicial',
                'sitio_recarga' => 'required|string',
                'costo_litro' => 'required|numeric|min:0',
                'total_litros' => 'required|numeric|min:0',
                'fecha' => 'required|date',
                'activo' => 'required|boolean'
            ]);

            $diesel = ApDiesel::find($id);
            
            if (!$diesel) {
                return response()->json(['message' => 'Registro de diesel no encontrado'], 404);
            }
            
            $diesel->fill($validatedData);
            
            // Calcular rendimiento y bandera
            $calculo = $diesel->calcularRendimiento();
            $diesel->rendimiento = $calculo['rendimiento'];
            $diesel->bandera = $calculo['bandera'];
            
            // Calcular costo total
            $diesel->total_costo = $diesel->costo_litro * $diesel->total_litros;
            
            // Datos de auditoría
            $diesel->usuario = Auth::id();
            $diesel->fechamodifica = now();
            
            $diesel->save();
            
            return response()->json($diesel);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactiva un registro de diesel
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarDiesel($id)
    {
        $diesel = ApDiesel::find($id);
        
        if (!$diesel) {
            return response()->json(['message' => 'Registro de diesel no encontrado'], 404);
        }
        
        $diesel->activo = false;
        $diesel->usuario = Auth::id();
        $diesel->fechamodifica = now();
        $diesel->save();
        
        return response()->json([
            'success' => true,
            'message' => "Registro de diesel {$id} desactivado correctamente"
        ]);
    }
}
