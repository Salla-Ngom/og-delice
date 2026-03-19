<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class AdminCateringController extends Controller
{
    /**
     * Liste des demandes traiteur
     */
    public function index(Request $request)
    {
        $status = $request->string('status')->toString() ?: null;

        $requests = CateringRequest::with(['user', 'respondedBy'])
            ->byStatus($status)
            ->latest()
            ->paginate(15);

        // Stats rapides
        $counts = [
            'nouvelle' => CateringRequest::where('status', 'nouvelle')->count(),
            'en_cours' => CateringRequest::where('status', 'en_cours')->count(),
            'acceptee' => CateringRequest::where('status', 'acceptee')->count(),
            'refusee'  => CateringRequest::where('status', 'refusee')->count(),
        ];

        return view('admin.catering.index', compact('requests', 'status', 'counts'));
    }

    /**
     * Détail d'une demande
     */
    public function show(CateringRequest $catering)
    {
        $catering->load(['user', 'respondedBy']);
        return view('admin.catering.show', compact('catering'));
    }

    /**
     * Changer le statut
     */
    public function updateStatus(Request $request, CateringRequest $catering): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', CateringRequest::STATUSES)],
        ]);

        $catering->status = $validated['status'];
        $catering->save();

        Cache::forget('admin.dashboard.stats');

        return back()->with('success', 'Statut mis à jour.');
    }

    /**
     * Répondre à une demande
     */
    public function respond(Request $request, CateringRequest $catering): RedirectResponse
    {
        $validated = $request->validate([
            'admin_response' => ['required', 'string', 'max:3000'],
            'status'         => ['required', 'in:' . implode(',', CateringRequest::STATUSES)],
        ]);

        $catering->admin_response = $validated['admin_response'];
        $catering->status         = $validated['status'];
        $catering->responded_by   = auth()->id();
        $catering->responded_at   = now();
        $catering->save();

        return back()->with('success', 'Réponse envoyée avec succès.');
    }

    /**
     * Supprimer une demande
     */
    public function destroy(CateringRequest $catering): RedirectResponse
    {
        $catering->delete();
        return redirect()
            ->route('admin.catering.index')
            ->with('success', 'Demande supprimée.');
    }
}
