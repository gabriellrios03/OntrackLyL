<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApExtra;
use App\Models\ApChofer;
use App\Models\ApViaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApExtrasController extends Controller
{
    /**
     * Obtiene todos los extras
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $extras = ApExtra::all();
        return response()->json($extras);
    }

    /**
     * Obtiene extras activos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $extras = ApExtra::where('activo', true)->get();
        return response()->json($extras);
    }

    /**
     * Obtiene un extra específico
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $extra = ApExtra::find($id);
        
        if (!$extra) {
            return response()->json(['message' => 'Extra no encontrado'], 404);
        }
        
        return response()->json($extra);
    }

    /**
     * Crea un nuevo extra
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearExtra(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'concepto' => 'required|string',
                'tipo' => 'required|string',
                'choferID' => 'required|integer|exists:ap_choferes,id',
                'viajeID' => 'nullable|integer|exists:ap_viajes,id',
                'previajeID' => 'nullable|integer',
                'observaciones' => 'nullable|string',
                'monto' => 'required|numeric|min:0',
                'activo' => 'required|boolean',
                'pagado' => 'required|boolean'
            ]);

            // Validación adicional para previajeID si es necesario
            // if ($request->previajeID && !ApPreviaje::find($request->previajeID)) {
            //     return response()->json(['message' => 'Previaje no encontrado'], 400);
            // }

            $extra = new ApExtra();
            $extra->fill($validatedData);
            $extra->usuario = Auth::id();
            $extra->fechamodifica = now();
            $extra->save();
            
            return response()->json($extra, 201);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un extra existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarExtra(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'concepto' => 'required|string',
                'tipo' => 'required|string',
                'choferID' => 'required|integer|exists:ap_choferes,id',
                'viajeID' => 'nullable|integer|exists:ap_viajes,id',
                'previajeID' => 'nullable|integer',
                'observaciones' => 'nullable|string',
                'monto' => 'required|numeric|min:0',
                'activo' => 'required|integer|min:0',
                'pagado' => 'required|integer|min:0'
            ]);

            $extra = ApExtra::find($id);
            
            if (!$extra) {
                return response()->json(['message' => 'Extra no encontrado'], 404);
            }

            // Validación adicional para previajeID si es necesario
            // if ($request->previajeID && !ApPreviaje::find($request->previajeID)) {
            //     return response()->json(['message' => 'Previaje no encontrado'], 400);
            // }
            
            $extra->fill($validatedData);
            $extra->usuario = Auth::id();
            $extra->fechamodifica = now();
            $extra->save();
            
            return response()->json($extra);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactiva un extra
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarExtra($id)
    {
        $extra = ApExtra::find($id);
        
        if (!$extra) {
            return response()->json(['message' => 'Extra no encontrado'], 404);
        }
        
        $extra->activo = false;
        $extra->usuario = Auth::id();
        $extra->fechamodifica = now();
        $extra->save();
        
        return response()->json([
            'success' => true,
            'message' => "Extra {$id} desactivado correctamente"
        ]);
    }
}
