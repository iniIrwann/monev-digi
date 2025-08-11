<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class DataDesaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'desa');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('desa', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('page.kecamatan.dataDesa.index', compact('users'));
    }

    public function edit(string $id)
    {
        $profile = User::findOrFail($id);

        return view('page.kecamatan.dataDesa.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function updateKec(Request $request, string $id)
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
            if ($user->foto_profile && file_exists(public_path('assets/images/foto_profile/' . $user->foto_profile))) {
                unlink(public_path('assets/images/foto_profile/' . $user->foto_profile));
            }

            $file = $request->file('foto_profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/foto_profile'), $filename);
            $user->foto_profile = $filename;
        }

        $user->save();

        return redirect()->route(route: 'kecamatan.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

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
            if ($user->foto_profile && file_exists(public_path('assets/images/foto_profile/' . $user->foto_profile))) {
                unlink(public_path('assets/images/foto_profile/' . $user->foto_profile));
            }

            $file = $request->file('foto_profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/foto_profile'), $filename);
            $user->foto_profile = $filename;
        }

        $user->save();

        return redirect()->route(route: 'kecamatan.dataDesa.index')->with('success', 'Data Desa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Hapus foto profil jika ada
        if ($user->foto_profile && file_exists(public_path('assets/images/foto_profile/' . $user->foto_profile))) {
            unlink(public_path('assets/images/foto_profile/' . $user->foto_profile));
        }

        // Hapus data user
        $user->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('kecamatan.dataDesa.index')->with('success', 'User berhasil dihapus.');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.kecamatan.dataDesa.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'desa' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nohp' => 'required|digits_between:10,15',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoName = null;

        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');

            $original = preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $fotoName = time() . '_' . $original;

            $destination = public_path('assets/images/foto_profile');

            // buat folder kalau belum ada
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $fotoName);
        }

        // Simpan ke database
        User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'name' => $validated['name'],
            'role' => $validated['role'],
            'desa' => $validated['desa'],
            'email' => $validated['email'],
            'nohp' => $validated['nohp'],
            'foto_profile' => $fotoName,
        ]);

        return redirect()->route('kecamatan.dataDesa.index')->with('success', 'Pengguna berhasil ditambahkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $login_credential = User::find($id);
        // Logic to display the login_credential page
        return view('page.kecamatan.dataDesa.detail', compact('login_credential'));
    }

    /**
     * Remove the specified resource from storage.
     */

}
