<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        $users = User::all();
        // return view('admin.users.index', compact('users'));
        return "Listado de usuarios: \n" . $users
            ->pluck('name', 'rol')
            ->map(function ($name, $rol) {
                return "$name ($rol)";
            })
            ->implode("\n");
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        // return view('admin.users.create');
        return "Formulario para crear un nuevo usuario.";
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:' . User::ROL_ADMIN . ',' . User::ROL_PROFESOR . ',' . User::ROL_ESTUDIANTE,
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        // return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
        return "Usuario " . $request->name . " creado como " . $request->rol . ".";
    }

    /**
     * Muestra un usuario específico.
     */
    public function show(User $user)
    {
        // return view('admin.users.show', compact('user'));
        return "Detalles del usuario: " . $user->name . " (" . $user->rol . ")";
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function edit(User $user)
    {
        // return view('admin.users.edit', compact('user'));
        return "Formulario para editar el usuario: " . $user->name;
    }

    /**
     * Actualiza un usuario específico en la base de datos.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'rol' => 'required|in:' . User::ROL_ADMIN . ',' . User::ROL_PROFESOR . ',' . User::ROL_ESTUDIANTE,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->rol = $request->rol;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
        return "Usuario " . $user->name . " actualizado a rol " . $user->rol . ".";
    }

    /**
     * Elimina un usuario específico.
     */
    public function destroy(User $user)
    {
        $user->delete();
        // return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
        return "Usuario " . $user->name . " eliminado.";
    }
}
