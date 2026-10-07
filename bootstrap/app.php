<?php

use App\Http\Middleware\EnsureTenantIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function (Request $request) {
            if (function_exists('tenant') && tenant()) {
                return route('tenant.home'); // Redirige vers la page welcome
            }
            
            return route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            return route('tenant.login.submit'); // Ajustez selon votre route GET
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
        
    })
    ->create(); // Le ->create() doit se trouver TOUJOURS à la toute fin