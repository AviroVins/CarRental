<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->pesel, 'pesel')],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'has_driver_license' => ['required', 'boolean'],
            'account_status' => ['required', Rule::in(['active', 'inactive', 'blocked'])],
            'role' => ['required', Rule::in(['user', 'admin'])],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->has_driver_license = $validated['has_driver_license'];
        $user->account_status = $validated['account_status'];
        $user->role = $validated['role'];

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::delete('profile_photos/' . $user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile_photos', $filename);

            $user->profile_photo = $filename;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil zaktualizowany pomyślnie.');
    }
}