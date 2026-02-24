<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur; // Ton modèle correspondant à la table 'utilisateur'

class RegisterController extends Controller
{
    // Affiche le formulaire
    public function showSignupForm()
    {
        return view('pages.auth.signup'); 

    // Traite l'inscription
    public function signup(Request $request)
    {
        // Validation des champs
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:utilisateur,username',
            'email' => 'nullable|email|max:255|unique:utilisateur,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Création de l'utilisateur
        Utilisateur::create([
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        // Redirection vers login
        return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès !');
    }
}