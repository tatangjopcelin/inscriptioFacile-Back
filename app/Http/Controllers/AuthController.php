<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel élève.
     */
    public function register(Request $request)
    {
        // Validation des données entrantes
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'email' => 'required|string|email|max:255|unique:students',
            'classe'=>'required|string|max:500',
            'password' => 'required|string|min:6',
        ]);

        // Création de l'élève
        $student = Student::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'classe'=>$request->classe,
            'password' => Hash::make($request->password),
        ]);

        // Génération du token d'authentification
        $token = $student->createToken('auth_token')->plainTextToken;

        // Réponse JSON avec le token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Inscription réussie',
            'student' => $student,
        ], 201);
    }

  
    public function login(Request $request)
    {
        // Validation des données entrantes
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Recherche de l'élève par email
        $student = Student::where('email', $request->email)->first();

        // Vérification du mot de passe
        if (!$student || !Hash::check($request->password, $student->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification sont incorrectes.'],
            ]);
        }

        // Génération du token d'authentification
        $token = $student->createToken('auth_token')->plainTextToken;

        // Réponse JSON avec le token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Connexion réussie',
            'student' => $student,
        ]);
    }

    /**
     * Déconnexion de l'élève.
     */
    public function logout(Request $request)
    {
        // Suppression du token actuel
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
    public function getEtudiant(Request $request)
    {
        return response()->json(['student' => $request->user()], 200);
    }

    public function updateEtudiant(Request $request)
    {
        $student = $request->user();

        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:eleves,email,' . $student->id,
            'classe' => 'sometimes|string|max:255',
        ]);

        $student->update($request->only(['nom', 'prenom', 'adresse', 'email', 'classe']));

        return response()->json(['success' => true, 'student' => $student], 200);
    }

  
}
