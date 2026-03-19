<?php

namespace App\Http\Controllers;

use App\Models\CateringRequest;
use App\Notifications\NewCateringRequestNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CateringRequestController extends Controller
{
    /**
     * Formulaire public — accessible à tous (connecté ou non)
     */
    public function create(): View
    {
        $eventTypes = CateringRequest::EVENT_TYPES;
        return view('traiteur.create', compact('eventTypes'));
    }

    /**
     * Enregistrer la demande
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'event_type' => ['required', 'in:' . implode(',', array_keys(CateringRequest::EVENT_TYPES))],
            'event_date' => ['required', 'date', 'after:today'],
            'guests'     => ['required', 'integer', 'min:10', 'max:5000'],
            'budget'     => ['nullable', 'integer', 'min:0'],
            'message'    => ['nullable', 'string', 'max:2000'],
        ], [
            'event_date.after' => 'La date de l\'événement doit être dans le futur.',
            'guests.min'       => 'Le nombre minimum de personnes est 10.',
        ]);

        $catering = new CateringRequest();
        $catering->fill($validated);

        // Lier au compte si connecté
        if (auth()->check()) {
            $catering->user_id = auth()->id();
        }

        $catering->status = 'nouvelle';
        $catering->save();

        // Notifier tous les admins
        User::where('role', 'admin')->get()
            ->each(fn($admin) => $admin->notify(
                new NewCateringRequestNotification($catering)
            ));

        return redirect()
            ->route('traiteur.confirmation')
            ->with('catering_ref', $catering->id);
    }

    /**
     * Page de confirmation après soumission
     */
    public function confirmation(): View
    {
        return view('traiteur.confirmation');
    }
}
