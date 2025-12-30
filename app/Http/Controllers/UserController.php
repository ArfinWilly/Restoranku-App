<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereHas('role' , function ($query) {
            $query->where('role_name', '!=', 'customer');
        })->get();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('role_name', 'asc')->where('role_name', '!=', 'customer')->get();
        
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'username.required' => 'Username wajib diisi.',
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'role_id.required' => 'Role wajib dipilih.',
        ]);

        $validate['password'] = bcrypt($validate['password']);

        User::create($validate);

        return redirect()->route('users.index')->with('success', 'Data User Berhasil Ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('role_name', 'asc')->where('role_name', '!=', 'customer')->get();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable' , 'string', 'min:8', 'confirmed' , function($attribute, $value, $fail) use ($user) {
                if (Hash::check($value, $user->password)) {
                    $fail('Password baru tidak boleh sama dengan password lama.');
                }
            }],
            'phone' => 'required|string|max:15',
            'role_id' => 'required|exists:roles,id',
        ], [
            'username.required' => 'Username wajib diisi.',
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.same' => 'Password baru tidak boleh sama dengan password lama.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'role_id.required' => 'Role wajib dipilih.',
        ]);

        $user = User::findOrFail($user->id);
        $user->update($validate);

        return redirect()->route('users.index')->with('success', 'Data User Berhasil Diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Data User Berhasil Dihapus.');
    }
}
