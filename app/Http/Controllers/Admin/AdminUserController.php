<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
   

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'vendeur'])
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.users.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','min:6','confirmed'],
            'role' => ['required', Rule::in(['admin','vendeur'])],
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'role'      => $request->role,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success','Utilisateur créé avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
           'role' => ['required', Rule::in(['admin','vendeur'])],
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        if ($request->filled('password')) {

            $request->validate([
                'password' => ['min:6','confirmed'],
            ]);

            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success','Utilisateur modifié avec succès');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error','Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->role === 'admin' && User::where('role','admin')->count() <= 1) {
            return back()->with('error','Impossible de supprimer le dernier administrateur.');
        }

        $user->delete();

        return back()->with('success','Utilisateur supprimé');
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */
 public function toggleStatus(User $user)
{
    if (!in_array($user->role, ['admin','vendeur'])) {
        abort(403);
    }

    $user->update([
        'is_active' => !$user->is_active
    ]);

    return back()->with('success','Statut utilisateur mis à jour');
}
}