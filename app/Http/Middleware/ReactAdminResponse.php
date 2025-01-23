<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReactAdminResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = $request->query('_start', 0);
        $end = $request->query('_end', 10);

        $perPage = $end - $start;
        $page = intval($start / $perPage) + 1;

        $request->merge([
            'perPage' => $perPage,
            'page' => $page,
        ]);

        $response = $next($request);

        if ($request->routeIs('*.index')) {
            abort_unless(property_exists($request->route()->controller, 'modelclass'), 500, "It must exist a modelclass property in the controller.");
            $modelClassName = $request->route()->controller->modelclass;

            // Aplicar filtros personalizados si existen
            $query = $modelClassName::query();
            if ($request->has('filter')) {
                $filters = json_decode($request->query('filter'), true);
                foreach ($filters as $field => $value) {
                    $query->where($field, 'like', "%$value%");
                }
            }

            // Contar el número total de registros después de aplicar los filtros
            $totalCount = $query->count();
            $response->header('X-Total-Count', $totalCount);
        }

        return $response;
    }
}
