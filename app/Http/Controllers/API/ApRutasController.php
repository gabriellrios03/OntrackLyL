<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApRuta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApRutasController extends Controller
{
    /**
     * Obtiene todas las rutas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $rutas = ApRuta::all();
        return response()->json($rutas);
    }

    /**
     * Obtiene rutas activas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $rutas = ApRuta::where('activo', true)->get();
        return response()->json($rutas);
    }

    /**
     * Obtiene una ruta específica
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $ruta = ApRuta::find($id);
        
        if (!$ruta) {
            return response()->json(['message' => 'Ruta no encontrada'], 404);
        }
        
        return response()->json($ruta);
    }

    /**
     * Crea una nueva ruta
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearRuta(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'origen' => 'required|string',
                'destino' => 'required|string',
                'kilomestima' => 'required|numeric|min:0',
                'consumodiesel' => 'required|numeric|min:0',
                'tarifacliente' => 'required|numeric',
                'ClienteID' => 'nullable|integer',
                'activo' => 'required|integer|min:0'
            ]);

            $ruta = new ApRuta();
            $ruta->fill($validatedData);
            $ruta->usuario = Auth::id();
            $ruta->fechamodifica = now();
            $ruta->save();
            
            return response()->json($ruta, 201);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza una ruta existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarRuta(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'origen' => 'required|string',
                'destino' => 'required|string',
                'kilomestima' => 'required|numeric|min:0',
                'consumodiesel' => 'required|numeric|min:0',
                'tarifacliente' => 'required|numeric',
                'ClienteID' => 'nullable|integer',
                'activo' => 'required|boolean'
            ]);

            $ruta = ApRuta::find($id);
            
            if (!$ruta) {
                return response()->json(['message' => 'Ruta no encontrada'], 404);
            }
            
            $ruta->fill($validatedData);
            $ruta->usuario = Auth::id();
            $ruta->fechamodifica = now();
            $ruta->save();
            
            return response()->json($ruta);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactiva una ruta
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarRuta($id)
    {
        $ruta = ApRuta::find($id);
        
        if (!$ruta) {
            return response()->json(['message' => 'Ruta no encontrada'], 404);
        }
        
        $ruta->activo = false;
        $ruta->usuario = Auth::id();
        $ruta->fechamodifica = now();
        $ruta->save();
        
        return response()->json([
            'success' => true,
            'message' => "Ruta {$id} desactivada correctamente"
        ]);
    }
}
