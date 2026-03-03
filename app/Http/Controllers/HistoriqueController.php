<?php

namespace App\Http\Controllers;

use App\Models\Rdv;
use Illuminate\Support\Facades\Auth;

class HistoriqueController extends Controller
{
    public function index()
    {
        $rdvs = Rdv::with(['client', 'prestataire'])
            ->where('user_id', Auth::id())
            ->where('start_time', '<', now()) // uniquement les RDV passés
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('pages.historiques.historique', compact('rdvs'));
    }
}