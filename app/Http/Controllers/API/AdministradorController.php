<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdministradorResource;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Administrador::query();

        foreach (Administrador::$filterColumns as $column) {
            if ($request->has($column)) {
                $query->where($column, $request->input($column));
            }
        }

        return AdministradorResource::collection(
            $query->with('user')->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->perPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */ //Esto es para que no marque error en la siguiente línea
        $user = Auth::user();
        if (!$user->esAdmin()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $administrador = Administrador::create([
            'user_id' => $request->user_id,
        ]);

        return new AdministradorResource($administrador);
    }

    /**
     * Display the specified resource.
     */
    public function show(Administrador $administrador)
    {
        return new AdministradorResource($administrador);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Administrador $administrador)
    {
        $data = json_decode($request->getContent(), true);
        $administrador->update($data);
        return new AdministradorResource($administrador);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrador $administrador)
    {
        /** @var \App\Models\User $user */ //Esto es para que no marque error en la siguiente línea
        $user = Auth::user();
        if (!$user->esAdmin()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $administrador->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
