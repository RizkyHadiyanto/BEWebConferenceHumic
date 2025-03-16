<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
    

// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;
// use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         api: __DIR__.'/../routes/api.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware) {
//         //
//     })
//     ->withExceptions(function (Exceptions $exceptions) {
//         //
//     })->create();

//     $app->router->group([
//         'middleware' => [
//             EnsureFrontendRequestsAreStateful::class,
//             'throttle:api',
//             \Illuminate\Routing\Middleware\SubstituteBindings::class,
//         ],
//     ], function ($router) {
//         require base_path('routes/api.php');
//     });

// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;
// use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
// use Laravel\Sanctum\Http\Middleware\SuperAdminMiddleware;
// use App\Http\Middleware\RoleMiddleware;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         api: __DIR__.'/../routes/api.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware) {
//         // Middleware untuk API

        
//         // $middleware->group('api', [
//         //     EnsureFrontendRequestsAreStateful::class, // Sanctum Middleware
//         //     'throttle:api',
//         //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
//         // ]);

//         // Middleware untuk web
//         $middleware->group('web', [
//             \Illuminate\Session\Middleware\StartSession::class,
//             \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//             \App\Http\Middleware\SuperAdminMiddleware::class, // Tambahkan middleware SuperAdmin
//         ]);

//         $middleware->group('api', [
//             EnsureFrontendRequestsAreStateful::class, // Sanctum Middleware
//             'throttle:api',
//             \Illuminate\Routing\Middleware\SubstituteBindings::class,
//             RoleMiddleware::class, // Tambahkan RoleMiddleware
//         ]);

        
        
//     })


    // ->withExceptions(function (Exceptions $exceptions) {
    //     //
    // })->create();

