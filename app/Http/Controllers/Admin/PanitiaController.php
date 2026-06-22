<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PanitiaController extends Controller
{
    public function index()
    {
        $panitias = User::where('role', 'panitia')->latest()->paginate(10);
        return view('admin.panitia.index', compact('panitias'));
    }

    public function create()
    {
        return view('admin.panitia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'panitia',
        ]);

        return redirect()->route('admin.panitia.index')->with('success', 'Akun Panitia berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $panitia = User::findOrFail($id);
        return view('admin.panitia.edit', compact('panitia'));
    }

    public function update(Request $request, $id)
    {
        $panitia = User::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($panitia->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $panitia->update($data);

        return redirect()->route('admin.panitia.index')->with('success', 'Data Panitia berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $panitia = User::findOrFail($id);
        $panitia->delete();
        return redirect()->route('admin.panitia.index')->with('success', 'Akun Panitia berhasil dihapus.');
    }
}
