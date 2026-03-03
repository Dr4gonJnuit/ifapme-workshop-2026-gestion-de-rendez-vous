<?php

namespace App\Http\Controllers;

use App\Models\Rdv;
use App\Models\Client;
use App\Models\Prestataire;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class RdvController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vérifie s'il y a un chevauchement avec un rendez-vous existant pour le même prestataire.
     *
     * @param string $prestataireId
     * @param Carbon $start
     * @param Carbon $end
     * @param string|null $excludeRdvId
     * @return bool
     */
    private function hasOverlap($prestataireId, Carbon $start, Carbon $end, $excludeRdvId = null)
    {
        $query = Rdv::where('prestataire_id', $prestataireId)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_time', '<=', $start)
                         ->where('end_time', '>=', $end);
                  });
            });

        if ($excludeRdvId) {
            $query->where('id', '!=', $excludeRdvId);
        }

        return $query->exists();
    }

    public function index(Request $request)
    {
        $query = Rdv::with(['client', 'prestataire'])
                    ->where('start_time', '>=', Carbon::now('UTC'));

        if ($request->filled('prestataire_id')) {
            $query->where('prestataire_id', $request->prestataire_id);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('start_time', '>=', Carbon::parse($request->date_debut));
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('start_time', '<=', Carbon::parse($request->date_fin));
        }

        $order = $request->get('order', 'start_time');
        $direction = $request->get('direction', 'asc');
        $allowedOrders = ['start_time', 'client_id', 'prestataire_id'];
        if (!in_array($order, $allowedOrders)) {
            $order = 'start_time';
        }
        $query->orderBy($order, $direction);

        $rdvs = $query->paginate(15)->appends($request->query());

        $prestataires = Prestataire::orderBy('lastname')->orderBy('firstname')->get();
        $clients = Client::orderBy('lastname')->orderBy('firstname')->get();

        return view('pages.rdv.index', compact('rdvs', 'prestataires', 'clients'));
    }


    public function create()
    {
        $clients = Client::all();
        $prestataires = Prestataire::all();

        return view('pages.rdv.create', compact('clients', 'prestataires'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'start_time'      => 'required|date|after:now',
        'duration'        => 'required|integer|min:1',
        'client_id'       => 'required|exists:client,id',
        'prestataire_id'  => 'required|exists:prestataire,id',
    ], [
        'start_time.after' => 'La date et heure de début doit être dans le futur.',
    ]);

        $start = Carbon::parse($validated['start_time']);
        $duration = (int) $validated['duration'];
        $end = $start->copy()->addMinutes($duration);

        if ($this->hasOverlap($validated['prestataire_id'], $start, $end)) {
            return back()->withErrors(['start_time' => 'Ce créneau horaire est déjà occupé pour ce prestataire.'])->withInput();
        }

        $rdvData = [
            'start_time'      => $start,
            'end_time'        => $end,
            'client_id'       => $validated['client_id'],
            'prestataire_id'  => $validated['prestataire_id'],
            'user_id'         => Auth::id(),
        ];

        Rdv::create($rdvData);

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous ajouté.');
    }

    public function edit(Rdv $rdv)
    {
        $clients = Client::all();
        $prestataires = Prestataire::all();

        return view('pages.rdv.edit', compact('rdv', 'clients', 'prestataires'));
    }

   public function update(Request $request, Rdv $rdv)
{
    $validated = $request->validate([
        'start_time'      => 'required|date|after:now',
        'duration'        => 'required|integer|min:1',
        'client_id'       => 'required|exists:client,id',
        'prestataire_id'  => 'required|exists:prestataire,id',
    ], [
        'start_time.after' => 'La date et heure de début doit être dans le futur.',
    ]);

        $start = Carbon::parse($validated['start_time']);
        $duration = (int) $validated['duration'];
        $end = $start->copy()->addMinutes($duration);

        if ($this->hasOverlap($validated['prestataire_id'], $start, $end, $rdv->id)) {
            return back()->withErrors(['start_time' => 'Ce créneau horaire est déjà occupé pour ce prestataire.'])->withInput();
        }

        $rdvData = [
            'start_time'      => $start,
            'end_time'        => $end,
            'client_id'       => $validated['client_id'],
            'prestataire_id'  => $validated['prestataire_id'],
        ];

        $rdv->update($rdvData);

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous mis à jour.');
    }

    public function destroy(Rdv $rdv)
    {
        $rdv->delete();
        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous supprimé.');
    }

    public function updateStatus(Request $request, Rdv $rdv)
    {
        $validated = $request->validate([
            'status_text' => 'required|in:à venir,passé',
        ]);

        if ($validated['status_text'] === 'à venir' && $rdv->status_text === 'passé') {
            $aVenirStatusId = Status::firstOrCreate(['name' => 'à venir'])->id;
            $start = $rdv->start_time->clone()->subMinutes(15);
            $end   = $rdv->start_time->clone()->addMinutes(15);

            $conflict = Rdv::where('status', $aVenirStatusId)
                ->whereBetween('start_time', [$start, $end])
                ->where('id', '!=', $rdv->id)
                ->exists();

            if ($conflict) {
                return back()->withErrors(['status_text' => 'Impossible de repasser en "à venir" : un autre rendez-vous existe déjà dans cette plage horaire.']);
            }
        }

        $statusId = Status::firstOrCreate(['name' => $validated['status_text']])->id;
        $rdv->update(['status' => $statusId]);

        return redirect()->route('rdvs.index')->with('success', 'Statut mis à jour.');
    }
}