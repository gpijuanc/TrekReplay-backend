<?php

namespace App\Http\Controllers;

use App\Models\Usuari; // Importem el nostre model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Per encriptar contrasenyes
use Illuminate\Support\Facades\Validator; // Per validar dades

class AuthController extends Controller
{
    /**
     * Registre d'un nou usuari (Comprador o Venedor).
     */
    public function register(Request $request)
    {
        // 1. Validació de les dades rebudes des d'Angular
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'correu' => 'required|string|email|max:255|unique:usuaris',
            'contrasenya' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:rols,id', // Ha de ser 2 (Venedor) o 3 (Comprador)
            'OTA' => 'boolean' // Opcional, per si és una agència
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // 2. Creació de l'usuari
        $usuari = Usuari::create([
            'nom' => $request->nom,
            'correu' => $request->correu,
            'contrasenya' => Hash::make($request->contrasenya),
            'role_id' => $request->role_id,
            'OTA' => $request->OTA ?? false,
        ]);

        // 3. (Opcional) Crear un token i loguejar-lo directament
        $token = $usuari->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuari registrat correctament',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $usuari
        ], 201);
    }

    /**
     * Login d'un usuari existent.
     */
    public function login(Request $request)
    {
        // 1. Validació de les dades
        $validator = Validator::make($request->all(), [
            'correu' => 'required|email',
            'contrasenya' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // 2. Comprovar si l'usuari existeix
        $usuari = Usuari::where('correu', $request['correu'])->first();

        // 3. Comprovar si l'usuari existeix i la contrasenya és correcta
        if (! $usuari || ! Hash::check($request['contrasenya'], $usuari->contrasenya)) {
            return response()->json(['message' => 'Credencials incorrectes'], 401);
        }

        // 4. Crear i retornar el token
        $token = $usuari->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login correcte',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $usuari // Enviem les dades de l'usuari a Angular
        ], 200);
    }
}