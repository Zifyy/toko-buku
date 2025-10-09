<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Semua route user wajib login
        $this->middleware(['auth']);
    }

    /**
     * Tampilkan daftar user.
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        $roles = ['admin', 'owner', 'kasir']; // daftar role yang tersedia

        return view('admin.user.index', compact('users', 'roles'));
    }

    /**
     * Redirect ke index (karena form create akan pakai modal di index).
     */
    public function create()
    {
        return redirect()->route('user.index');
    }

    /**
     * Redirect ke index (karena form edit akan pakai modal di index).
     */
    public function edit(User $user)
    {
        return redirect()->route('user.index');
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => ['required', Rule::in(['admin', 'owner', 'kasir'])],
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create($data);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', Rule::in(['admin', 'owner', 'kasir'])],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // hanya update password kalau ada input baru
        if (!empty($data['password'])) {
            $data['password'] = $data['password']; // otomatis di-hash via mutator di model
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        // Cegah user login menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('user.index')->with('error', 'Anda tidak bisa menghapus user yang sedang login.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}
