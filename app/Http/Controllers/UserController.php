<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,technician'],
            'phone' => ['nullable', 'string', 'max:100'],
            'rut' => ['nullable', 'string', 'max:100'],
            'sec_code' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'rut' => $request->rut,
            'sec_code' => $request->sec_code,
            'is_active' => true,
        ]);

        // Automatically trigger welcome email with password setup token
        $this->sendWelcomeMail($user);

        return redirect()->route('users.index')
            ->with('success', "Usuario / Técnico '{$user->name}' creado exitosamente y correo de bienvenida enviado.");
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,technician'],
            'phone' => ['nullable', 'string', 'max:100'],
            'rut' => ['nullable', 'string', 'max:100'],
            'sec_code' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'rut' => $request->rut,
            'sec_code' => $request->sec_code,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puede eliminar su propia cuenta de administrador.']);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado.');
    }

    /**
     * Send welcome email with password setup token to a technician/user.
     */
    public function sendWelcomeMail(User $user)
    {
        $token = \Illuminate\Support\Str::random(60);

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => \Illuminate\Support\Facades\Hash::make($token),
                'created_at' => now(),
            ]
        );

        $setupUrl = route('password.set.form', [
            'token' => $token,
            'email' => $user->email,
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\TechnicianWelcomeMail($user, $setupUrl));
            return back()->with('success', "Correo de bienvenida enviado exitosamente a {$user->name} ({$user->email}).");
        } catch (\Throwable $e) {
            return back()->with('error', "No se pudo enviar el correo: " . $e->getMessage() . " | Enlace generado: " . $setupUrl);
        }
    }

    /**
     * Show public set password form from token link.
     */
    public function showSetPasswordForm(Request $request, $token)
    {
        $email = $request->query('email');
        if (!$email) {
            return redirect()->route('login')->with('error', 'Enlace de contraseña inválido o incompleto.');
        }

        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record || !\Illuminate\Support\Facades\Hash::check($token, $record->token)) {
            return redirect()->route('login')->with('error', 'El enlace para establecer contraseña ha expirado o no es válido.');
        }

        return view('auth.set-password', compact('token', 'email'));
    }

    /**
     * Update user password from set password form.
     */
    public function updateSetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !\Illuminate\Support\Facades\Hash::check($request->token, $record->token)) {
            return back()->with('error', 'El enlace ha expirado o es inválido.');
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'No se encontró el usuario asociado a este correo.');
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('certificates.index')
            ->with('success', "¡Bienvenido {$user->name}! Tu contraseña se ha establecido correctamente.");
    }
}
