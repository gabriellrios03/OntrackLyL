<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApClientesController extends Controller
{
       /**
     * Obtiene todos los clientes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $clientes = ApCliente::all();
        return response()->json($clientes);
    }

    /**
     * Obtiene todos los clientes (alias de getAll)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTodos()
    {
        return $this->getAll();
    }

    /**
     * Obtiene clientes activos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivos()
    {
        $clientes = ApCliente::where('activo', true)->get();
        return response()->json($clientes);
    }

    /**
     * Obtiene un cliente por ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $cliente = ApCliente::find($id);
        
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
        
        return response()->json($cliente);
    }

    /**
     * Crea un nuevo cliente
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearCliente(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'rfc' => 'required|string|size:13',
                'status' => 'required|string',
                'activo' => 'required|integer|min:0'
            ]);
            
            $cliente = new ApCliente();
            $cliente->fill($validatedData);
            $cliente->usuario = Auth::id();
            $cliente->fechamodifica = now();
            $cliente->save();
            
            return response()->json($cliente, 201);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un cliente existente
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarCliente(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'rfc' => 'required|string|size:13',
                'status' => 'required|string',
                'activo' => 'required|boolean'
            ]);
            
            $cliente = ApCliente::find($id);
            
            if (!$cliente) {
                return response()->json(['message' => 'Cliente no encontrado'], 404);
            }
            
            $cliente->fill($validatedData);
            $cliente->usuario = Auth::id();
            $cliente->fechamodifica = now();
            $cliente->save();
            
            return response()->json($cliente);
            
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactiva un cliente
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function desactivarCliente($id)
    {
        $cliente = ApCliente::find($id);
        
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
        
        $cliente->activo = false;
        $cliente->usuario = Auth::id();
        $cliente->fechamodifica = now();
        $cliente->save();
        
        return response()->json([
            'success' => true,
            'message' => "Cliente {$id} desactivado correctamente"
        ]);
    }
}
