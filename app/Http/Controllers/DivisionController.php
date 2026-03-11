<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Notification;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function index()
    {
        return redirect()->route('divisions.create');
    }

    public function create()
    {
        return view('divisions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:divisions,name'],
            'unit_code' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/', 'unique:divisions,unit_code'],
        ]);

        Division::create($data);

        return redirect()->route('divisions.create')->with('status', 'Divisi berhasil dibuat.');
    }

    public function edit(Division $division)
    {
        return view('divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('divisions', 'name')->ignore($division->id)],
            'unit_code' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/', Rule::unique('divisions', 'unit_code')->ignore($division->id)],
        ]);

        $division->update($data);

        return redirect()->route('divisions.create')->with('status', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        $name = $division->name;

        $usedByUser = User::where('division', $name)->exists();
        $usedBySurat = Surat::where('sender_division', $name)
            ->orWhere('recipient_division', $name)
            ->exists();
        $usedByNotif = Notification::where('recipient_division', $name)->exists();

        if ($usedByUser || $usedBySurat || $usedByNotif) {
            return redirect()
                ->route('divisions.create')
                ->with('status', 'Divisi tidak dapat dihapus karena masih digunakan.');
        }

        $division->delete();

        return redirect()->route('divisions.create')->with('status', 'Divisi berhasil dihapus.');
    }
}
