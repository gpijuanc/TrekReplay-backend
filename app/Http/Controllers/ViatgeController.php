<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viatge; // Model de Viatge
use App\Models\PlataformesAfiliat; // Model de Plataformes
use Illuminate\Support\Facades\Auth; // Per gestionar l'usuari autenticat
use Illuminate\Support\Facades\Validator;
use DOMDocument; // Per processar l'HTML
use Illuminate\Routing\Controller;

class ViatgeController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Mostra tots els viatges publicats - per als Compradors)
     */
    public function index()
    {
        // Retornem només els viatges publicats, ordenats pels més nous
        $viatges = Viatge::where('publicat', true)
                         ->with('venedor') // Opcional: per saber qui és el venedor
                         ->orderBy('created_at', 'desc')
                         ->get();
        
        return response()->json($viatges, 200);
    }


    /**
     * Store a newly created resource in storage.
     * (Desa un viatge nou - per als Venedors)
     */
    public function store(Request $request)
    {
        // 1. Obtenim l'usuari autenticat (el Venedor)
        $usuari = Auth::user();

        // 2. Validació de seguretat: Només els Venedors (role_id 2) poden crear
        if ($usuari->role_id != 2) {
            return response()->json(['message' => 'Accés no autoritzat. Només els venedors poden crear viatges.'], 403);
        }

        // 3. Validació de les dades del formulari
        $validator = Validator::make($request->all(), [
            'titol' => 'required|string|max:255',
            'blog' => 'required|string',
            'tipus_viatge' => 'required|in:Paquet Tancat,Afiliats',
            'preu' => 'nullable|numeric|min:0', // Preu és opcional
            'imatge_principal' => 'nullable|string', // De moment, assumim que és un path/url
            'publicat' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // 4. Lògica d'Afiliats (El teu repte tècnic)
        // Només processem enllaços si el viatge és de tipus "Afiliats"
        $processedBlog = $request->blog;
        if ($request->tipus_viatge == 'Afiliats') {
            $processedBlog = $this->processarEnllaçosAfiliats($request->blog, $usuari->id);
        }

        // 5. Creació del viatge a la Base de Dades
        $viatge = Viatge::create([
            'usuari_id' => $usuari->id,
            'titol' => $request->titol,
            'blog' => $processedBlog, // Guardem l'HTML ja processat
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
     * AQUESTA ÉS LA FUNCIÓ PRIVADA FINAL PER PROCESSAR L'HTML
     * Busca els enllaços marcats (data-affiliate="true") i els modifica
     * utilitzant les plantilles de la BD.
     */
private function processarEnllaçosAfiliats($html, $creatorId)
    {
        $plataformes = PlataformesAfiliat::all();

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $enllaços = $dom->getElementsByTagName('a');
        $urlsModificades = [];

        foreach (iterator_to_array($enllaços) as $enllaç) {
            $urlOriginal = $enllaç->getAttribute('href');

            if ($enllaç->getAttribute('data-affiliate') === 'true' && !isset($urlsModificades[$urlOriginal])) {
                
                $plataformaNom = $enllaç->getAttribute('data-platform');
                $regla = $plataformes->firstWhere('empresa', $plataformaNom);

                if ($regla) {
                    
                    // 1. Reemplacem les etiquetes de la plantilla
                    $plantilla = $regla->url_template;
                    $plantillaProcessada = str_replace('{PLATFORM_ID}', $regla->platform_affiliate_id, $plantilla);
                    $plantillaProcessada = str_replace('{CREATOR_ID}', $creatorId, $plantillaProcessada);

                    // 2. AQUESTA ÉS LA LÒGICA CORREGIDA:
                    // Comprovem si la URL Original ja té paràmetres (?)
                    $separator = (strpos($urlOriginal, '?') === false) ? '?' : '&';

                    // 3. Construïm la nova URL (Afegint, NO reemplaçant)
                    $novaUrl = $urlOriginal . $separator . $plantillaProcessada;
                    
                    $enllaç->setAttribute('href', $novaUrl);
                    $enllaç->setAttribute('target', '_blank');
                    $enllaç->setAttribute('rel', 'nofollow noopener noreferrer sponsored');
                    
                    $urlsModificades[$urlOriginal] = $novaUrl;
                }
            }
            else if (isset($urlsModificades[$urlOriginal])) {
                 $enllaç->setAttribute('href', $urlsModificades[$urlOriginal]);
                 $enllaç->setAttribute('target', '_blank');
                 $enllaç->setAttribute('rel', 'nofollow noopener noreferrer sponsored');
            }
        }

        return $dom->saveHTML();
    }

    // De moment deixem les altres funcions (show, update, destroy) buides
}