<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class StaffUserController extends Controller
{
    public function index(string $role): View
    {
        $userRole = $this->resolveRole($role);

        return view('admin.staff.index', [
            'role' => $userRole,
            'roleSlug' => $role,
            'users' => User::where('role', $userRole)->latest()->paginate(15),
        ]);
    }

    public function create(string $role): View
    {
        $userRole = $this->resolveRole($role);

        return view('admin.staff.create', [
            'role' => $userRole,
            'roleSlug' => $role,
        ]);
    }

    public function store(Request $request, string $role): RedirectResponse
    {
        $userRole = $this->resolveRole($role);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'contact_info' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:5120'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('staff', 'public');
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'contact_info' => $data['contact_info'] ?? null,
            'image_path' => $imagePath,
            'role' => $userRole,
            'is_active' => true,
            'password' => Hash::make($data['password']),
        ]);

        return redirect()
            ->route('admin.staff.index', $role)
            ->with('status', $userRole->label().' created successfully.');
    }

    public function edit(string $role, User $user): View
    {
        $userRole = $this->resolveRole($role);
        abort_unless($user->role === $userRole, 404);

        return view('admin.staff.edit', [
            'role' => $userRole,
            'roleSlug' => $role,
            'user' => $user,
        ]);
    }

    public function update(Request $request, string $role, User $user): RedirectResponse
    {
        $userRole = $this->resolveRole($role);
        abort_unless($user->role === $userRole, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'contact_info' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:5120'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'contact_info' => $data['contact_info'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('image')) {
            if ($user->image_path) {
                Storage::disk('public')->delete($user->image_path);
            }
            $user->image_path = $request->file('image')->store('staff', 'public');
        }

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.staff.index', $role)
            ->with('status', $userRole->label().' updated successfully.');
    }

    public function destroy(string $role, User $user): RedirectResponse
    {
        $userRole = $this->resolveRole($role);
        abort_unless($user->role === $userRole, 404);

        if ($user->image_path) {
            Storage::disk('public')->delete($user->image_path);
        }

        $user->delete();

        return redirect()
            ->route('admin.staff.index', $role)
            ->with('status', $userRole->label().' removed.');
    }

    private function resolveRole(string $role): UserRole
    {
        return match ($role) {
            'approvers' => UserRole::Approver,
            'lawyers' => UserRole::Lawyer,
            'counselors' => UserRole::Counselor,
            'law-enforcers' => UserRole::LawEnforcer,
            'humanitarians' => UserRole::Humanitarian,
            default => abort(404),
        };
    }
}
