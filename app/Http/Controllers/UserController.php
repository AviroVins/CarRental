<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(): View
    {
        $users = DB::table('users')->get();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        DB::table('users')->insert($validated);

        return redirect()->route('users.index')->with('success', 'Użytkownik dodany.');
    }

    public function edit($id): View
    {
        $user = DB::table('users')->where('user_id', $id)->first();
        abort_unless($user, 404);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$id,user_id",
            'role' => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->input('password'));
        }

        DB::table('users')->where('user_id', $id)->update($validated);

        return redirect()->route('users.index')->with('success', 'Użytkownik zaktualizowany.');
    }

    public function destroy($id): RedirectResponse
    {
        DB::table('users')->where('user_id', $id)->delete();
        return redirect()->route('users.index')->with('success', 'Użytkownik usunięty.');
    }
}
