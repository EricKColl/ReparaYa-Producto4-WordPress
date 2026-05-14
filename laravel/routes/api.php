<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
| Endpoint REST para datos agregados por zona.
*/

Route::get('/servicios/zonas', [ApiController::class, 'serviciosPorZona']);
