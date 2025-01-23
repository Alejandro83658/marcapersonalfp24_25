<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActividadResource;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ActividadController extends Controller
{
public $modelclass = Actividad::class;
public $registrosTotales = 0;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

            $campos = ['apellidos', 'nombre', 'name', 'email'];
            $query = Actividad::query();
            foreach($campos as $campo) {
                $query->orWhere($campo, 'like', '%' . $request->q . '%');
            }
            return ActividadResource::collection(
                $query->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
                ->paginate($request->perPage + 1));
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $actividad = json_decode($request->getContent(), true);

        $actividad = Actividad::create($actividad);

        return new ActividadResource($actividad);
    }

    /**
     * Display the specified resource.
     */
    public function show(Actividad $actividad)
    {
        return new ActividadResource($actividad);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Actividad $actividad)
    {
        $actividadData = json_decode($request->getContent(), true);

        $actividad->update($actividadData);

        return new ActividadResource($actividad);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actividad $actividad)
    {
        try {
            $actividad->delete();
            return response()->json(null, 204);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
