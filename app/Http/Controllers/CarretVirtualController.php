<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarretVirtual;
use App\Models\Viatge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller; // Assegura't que aquest 'use' hi és

class CarretVirtualController extends Controller
{
    /**
     * Mostra els items del carret de l'usuari autenticat.
     */
    public function index()
    {
        $usuariId = Auth::id();
        $items = CarretVirtual::where('usuari_id', $usuariId)
                              ->with('viatge') // Carreguem la info del viatge
                              ->get();

        return response()->json($items, 200);
    }

    /**
     * Afegeix un Paquet Tancat al carret.
     */
    public function store(Request $request)
    {
        $usuari = Auth::user();

        // 1. Validació de seguretat: Només els Compradors (role_id 3) poden afegir
        if ($usuari->role_id != 3) {
            return response()->json(['message' => 'Només els compradors poden afegir items al carret.'], 403);
        }

        // 2. Validació de dades
        $validator = Validator::make($request->all(), [
            'viatge_id' => 'required|integer|exists:viatge,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // 3. Comprovem que el viatge sigui un "Paquet Tancat"
        $viatge = Viatge::find($request->viatge_id);
        if ($viatge->tipus_viatge !== 'Paquet Tancat') {
            return response()->json(['message' => 'Només es poden afegir Paquets Tancats al carret.'], 400);
        }
        
        // 4. (Opcional) Comprovem si ja existeix per no duplicar-lo
        $existent = CarretVirtual::where('usuari_id', $usuari->id)
                                 ->where('viatge_id', $viatge->id)
                                 ->first();
        
        if ($existent) {
            return response()->json(['message' => 'Aquest item ja és al teu carret.'], 409); // 409 Conflict
        }

        // 5. Creem l'ítem al carret
        $item = CarretVirtual::create([
            'usuari_id' => $usuari->id,
            'viatge_id' => $viatge->id,
            'temps_afegit' => now() // Utilitzem el camp 'temps_afegit'
        ]);

        return response()->json([
            'message' => 'Item afegit al carret.',
            'data' => $item
        ], 201);
    }

    /**
     * Esborra un ítem del carret.
     * Nota: Passem el 'viatge_id' (no l'ID del carret) per simplicitat.
     */
    public function destroy($viatge_id)
    {
        $usuariId = Auth::id();

        // Busquem l'ítem del carret que pertany a aquest usuari I a aquest viatge
        $item = CarretVirtual::where('usuari_id', $usuariId)
                             ->where('viatge_id', $viatge_id)
                             ->first();

        if (!$item) {
            return response()->json(['message' => 'Item no trobat al carret.'], 404);
        }

        // Esborrem l'ítem
        $item->delete();

        return response()->json(['message' => 'Item esborrat del carret.'], 200);
    }
}
