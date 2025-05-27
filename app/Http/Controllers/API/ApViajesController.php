<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApViaje;
use App\Models\ApCamion;
use App\Models\ApRuta;
use App\Models\ApChofer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApViajesController extends Controller
{
   /**
     * Obtiene todos los viajes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $viajes = ApViaje::all();
        return response()->json($viajes);
    }

    /**
     * Obtiene viajes activos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $viajes = ApViaje::where('activo', true)->get();
        return response()->json($viajes);
    }

    /**
     * Obtiene un viaje específico
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $viaje = ApViaje::find($id);
        
        if (!$viaje) {
            return response()->json(['message' => 'Viaje no encontrado'], 404);
        }
        
        return response()->json($viaje);
    }

    /**
     * Crea un nuevo viaje
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearViaje(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'Id_ruta' => 'required|integer|exists:ap_rutas,id',
                'Id_chofer' => 'required|integer|exists:ap_choferes,id',
                'Id_camion' => 'required|integer|exists:ap_camiones,id',
                'fecha' => 'required|date',
                'estado' => 'required|string',
                'cajainicial' => 'nullable|numeric|min:0',
                'cajafinal' => 'nullable|numeric|min:0',
                'selloInicial' => 'nullable|string',
                'selloFinal' => 'nullable|string',
                'evidencia' => 'nullable|string',
                'facturado' => 'required|boolean',
                'finalizado' => 'required|boolean',
                'motivo' => 'nullable|string',
                'ClienteID' => 'nullable|integer',
                'activo' => 'required|integer|min:0'
            ]);

            $viaje = new ApViaje();
            $viaje->fill($validatedData);
            $viaje->usuario = Auth::id();
            $viaje->fechamodifica = now();
            $viaje->save();
            
            return response()->json($viaje, 201);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un viaje existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarViaje(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'Id_ruta' => 'required|integer|exists:ap_rutas,id',
                'Id_chofer' => 'required|integer|exists:ap_choferes,id',
                'Id_camion' => 'required|integer|exists:ap_camiones,id',
                'fecha' => 'required|date',
                'estado' => 'required|string',
                'cajainicial' => 'nullable|numeric|min:0',
                'cajafinal' => 'nullable|numeric|min:0',
                'selloInicial' => 'nullable|string',
                'selloFinal' => 'nullable|string',
                'evidencia' => 'nullable|string',
                'facturado' => 'required|boolean',
                'finalizado' => 'required|boolean',
                'motivo' => 'nullable|string',
                'ClienteID' => 'nullable|integer',
                'activo' => 'required|boolean'
            ]);

            $viaje = ApViaje::find($id);
            
            if (!$viaje) {
                return response()->json(['message' => 'Viaje no encontrado'], 404);
            }
            
            $viaje->fill($validatedData);
            $viaje->usuario = Auth::id();
            $viaje->fechamodifica = now();
            $viaje->save();
            
            return response()->json($viaje);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactiva un viaje
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarViaje($id)
    {
        $viaje = ApViaje::find($id);
        
        if (!$viaje) {
            return response()->json(['message' => 'Viaje no encontrado'], 404);
        }
        
        $viaje->activo = false;
        $viaje->usuario = Auth::id();
        $viaje->fechamodifica = now();
        $viaje->save();
        
        return response()->json([
            'success' => true,
            'message' => "Viaje {$id} desactivado correctamente"
        ]);
    }
}
