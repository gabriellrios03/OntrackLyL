<?php

namespace App\Http\Swagger;

/**
 * @OA\Info(
 *     title="API CRUD Transportes",
 *     version="1.0.0",
 *     description="API para gestión de transporte",
 *     @OA\Contact(
 *         email="",
 *         name="Equipo de Desarrollo"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8081/api",
 *     description="Servidor Local"
 * )
 * 
 * @OA\Tag(
 *     name="Cajas",
 *     description="Operaciones con cajas de transporte"
 * )
 */
/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */
class SwaggerAnnotations {}