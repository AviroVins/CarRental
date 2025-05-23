<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class UserController extends Controller
{
    protected $primaryKey = 'user_id';
    private $routePrefix = 'users';
    private $title = 'Użytkownicy';

    public function index()
    {
        $items = User::all();
        $columns = ['user_id', 'first_name', 'last_name', 'email', 'phone_number', 'role'];

        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => $this->routePrefix,
            'title' => $this->title,
        ]);
    }

    public function create()
    {
        $columns = ['first_name', 'last_name', 'email', 'phone_number', 'role'];

        return view('shared.form', [
            'item' => null,
            'columns' => $columns,
            'routePrefix' => $this->routePrefix,
            'title' => $this->title,
            'mode' => 'create',
        ]);
    }

    public function edit(User $user)
    {
        $columns = ['user_id', 'first_name', 'last_name', 'email', 'phone_number', 'role'];

        return view('shared.form', [
            'item' => $user,
            'columns' => $columns,
            'routePrefix' => $this->routePrefix,
            'title' => $this->title,
            'mode' => 'edit',
        ]);
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
