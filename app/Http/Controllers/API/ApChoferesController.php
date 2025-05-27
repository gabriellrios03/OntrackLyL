<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApChofer;
use App\Models\ApViaje;
use App\Models\ApExtra;
use App\Models\ApPagoChofer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApChoferesController extends Controller
{
   /**
     * Obtener todos los choferes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $choferes = ApChofer::all();
        return response()->json($choferes);
    }

    /**
     * Obtener choferes activos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $choferes = ApChofer::where('activo', true)->get();
        return response()->json($choferes);
    }

    /**
     * Obtener un chofer por ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $chofer = ApChofer::find($id);
        
        if (!$chofer) {
            return response()->json(['message' => 'Chofer no encontrado'], 404);
        }
        
        return response()->json($chofer);
    }

    /**
     * Obtener viajes de un chofer
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getViajes($id)
    {
        $viajes = ApViaje::where('Id_chofer', $id)->get();
        
        if ($viajes->isEmpty()) {
            return response()->json(['message' => 'No se encontraron viajes'], 404);
        }
        
        return response()->json($viajes);
    }

    /**
     * Obtener extras de un chofer
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExtras($id)
    {
        $extras = ApExtra::where('choferID', $id)->get();
        
        if ($extras->isEmpty()) {
            return response()->json(['message' => 'No se encontraron extras'], 404);
        }
        
        return response()->json($extras);
    }

    /**
     * Obtener pagos de un chofer
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPagos($id)
    {
        $pagos = ApPagoChofer::where('choferID', $id)->get();
        
        if ($pagos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron pagos'], 404);
        }
        
        return response()->json($pagos);
    }

    /**
     * Crear un nuevo chofer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearChofer(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'nolicencialocal' => 'required|string',
                'nolicenciafederal' => 'required|string',
                'rfc' => 'required|string|size:13',
                'status' => 'required|string',
                'activo' => 'required|integer|min:0'
            ]);
            
            $chofer = new ApChofer();
            $chofer->fill($validatedData);
            $chofer->usuario = Auth::id();
            $chofer->fechamodifica = now();
            $chofer->save();
            
            return response()->json($chofer, 201);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un chofer existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarChofer(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'nolicencialocal' => 'required|string',
                'nolicenciafederal' => 'required|string',
                'rfc' => 'required|string|size:13',
                'status' => 'required|string',
                'activo' => 'required|boolean'
            ]);
            
            $chofer = ApChofer::find($id);
            
            if (!$chofer) {
                return response()->json(['message' => 'Chofer no encontrado'], 404);
            }
            
            $chofer->fill($validatedData);
            $chofer->usuario = Auth::id();
            $chofer->fechamodifica = now();
            $chofer->save();
            
            return response()->json($chofer);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactivar un chofer
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarChofer($id)
    {
        $chofer = ApChofer::find($id);
        
        if (!$chofer) {
            return response()->json(['message' => 'Chofer no encontrado'], 404);
        }
        
        $chofer->activo = false;
        $chofer->usuario = Auth::id();
        $chofer->fechamodifica = now();
        $chofer->save();
        
        return response()->json([
            'success' => true,
            'message' => "Chofer {$id} desactivado correctamente"
        ]);
    }
}
