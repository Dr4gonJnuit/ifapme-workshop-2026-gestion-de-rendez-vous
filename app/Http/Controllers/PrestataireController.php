<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestataire;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PrestataireController extends Controller
{

    public function index(Request $request)
    {
        $query = Prestataire::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $prestataires = $query->orderBy('lastname')->paginate(10);

        $prestataires->appends($request->all());

        return view('pages.from.prestataire', compact('prestataires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname'  => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'email'      => 'nullable|email|max:255|unique:prestataire,email',
            'phone'      => 'nullable|string|max:20|unique:prestataire,phone',
        ]);

        $prestataire = new Prestataire($validated);
        $prestataire->id = (string) Str::uuid(); // UUID comme clé primaire
        $prestataire->save();

        return redirect()->route('prestataire.index')
                         ->with('success', 'Prestataire ajouté avec succès.');
    }

    public function update(Request $request, $id)
    {
        $prestataire = Prestataire::findOrFail($id);

        $validated = $request->validate([
            'firstname'  => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'email'      => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('prestataire')->ignore($prestataire->id, 'id')
            ],
            'phone'      => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('prestataire')->ignore($prestataire->id, 'id')
            ],
        ]);

        $prestataire->update($validated);

        return redirect()->route('prestataire.index')
                         ->with('success', 'Prestataire mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $prestataire = Prestataire::findOrFail($id);
        $prestataire->delete();

        return redirect()->route('prestataire.index')
                         ->with('success', 'Prestataire supprimé avec succès.');
    }
}