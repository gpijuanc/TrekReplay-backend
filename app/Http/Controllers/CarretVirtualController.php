<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarretVirtual;
use App\Models\Viatge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class CarretVirtualController extends Controller
{
    /**
     * Mostra els items del carret de l'usuari autenticat.
     */
    public function index()
    {
        $usuariId = Auth::id();
        $items = CarretVirtual::where('usuari_id', $usuariId)
                              ->with('viatge')  //Eager Loading? funciona no tocar
                              ->get();

        return response()->json($items, 200);
    }

    /**
     * Afegeix un Paquet Tancat al carret.
     */
    public function store(Request $request)
    {
        $usuari = Auth::user();

        if ($usuari->role_id != 3) {
            return response()->json(['message' => 'Només els compradors poden afegir items al carret.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'viatge_id' => 'required|integer|exists:viatge,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $viatge = Viatge::find($request->viatge_id);
        if ($viatge->tipus_viatge !== 'Paquet Tancat') {
            return response()->json(['message' => 'Només es poden afegir Paquets Tancats al carret.'], 400);
        }
        
        $existent = CarretVirtual::where('usuari_id', $usuari->id)
                                 ->where('viatge_id', $viatge->id)
                                 ->first();
        
        if ($existent) {
            return response()->json(['message' => 'Aquest item ja és al teu carret.'], 409); // 409 Conflict
        }

        $item = CarretVirtual::create([
            'usuari_id' => $usuari->id,
            'viatge_id' => $viatge->id,
            'temps_afegit' => now() 
        ]);

        return response()->json([
            'message' => 'Item afegit al carret.',
            'data' => $item
        ], 201);
    }

    /**
     * Esborra un viatge del carret.
     */
    public function destroy($viatge_id)
    {
        $usuariId = Auth::id();

        $item = CarretVirtual::where('usuari_id', $usuariId)
                             ->where('viatge_id', $viatge_id)
                             ->first();

        if (!$item) {
            return response()->json(['message' => 'Item no trobat al carret.'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Item esborrat del carret.'], 200);
    }
}
