<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Perupustakaan",
 *     description="Perpupstakaan API Documentation"
 * )
 * @OA\Server(
 *     url="http://127.0.0.1:8000/",
 *     description="Local server"
 * )
 * @OA\Server(
 *     url="http://staging.example.com",
 *     description="Staging server"
 * )
 * @OA\Server(
 *     url="http://example.com",
 *     description="Production server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer"
 * )
 */
abstract class Controller
{
    // Base controller logic or shared methods can go here
}
