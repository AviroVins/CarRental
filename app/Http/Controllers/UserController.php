<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Rules\ValidPesel;

class UserController extends Controller
{
    protected $primaryKey = 'pesel';
    protected $keyType = 'string';
    private $routePrefix = 'users';
    private $title = 'Użytkownicy';

    public function index()
    {
        $items = User::paginate(10);
        $columns = ['pesel', 'first_name', 'last_name', 'email', 'phone_number', 'role', 'profile_photo'];

        return view('shared.index', [
            'items' => $items,
            'columns' => $columns,
            'routePrefix' => $this->routePrefix,
            'title' => $this->title,
        ]);
    }

    public function create()
    {
        $columns = ['pesel', 'first_name', 'last_name', 'email', 'phone_number', 'role', 'password'];

        $extraData = [
            'roles' => ['user', 'admin'],
        ];

        return view('shared.form', [
            'item' => new User(),
            'columns' => $columns,
            'routePrefix' => $this->routePrefix,
            'title' => $this->title,
            'mode' => 'create',
            'extraData' => $extraData,
        ]);
    }

    public function edit(User $user)
    {
        $columns = ['first_name', 'last_name', 'email', 'phone_number', 'role'];

        $extraData = [
            'roles' => ['user', 'admin'],
        ];

        return view('shared.form', [
            'item' => $user,
            'columns' => $columns,
            'routePrefix' => $this->routePrefix,
            'title' => $this->title,
            'mode' => 'edit',
            'extraData' => $extraData,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pesel' => ['required', 'string', 'unique:users,pesel', new ValidPesel],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|string|in:admin,user',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        DB::table('users')->insert($validated);

        return redirect()->route('users.index')->with('success', 'Użytkownik dodany.');
    }

    public function update(Request $request, $pesel): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pesel . ',pesel',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|string|in:admin,user',
            'password' => 'nullable|string|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->input('password'));
        } else {
            unset($validated['password']);
        }

        DB::table('users')->where('pesel', $pesel)->update($validated);

        return redirect()->route('users.index')->with('success', 'Użytkownik zaktualizowany.');
    }


    public function destroy($pesel): RedirectResponse
    {
        DB::table('users')->where('pesel', $pesel)->delete();
        return redirect()->route('users.index')->with('success', 'Użytkownik usunięty.');
    }

    public function dashboard()
    {
        $past = DB::table('rentals')
            ->join('cars', 'rentals.plate_number', '=', 'cars.plate_number')
            ->select('cars.model', DB::raw('COUNT(*) as count'))
            ->groupBy('cars.model')
            ->orderByDesc('count')
            ->get();

        $future = DB::table('reservations')
            ->where('start_time', '>=', now())
            ->join('cars', 'reservations.plate_number', '=', 'cars.plate_number')
            ->select('cars.model', DB::raw('COUNT(*) as count'))
            ->groupBy('cars.model')
            ->orderByDesc('count')
            ->get();

        $pastLabels = $past->pluck('model');
        $pastData = $past->pluck('count');
        $futureLabels = $future->pluck('model');
        $futureData = $future->pluck('count');

        return view('dashboard', compact(
            'pastLabels', 'pastData', 'futureLabels', 'futureData'
        ));
    }
}
