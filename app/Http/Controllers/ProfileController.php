<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = User::find(auth()->id());
        // Logic to display the profile page
        return view('page.profile.profile', compact('profile'));
    }
    public function indexKec()
    {
        $login_credential = User::find(auth()->id());
        // Logic to display the profile login_credential
        return view('page.kecamatan.profile.index', compact('login_credential'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Ambil user yang sedang login atau ambil berdasarkan id
        $profile = auth()->user(); // atau User::find($id)

        return view('page.profile.profile', compact('profile'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $profile = User::findOrFail($id);

        return view('page.profile.edit_profile', compact('profile'));
    }
    public function editKec(string $id)
    {
        $profile = User::findOrFail($id);

        return view('page.kecamatan.profile.edit_profile', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'name' => 'required|string|max:255',
            // 'role' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nohp' => 'nullable|numeric|digits_between:10,15',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data
        $user->username = $validated['username'];
        $user->name = $validated['name']; // huruf kapital
        // $user->role = $validated['role'];
        $user->email = $validated['email'];
        $user->nohp = $validated['nohp'];

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Upload foto profil jika ada
        if ($request->hasFile('foto_profile')) {
            // Hapus foto lama jika ada dan bukan default
            if ($user->foto_profile && file_exists(public_path('assets/images/' . $user->foto_profile))) {
                unlink(public_path('assets/images/' . $user->foto_profile));
            }

            $file = $request->file('foto_profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $user->foto_profile = $filename;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
