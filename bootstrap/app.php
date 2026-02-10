<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load module routes with override capability
            // Modules can override core routes when their feature flags are enabled
            $modulesPath = base_path('Modules');
            if (is_dir($modulesPath)) {
                foreach (glob("{$modulesPath}/*/Routes/web.php") as $routeFile) {
                    require $routeFile;
                }
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'superadmin' => \App\Http\Middleware\EnsureUserIsSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Theme-aware error page rendering
        // Resolution order:
        // 1. resources/themes/{active_theme}/views/errors/{status}.blade.php
        // 2. resources/views/errors/{status}.blade.php (Laravel default fallback)
        $exceptions->respond(function ($response, Throwable $e, Request $request) {
            // Only handle HTTP exceptions (404, 403, 419, 500, etc.)
            if (!($e instanceof HttpExceptionInterface)) {
                return $response;
            }

            $status = $e->getStatusCode();
            
            // Get active theme from config
            $theme = config('theme.active', 'classic');
            $themeDirectory = config('theme.directory', 'themes');
            
            // Build themed error view path
            $themedView = "errors.{$status}";
            $themedViewPath = resource_path("{$themeDirectory}/{$theme}/views/errors/{$status}.blade.php");
            
            // If themed error view exists, render it
            if (file_exists($themedViewPath)) {
                return response()->view($themedView, [
                    'exception' => $e,
                ], $status);
            }
            
            // Otherwise, let Laravel handle with default error views
            return $response;
        });
    })->create();
