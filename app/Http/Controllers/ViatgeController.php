<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viatge;
use App\Models\PlataformesAfiliat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DOMDocument;
use Illuminate\Routing\Controller;

class ViatgeController extends Controller
{
    /**
     * Carrega els viatges publicats
     */
    public function index()
    {
        $viatges = Viatge::where('publicat', true)
                         ->with('venedor')
                         ->orderBy('created_at', 'desc')
                         ->get();
        
        return response()->json($viatges, 200);
    }

    /**
     * Desa un viatge nou 
     */
    public function store(Request $request)
    {
        $usuari = Auth::user();

        if ($usuari->role_id != 2) {
            return response()->json(['message' => 'Accés no autoritzat. Només els venedors poden crear viatges.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'titol' => 'required|string|max:255',
            'blog' => 'required|string',
            'pais' => 'required|array', 
            'pais.*' => 'string',
            'tipus_viatge' => 'required|in:Paquet Tancat,Afiliats',
            'preu' => 'nullable|numeric|min:0',
            'imatge_principal' => 'nullable|string',
            'publicat' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $processedBlog = $request->blog;

        // Creació del viatge
        $viatge = Viatge::create([
            'usuari_id' => $usuari->id,
            'titol' => $request->titol,
            'blog' => $processedBlog,
            'pais' => $request->pais, 
            'tipus_viatge' => $request->tipus_viatge,
            'preu' => ($request->tipus_viatge == 'Paquet Tancat') ? $request->preu : null,
            'imatge_principal' => $request->imatge_principal,
            'publicat' => $request->publicat,
        ]);

        return response()->json([
            'message' => 'Viatge creat correctament',
            'data' => $viatge
        ], 201);
    }

    /**
     * Rep una URL i una Plataforma, i retorna la URL d'afiliat final.
     */
    public function generarEnllacAfiliat(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'plataforma' => 'required|string|exists:plataformes_afiliats,empresa'
        ]);

        $usuari = Auth::user();
        
        $regla = PlataformesAfiliat::where('empresa', $request->plataforma)->first();

        $plantilla = $regla->url_template;
        $plantillaProcessada = str_replace('{PLATFORM_ID}', $regla->platform_affiliate_id, $plantilla);
        $plantillaProcessada = str_replace('{CREATOR_ID}', $usuari->id, $plantillaProcessada);

        $separator = (strpos($request->url, '?') === false) ? '?' : '&';
        $novaUrl = $request->url . $separator . $plantillaProcessada;

        return response()->json(['url' => $novaUrl]);
    }

    /**
     * Actualitza un viatge existent 
     */
    public function update(Request $request, Viatge $viatge)
    {
        $usuari = Auth::user();

        if ($usuari->id !== $viatge->usuari_id) {
            return response()->json(['message' => 'Accés no autoritzat'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'titol' => 'string|max:255',
            'blog' => 'string',
            'pais' => 'array',
            'pais.*' => 'string',
            'tipus_viatge' => 'in:Paquet Tancat,Afiliats',
            'preu' => 'nullable|numeric|min:0',
            'imatge_principal' => 'nullable|string',
            'publicat' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $dadesPerActualitzar = $request->only([
            'titol', 'pais', 'tipus_viatge', 'preu', 'imatge_principal', 'publicat'
        ]);

        if ($request->has('blog')) {
            $processedBlog = $request->blog;           
            $dadesPerActualitzar['blog'] = $processedBlog;
        }

        if (isset($dadesPerActualitzar['tipus_viatge']) && $dadesPerActualitzar['tipus_viatge'] == 'Afiliats') {
            $dadesPerActualitzar['preu'] = null;
        }
        $viatge->update($dadesPerActualitzar);

        return response()->json([
            'message' => 'Viatge actualitzat correctament',
            'data' => $viatge
        ], 200);
    }

    /**
     * Esborra un viatge 
     */
    public function destroy(Viatge $viatge)
    {
        $usuari = Auth::user();

        if ($usuari->id !== $viatge->usuari_id) {
            return response()->json(['message' => 'Accés no autoritzat'], 403);
        }

        $viatge->delete();

        return response()->json(['message' => 'Viatge esborrat correctament'], 200);
    }

    /**
     * Mostra un viatge específic
     */
    public function show(Viatge $viatge)
    {
        return response()->json($viatge->load(['venedor', 'fotos']), 200);
    }

    /**
     * Puja la imatge principal (Portada) d'un viatge.
     */
    public function uploadImatgePrincipal(Request $request, Viatge $viatge)
    {
        if (Auth::id() !== $viatge->usuari_id) {
            return response()->json(['message' => 'Accés no autoritzat'], 403);
        }

        $request->validate([
            'imatge_principal' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $path = $request->file('imatge_principal')->store('viatges/portades', 'public');

        $viatge->imatge_principal = $path;
        $viatge->save();

        return response()->json([
            'message' => 'Imatge principal pujada correctament',
            'path' => $path
        ], 200);
    }

    /**
     * Puja una foto addicional a la galeria (taula 'viatge_fotos').
     */
    public function uploadFotoGaleria(Request $request, Viatge $viatge)
    {
        if (Auth::id() !== $viatge->usuari_id) {
            return response()->json(['message' => 'Accés no autoritzat'], 403);
        }

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alt_text' => 'nullable|string'
        ]);

        $path = $request->file('foto')->store('viatges/galeria', 'public');
        $fullPath = '/storage/' . $path;

        $viatge->fotos()->create([
            'imatge_url' => $path,
            'alt_text' => $request->alt_text ?? 'Imatge de galeria'
        ]);

        $totalFotos = $viatge->fotos()->count();

        if ($totalFotos === 1 || empty($viatge->imatge_principal)) {
            $viatge->imatge_principal = $fullPath;
            $viatge->save();
        }

        return response()->json(['message' => 'Foto afegida a la galeria', 'path' => $path], 201);
    }

    /**
     * Mostra només els viatges de l'usuari autenticat (Venedor).
     */
    public function myViatges()
    {
        $usuari = Auth::user();

        if ($usuari->role_id != 2) {
            return response()->json(['message' => 'Accés no autoritzat'], 403);
        }

        $viatges = Viatge::where('usuari_id', $usuari->id)
                         ->orderBy('created_at', 'desc')
                         ->get();
        
        return response()->json($viatges, 200);
    }
}