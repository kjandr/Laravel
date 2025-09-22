<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    //
    // 👉 Admin-Routen
    //
    Route::middleware(['role:admin'])->group(function () {

        // Benutzerübersicht
        Route::get('/admin/users', function () {
            $users = User::orderBy('created_at', 'desc')->get();
            return view('admin.users', compact('users'));
        })->name('admin.users.index');

        // Rolle ändern (inkl. Deaktivieren = NULL)
        Route::put('/admin/users/{user}/role', function (Request $request, User $user) {
            $data = $request->validate([
                'role' => ['nullable','in:anwender,haendler,admin'],
            ]);

            if ($user->id === auth()->id() && ($data['role'] ?? null) !== 'admin') {
                return back()->with('error', 'Du kannst deine eigene Admin-Rolle nicht entfernen.');
            }

            $user->role = $data['role'] ?? null; // null = deaktiviert
            $user->save();

            return back()->with('success', "Rolle für {$user->email} wurde geändert zu: ".($user->role ?? 'deaktiviert'));
        })->name('admin.users.role');

        // Löschen
        Route::delete('/admin/users/{user}', function (User $user) {
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Du kannst dich nicht selbst löschen.');
            }
            $email = $user->email;
            $user->delete();
            return back()->with('success', "User {$email} wurde gelöscht.");
        })->name('admin.users.destroy');

    });
});
