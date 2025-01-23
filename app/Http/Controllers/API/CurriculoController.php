<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurriculoResource;
use App\Models\Curriculo;
use Illuminate\Http\Request;

class CurriculoController extends Controller
{
    public $modelclass = Curriculo::class;
public $registrosTotales = 0;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

            $campos = ['apellidos', 'nombre', 'name', 'email'];
            $query = Curriculo::query();
            foreach($campos as $campo) {
                $query->orWhere($campo, 'like', '%' . $request->q . '%');
            }
            return CurriculoResource::collection(
                $query->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
                ->paginate($request->perPage + 1));
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $curriculo = json_decode($request->getContent(), true);
        $curriculo = Curriculo::create($curriculo);

        return new CurriculoResource($curriculo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Curriculo $curriculo)
    {
        return new CurriculoResource($curriculo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curriculo $curriculo)
    {
        $curriculoData = json_decode($request->getContent(), true);
        $curriculo->update($curriculoData);

        return new CurriculoResource($curriculo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curriculo $curriculo)
    {
        try {
            $curriculo->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
