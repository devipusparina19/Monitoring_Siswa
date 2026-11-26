<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const WA_RULE = '/^628[0-9]{8,12}$/';

    private const WA_MESSAGE = 'Nomor WhatsApp tidak valid. Contoh: 08123456789.';

    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form', [
            'user' => new User(),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'whatsapp' => FonnteService::normalizePhone($request->input('whatsapp')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'role' => ['required', Rule::in([
                'admin',
                'guru',
                'orang_tua'
            ])],
            'whatsapp' => ['nullable', 'regex:' . self::WA_RULE],
        ], [
            'whatsapp.regex' => self::WA_MESSAGE,
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->merge([
            'whatsapp' => FonnteService::normalizePhone($request->input('whatsapp')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'min:8'],
            'role' => ['required', Rule::in([
                'admin',
                'guru',
                'orang_tua'
            ])],
            'whatsapp' => ['nullable', 'regex:' . self::WA_RULE],
        ], [
            'whatsapp.regex' => self::WA_MESSAGE,
        ]);

        if ($user->isAdmin() && $data['role'] !== 'admin' && $this->isLastAdmin($user)) {
            return back()
                ->withInput()
                ->with('error', 'Admin terakhir tidak boleh diubah perannya.');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->isAdmin() && $this->isLastAdmin($user)) {
            return back()->with('error', 'Admin terakhir tidak boleh dihapus.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    private function isLastAdmin(User $user): bool
    {
        return User::where('role', 'admin')
            ->where('id', '!=', $user->id)
            ->doesntExist();
    }
}