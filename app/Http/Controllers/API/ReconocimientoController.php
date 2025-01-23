<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReconocimientoResource;
use App\Models\Reconocimiento;
use Illuminate\Http\Request;

class ReconocimientoController extends Controller
{
    public $modelclass = Reconocimiento::class;
public $registrosTotales = 0;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

            $campos = ['apellidos', 'nombre', 'name', 'email'];
            $query = Reconocimiento::query();
            foreach($campos as $campo) {
                $query->orWhere($campo, 'like', '%' . $request->q . '%');
            }
            return ReconocimientoResource::collection(
                $query->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
                ->paginate($request->perPage + 1));
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Reconocimiento = json_decode($request->getContent(), true);

        $Reconocimiento = Reconocimiento::create($Reconocimiento);

        return new ReconocimientoResource($Reconocimiento);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reconocimiento $Reconocimiento)
    {
        return new ReconocimientoResource($Reconocimiento);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reconocimiento $Reconocimiento)
    {
        $ReconocimientoData = json_decode($request->getContent(), true);
        $Reconocimiento->update($ReconocimientoData);

        return new ReconocimientoResource($Reconocimiento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reconocimiento $Reconocimiento)
    {
        try {
            $Reconocimiento->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
