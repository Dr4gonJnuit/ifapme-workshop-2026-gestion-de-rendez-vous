<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;

class RegisterController extends Controller
{
    // Affiche le formulaire d'inscription
    public function showSignupForm()
    {
        return view('pages.auth.signup'); // Assure-toi que signup.blade.php existe
    }

    // Traite l'inscription
    public function signup(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:utilisateur,username',
            'email' => 'nullable|email|max:255|unique:utilisateur,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Utilisateur::create([
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès !');
    }
}