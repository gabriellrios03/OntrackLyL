<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApPagoChofer;
use App\Models\ApChofer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApPagoChoferesController extends Controller
{
    /**
     * Obtiene todos los pagos a choferes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $pagos = ApPagoChofer::all();
        return response()->json($pagos);
    }

    /**
     * Obtiene pagos activos a choferes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $pagos = ApPagoChofer::where('activo', true)->get();
        return response()->json($pagos);
    }

    /**
     * Obtiene un pago específico
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $pago = ApPagoChofer::find($id);
        
        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }
        
        return response()->json($pago);
    }

    /**
     * Crea un nuevo pago a chofer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearPagoChofer(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'choferID' => 'required|integer|exists:ap_choferes,id',
                'viajeID' => 'nullable|integer',
                'ExtrasID' => 'nullable|integer',
                'monto' => 'required|numeric|min:0',
                'fecha' => 'required|date',
                'estado' => 'required|string',
                'activo' => 'required|boolean'
            ]);

            // Validación adicional para viajeID si es necesario
            // if ($request->viajeID && !ApViaje::find($request->viajeID)) {
            //     return response()->json(['message' => 'Viaje no encontrado'], 400);
            // }

            // Validación adicional para ExtrasID si es necesario
            // if ($request->ExtrasID && !ApExtra::find($request->ExtrasID)) {
            //     return response()->json(['message' => 'Extra no encontrado'], 400);
            // }

            $pago = new ApPagoChofer();
            $pago->fill($validatedData);
            $pago->usuario = Auth::id();
            $pago->fechamodifica = now();
            $pago->save();
            
            return response()->json($pago, 201);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un pago existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarPagoChofer(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'choferID' => 'required|integer|exists:ap_choferes,id',
                'viajeID' => 'nullable|integer',
                'ExtrasID' => 'nullable|integer',
                'monto' => 'required|numeric|min:0',
                'fecha' => 'required|date',
                'estado' => 'required|string',
                'activo' => 'required|integer|min:0'
            ]);

            $pago = ApPagoChofer::find($id);
            
            if (!$pago) {
                return response()->json(['message' => 'Pago no encontrado'], 404);
            }

            // Validación adicional para viajeID si es necesario
            // if ($request->viajeID && !ApViaje::find($request->viajeID)) {
            //     return response()->json(['message' => 'Viaje no encontrado'], 400);
            // }

            // Validación adicional para ExtrasID si es necesario
            // if ($request->ExtrasID && !ApExtra::find($request->ExtrasID)) {
            //     return response()->json(['message' => 'Extra no encontrado'], 400);
            // }
            
            $pago->fill($validatedData);
            $pago->usuario = Auth::id();
            $pago->fechamodifica = now();
            $pago->save();
            
            return response()->json($pago);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactiva un pago
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarPagoChofer($id)
    {
        $pago = ApPagoChofer::find($id);
        
        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }
        
        $pago->activo = false;
        $pago->usuario = Auth::id();
        $pago->fechamodifica = now();
        $pago->save();
        
        return response()->json([
            'success' => true,
            'message' => "Pago {$id} desactivado correctamente"
        ]);
    }
}
