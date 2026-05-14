<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\GestoraController;
use App\Http\Controllers\B2BController;
use App\Http\Controllers\ComunidadController;

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Autenticación usuarios internos
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Gestión de usuarios
|--------------------------------------------------------------------------
*/

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

/*
|--------------------------------------------------------------------------
| Gestión de técnicos
|--------------------------------------------------------------------------
*/

Route::get('/tecnicos', [TecnicoController::class, 'index'])->name('tecnicos.index');
Route::get('/tecnicos/create', [TecnicoController::class, 'create'])->name('tecnicos.create');
Route::post('/tecnicos', [TecnicoController::class, 'store'])->name('tecnicos.store');
Route::get('/tecnicos/{id}/edit', [TecnicoController::class, 'edit'])->name('tecnicos.edit');
Route::put('/tecnicos/{id}', [TecnicoController::class, 'update'])->name('tecnicos.update');
Route::delete('/tecnicos/{id}', [TecnicoController::class, 'destroy'])->name('tecnicos.destroy');

/*
|--------------------------------------------------------------------------
| Gestión de especialidades
|--------------------------------------------------------------------------
*/

Route::get('/especialidades', [EspecialidadController::class, 'index'])->name('especialidades.index');
Route::get('/especialidades/create', [EspecialidadController::class, 'create'])->name('especialidades.create');
Route::post('/especialidades', [EspecialidadController::class, 'store'])->name('especialidades.store');
Route::get('/especialidades/{id}/edit', [EspecialidadController::class, 'edit'])->name('especialidades.edit');
Route::put('/especialidades/{id}', [EspecialidadController::class, 'update'])->name('especialidades.update');
Route::delete('/especialidades/{id}', [EspecialidadController::class, 'destroy'])->name('especialidades.destroy');

/*
|--------------------------------------------------------------------------
| Gestión de incidencias internas
|--------------------------------------------------------------------------
*/

Route::get('/incidencias', [IncidenciaController::class, 'index'])->name('incidencias.index');
Route::get('/incidencias/create', [IncidenciaController::class, 'create'])->name('incidencias.create');
Route::post('/incidencias', [IncidenciaController::class, 'store'])->name('incidencias.store');
Route::get('/incidencias/{id}/edit', [IncidenciaController::class, 'edit'])->name('incidencias.edit');
Route::put('/incidencias/{id}', [IncidenciaController::class, 'update'])->name('incidencias.update');
Route::delete('/incidencias/{id}', [IncidenciaController::class, 'destroy'])->name('incidencias.destroy');

/*
|--------------------------------------------------------------------------
| Gestión de gestoras
|--------------------------------------------------------------------------
*/

Route::get('/gestoras', [GestoraController::class, 'index'])->name('gestoras.index');
Route::get('/gestoras/create', [GestoraController::class, 'create'])->name('gestoras.create');
Route::post('/gestoras', [GestoraController::class, 'store'])->name('gestoras.store');
Route::get('/gestoras/{id}/edit', [GestoraController::class, 'edit'])->name('gestoras.edit');
Route::put('/gestoras/{id}', [GestoraController::class, 'update'])->name('gestoras.update');
Route::delete('/gestoras/{id}', [GestoraController::class, 'destroy'])->name('gestoras.destroy');

/*
|--------------------------------------------------------------------------
| Gestión de comunidades
|--------------------------------------------------------------------------
*/

Route::get('/comunidades', [ComunidadController::class, 'index'])->name('comunidades.index');
Route::get('/comunidades/create', [ComunidadController::class, 'create'])->name('comunidades.create');
Route::post('/comunidades', [ComunidadController::class, 'store'])->name('comunidades.store');
Route::get('/comunidades/{id}/edit', [ComunidadController::class, 'edit'])->name('comunidades.edit');
Route::put('/comunidades/{id}', [ComunidadController::class, 'update'])->name('comunidades.update');
Route::delete('/comunidades/{id}', [ComunidadController::class, 'destroy'])->name('comunidades.destroy');

/*
|--------------------------------------------------------------------------
| Panel B2B de gestoras
|--------------------------------------------------------------------------
*/

Route::get('/b2b/login', [B2BController::class, 'showLogin'])->name('b2b.login');
Route::post('/b2b/login', [B2BController::class, 'login'])->name('b2b.login.submit');
Route::get('/b2b/logout', [B2BController::class, 'logout'])->name('b2b.logout');

Route::get('/b2b/panel', [B2BController::class, 'panel'])->name('b2b.panel');

Route::get('/b2b/aviso/create', [B2BController::class, 'createAviso'])->name('b2b.create_aviso');
Route::post('/b2b/aviso', [B2BController::class, 'storeAviso'])->name('b2b.store_aviso');

Route::get('/b2b/aviso/{id}/edit', [B2BController::class, 'editAviso'])->name('b2b.edit_aviso');
Route::put('/b2b/aviso/{id}', [B2BController::class, 'updateAviso'])->name('b2b.update_aviso');
Route::delete('/b2b/aviso/{id}', [B2BController::class, 'destroyAviso'])->name('b2b.destroy_aviso');

/*
|--------------------------------------------------------------------------
| Liquidaciones administrador
|--------------------------------------------------------------------------
*/

Route::get('/liquidaciones', [B2BController::class, 'liquidaciones'])->name('liquidaciones.index');