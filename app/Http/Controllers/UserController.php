<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('division')
            ->orderBy('role')
            ->orderBy('username')
            ->paginate(12);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $divisions = $this->divisionOptions();
        $roles = ['User', 'Admin'];

        return view('users.create', compact('divisions', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'division' => [
                Rule::requiredIf(fn () => $request->input('role') !== 'Admin'),
                'nullable',
                'string',
                'max:80',
                Rule::exists('divisions', 'name'),
            ],
            'role' => ['required', 'in:User,Admin'],
            'email' => ['nullable', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if (empty($data['division'])) {
            $data['division'] = null;
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('status', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $divisions = $this->divisionOptions();
        $roles = ['User', 'Admin'];

        return view('users.edit', compact('user', 'divisions', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'division' => [
                Rule::requiredIf(fn () => $request->input('role') !== 'Admin'),
                'nullable',
                'string',
                'max:80',
                Rule::exists('divisions', 'name'),
            ],
            'role' => ['required', 'in:User,Admin'],
            'email' => ['nullable', 'email', 'max:120', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (empty($data['division'])) {
            $data['division'] = null;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('status', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'Admin' && User::where('role', 'Admin')->count() <= 1) {
            return redirect()->route('users.index')->with('status', 'Minimal harus ada satu admin.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'Akun berhasil dihapus.');
    }

    private function divisionOptions(): array
    {
        return Division::orderBy('name')
            ->pluck('name')
            ->toArray();
    }
}
