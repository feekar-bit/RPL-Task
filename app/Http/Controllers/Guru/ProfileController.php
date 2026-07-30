<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // halaman profile
    public function index()
    {
        return view('guru.profile.index');
    }

    // update profile
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([

            'name' => 'required',

            'email' => 'required|email',

            'password' => 'nullable|min:6|confirmed',

            'photo' => 'nullable|image|max:5120',
        ]);

        // upload photo
        if ($request->hasFile('photo')) {

            $photo = $request->file('photo')
                ->store('profile_photos', 'public');

            $user->photo = $photo;
        }

        // update data
        $user->name = $request->name;

        $user->email = $request->email;

        // password baru
        if ($request->password) {

            $user->password = Hash::make(
                $request->password
            );
        }

        $user->save();

        return back()
            ->with('success', 'Profile berhasil diperbarui.');
    }
}