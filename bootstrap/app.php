<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        //Para que reconozca el archivo de rutas admin.php añado una funcion anónima donde se invoca el middleware web y añado el archivo de rutas admin.php con group
        then:function (): void {
            Route::middleware('web', 'auth')
            ->prefix('admin')//todas las rutas comenzan con /admin, por lo que me ahorro escribir el prefijo en cada ruta del archivo de rutas
            ->name('admin.')//añade a las rutas nombradas el prefijo admin., por lo que me ahorra escribir por ejemplo admin.dashboard.
            ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
