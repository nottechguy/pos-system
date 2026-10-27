<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $users = User::where('business_id', $businessId)
            ->with(['role', 'stores'])
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $stores = Store::all();
        return view('admin.users.create', compact('roles', 'stores'));
    }

    public function store(Request $request)
    {
        $businessId = auth()->user()->business_id;

        $validated = $request->validate([
            'code' => 'required|string|max:6|unique:users,code,NULL,id,business_id,' . $businessId,
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:80',
            'email' => 'nullable|email|max:254|unique:users,email,NULL,id,business_id,' . $businessId,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:6',
            'pin' => 'nullable|digits:4',
            'stores' => 'array',
            'logo_path' => 'nullable|image|max:2048', // avatar input
        ]);

        $avatarPath = null;
        if ($request->hasFile('logo_path')) {
            $avatarPath = $request->file('logo_path')->store('avatars', 'public');
        }

        $user = User::create([
            'business_id' => $businessId,
            'role_id' => $validated['role_id'],
            'code' => $validated['code'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'avatar_path' => $avatarPath,
            'password' => Hash::make($validated['password']),
            'pin_hash' => !empty($validated['pin']) ? Hash::make($validated['pin']) : null,
            'status' => $validated['status'],
        ]);

        if (!empty($validated['stores'])) {
            $syncData = [];
            foreach ($validated['stores'] as $storeId) {
                $syncData[$storeId] = ['assigned_at' => now()];
            }
            $user->stores()->sync($syncData);
        } else {
            $user->stores()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $stores = Store::all();
        return view('admin.users.edit', compact('user', 'roles', 'stores'));
    }

    public function update(Request $request, User $user)
    {
        $businessId = auth()->user()->business_id;

        $validated = $request->validate([
            'code' => 'required|string|max:6|unique:users,code,' . $user->id . ',id,business_id,' . $businessId,
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:80',
            'email' => 'nullable|email|max:254|unique:users,email,' . $user->id . ',id,business_id,' . $businessId,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:6',
            'pin' => 'nullable|digits:4',
            'stores' => 'array',
            'logo_path' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo_path')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $user->avatar_path = $request->file('logo_path')->store('avatars', 'public');
        }

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->code = $validated['code'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->role_id = $validated['role_id'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if (!empty($validated['pin'])) {
            $user->pin_hash = Hash::make($validated['pin']);
        }

        $user->save();

        if (isset($validated['stores'])) {
            $user->stores()->sync($validated['stores']);
        } else {
            $user->stores()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario activo.');
        }

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}