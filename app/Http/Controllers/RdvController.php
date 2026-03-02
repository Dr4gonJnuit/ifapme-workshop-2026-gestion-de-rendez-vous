<?php

namespace App\Http\Controllers;

use App\Models\Rdv;
use App\Models\Client;
use App\Models\Prestataire;
use App\Models\Status;
use App\Http\Requests\RdvRequest;
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

    public function index()
    {
        $rdvs = Rdv::with(['client', 'prestataire', 'status'])
            ->orderBy('date', 'desc');

        // if pagination used we need to modify the collection after retrieving
        $paginated = $rdvs->paginate(15);

        // sync status column with computed value based solely on timestamp
        // use simple comparison lte(now()) to avoid timezone confusion
        $paginated->getCollection()->transform(function (Rdv $rdv) {
            $date = $rdv->date;

            if ($date && $date->lte(now())) {
                // automatically mark past items, but never revert a future status
                if ($rdv->status_text !== 'passé') {
                    $statusId = Status::findOrCreate('passé')->id;
                    $rdv->update(['status' => $statusId]);
                    $rdv->setRelation('status', Status::find($statusId));
                }
            }

            return $rdv;
        });

        return view('pages.rdv.index', ['rdvs' => $paginated]);
    }

    public function create()
    {
        $clients = Client::all();
        $prestataires = Prestataire::all();

        return view('pages.rdv.create', compact('clients', 'prestataires'));
    }

    protected function determineStatusId(Carbon $date)
    {
        $name = $date->isPast() ? 'passé' : 'à venir';
        $status = Status::findOrCreate($name);
        return $status->id;
    }

    public function store(RdvRequest $request)
    {
        $validated = $request->validated();

        $date = Carbon::parse($validated['date']);
        $validated['user_id'] = Auth::id();
        $validated['status'] = $this->determineStatusId($date);

        Rdv::create($validated);

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous ajouté.');
    }

    public function edit(Rdv $rdv)
    {
        $clients = Client::all();
        $prestataires = Prestataire::all();

        return view('pages.rdv.edit', compact('rdv', 'clients', 'prestataires'));
    }

    public function update(RdvRequest $request, Rdv $rdv)
    {
        $validated = $request->validated();

        $date = Carbon::parse($validated['date']);
        $validated['status'] = $this->determineStatusId($date);

        $rdv->update($validated);

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous mis à jour.');
    }

    public function destroy(Rdv $rdv)
    {
        $rdv->delete();
        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous supprimé.');
    }

    /**
     * Update the status manually (à venir / passé).
     */
    public function updateStatus(Request $request, Rdv $rdv)
    {
        $validated = $request->validate([
            'status_text' => 'required|in:à venir,passé',
        ]);

        // prevent reverting from "passé" to "à venir" if a conflict would occur
        if ($validated['status_text'] === 'à venir' && $rdv->status_text === 'passé') {
            // check if changing to "à venir" would create a conflict
            $aVenirStatusId = Status::findOrCreate('à venir')->id;
            $start = $rdv->date->clone()->subMinutes(15);
            $end = $rdv->date->clone()->addMinutes(15);

            $conflict = Rdv::where('status', $aVenirStatusId)
                ->whereBetween('date', [$start, $end])
                ->where('id', '!=', $rdv->id)
                ->exists();

            if ($conflict) {
                return back()->withErrors(['status_text' => 'Impossible de reverser en "à venir" : un autre rendez-vous existe déjà dans cette plage horaire.']);
            }
        }

        $statusId = Status::findOrCreate($validated['status_text'])->id;
        $rdv->update(['status' => $statusId]);

        return redirect()->route('rdvs.index')->with('success', 'Statut mis à jour.');
    }
}
