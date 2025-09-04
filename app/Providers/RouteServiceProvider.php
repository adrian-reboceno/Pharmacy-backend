<?php

namespace App\Providers;

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * La ruta a la "home" para redirecciones después del login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define la configuración de enrutamiento para la aplicación.
     */
    public function boot(): void
    {
        // Puedes agregar bindings o patrones de rutas aquí si es necesario
        $this->configureRateLimiting();

        $this->routes(function () {
            // Rutas Web
            Route::middleware('web')
                 ->group(base_path('routes/web.php'));

            // Rutas API
            Route::middleware('api')        // aplica middleware api
                 ->prefix('api')            // todas las rutas comienzan con /api
                 ->group(base_path('routes/api.php'));
            Route::aliasMiddleware('jwt.verify', JwtMiddleware::class);
        });
    }

    /**
     * Configura limitación de velocidad para rutas API si quieres.
     */
    protected function configureRateLimiting(): void
    {
        // ejemplo de rate limit
        // RateLimiter::for('api', function (Request $request) {
        //     return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        // });
    }
}
