<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApFactura;
use App\Models\ApViaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApFacturasController extends Controller
{
    /**
     * Obtiene todas las facturas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $facturas = ApFactura::all();
        return response()->json($facturas);
    }

    /**
     * Obtiene facturas activas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $facturas = ApFactura::where('activo', true)->get();
        return response()->json($facturas);
    }

    /**
     * Obtiene una factura específica
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $factura = ApFactura::find($id);
        
        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }
        
        return response()->json($factura);
    }

    /**
     * Crea una nueva factura
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearFactura(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'viajeID' => 'required|integer|exists:ap_viajes,id',
                'folio' => 'required|string',
                'uuid' => 'nullable|string',
                'monto' => 'required|numeric|min:0',
                'estadopago' => 'required|string',
                'activo' => 'required|boolean',
                'MontoXML' => 'nullable|numeric|min:0'
            ]);

            $factura = new ApFactura();
            $factura->fill($validatedData);
            $factura->usuario = Auth::id();
            $factura->fechamodifica = now();
            $factura->save();
            
            return response()->json($factura, 201);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza una factura existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarFactura(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'viajeID' => 'required|integer|exists:ap_viajes,id',
                'folio' => 'required|string',
                'uuid' => 'nullable|string',
                'monto' => 'required|numeric|min:0',
                'estadopago' => 'required|string',
                'activo' => 'required|integer|min:0',
                'MontoXML' => 'nullable|numeric|min:0'
            ]);

            $factura = ApFactura::find($id);
            
            if (!$factura) {
                return response()->json(['message' => 'Factura no encontrada'], 404);
            }
            
            $factura->fill($validatedData);
            $factura->usuario = Auth::id();
            $factura->fechamodifica = now();
            $factura->save();
            
            return response()->json($factura);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactiva una factura
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarFactura($id)
    {
        $factura = ApFactura::find($id);
        
        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }
        
        $factura->activo = false;
        $factura->usuario = Auth::id();
        $factura->fechamodifica = now();
        $factura->save();
        
        return response()->json([
            'success' => true,
            'message' => "Factura {$id} desactivada correctamente"
        ]);
    }    
}
