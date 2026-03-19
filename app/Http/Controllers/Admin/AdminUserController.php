<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'vendeur', 'super_admin'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // ✅ Seul le super_admin peut créer un admin
        // Un admin simple ne peut créer que des vendeurs
        $allowedRoles = auth()->user()->role === 'super_admin'
            ? ['super_admin' => 'Super Administrateur', 'admin' => 'Administrateur', 'vendeur' => 'Vendeur']
            : ['vendeur' => 'Vendeur'];

        return view('admin.users.create', compact('allowedRoles'));
    }

    public function store(Request $request): RedirectResponse
    {
        // ✅ Rôles autorisés selon le rôle du créateur
        $allowedRoles = auth()->user()->role === 'super_admin'
            ? ['super_admin', 'admin', 'vendeur']
            : ['vendeur'];

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role'     => ['required', Rule::in($allowedRoles)],
        ]);

        // ✅ Vérification supplémentaire — un admin ne peut PAS créer un autre admin
        if ($validated['role'] === 'admin' && auth()->user()->role !== 'super_admin') {
            abort(403, 'Seul le super administrateur peut créer un administrateur.');
        }

        $user            = new User();
        $user->name      = $validated['name'];
        $user->email     = $validated['email'];
        $user->password  = Hash::make($validated['password']);
        $user->role      = $validated['role'];
        $user->is_active = true;
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        // ✅ Un admin ne peut pas modifier un super_admin ni un autre admin
        if (
            $user->role === 'super_admin' ||
            ($user->role === 'admin' && auth()->user()->role !== 'super_admin')
        ) {
            abort(403, 'Vous n\'avez pas la permission de modifier cet utilisateur.');
        }

        $allowedRoles = auth()->user()->role === 'super_admin'
            ? ['super_admin' => 'Super Administrateur', 'admin' => 'Administrateur', 'vendeur' => 'Vendeur']
            : ['vendeur' => 'Vendeur'];

        return view('admin.users.edit', compact('user', 'allowedRoles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        // ✅ Protection identique à edit()
        if (
            $user->role === 'super_admin' ||
            ($user->role === 'admin' && auth()->user()->role !== 'super_admin')
        ) {
            abort(403, 'Vous n\'avez pas la permission de modifier cet utilisateur.');
        }

        $allowedRoles = auth()->user()->role === 'super_admin'
            ? ['super_admin', 'admin', 'vendeur']
            : ['vendeur'];

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'  => ['required', Rule::in($allowedRoles)],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::min(8)->mixedCase()->numbers()];
        }

        $validated = $request->validate($rules);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // ✅ Personne ne peut supprimer un super_admin
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Le super administrateur ne peut pas être supprimé.');
        }

        // ✅ Un admin ne peut pas supprimer un autre admin
        if ($user->role === 'admin' && auth()->user()->role !== 'super_admin') {
            return back()->with('error', 'Seul le super administrateur peut supprimer un administrateur.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // ✅ Empêcher la suppression du dernier admin actif
        if (
            $user->role === 'admin' &&
            User::where('role', 'admin')->count() <= 1 &&
            User::where('role', 'super_admin')->count() === 0
        ) {
            return back()->with('error', 'Impossible de supprimer le dernier administrateur.');
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        // ✅ Personne ne peut désactiver un super_admin
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Le super administrateur ne peut pas être désactivé.');
        }

        // ✅ Un admin ne peut pas désactiver un autre admin
        if ($user->role === 'admin' && auth()->user()->role !== 'super_admin') {
            return back()->with('error', 'Seul le super administrateur peut désactiver un administrateur.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        // ✅ Empêcher la désactivation du dernier admin actif
        if (
            $user->role === 'admin' &&
            $user->is_active &&
            User::where('role', 'admin')->where('is_active', true)->count() <= 1 &&
            User::where('role', 'super_admin')->where('is_active', true)->count() === 0
        ) {
            return back()->with('error', 'Impossible de désactiver le dernier administrateur actif.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Statut mis à jour.');
    }
}
