<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Configuración para fotos de perfil
     * - Formatos permitidos: JPEG, PNG, WebP
     * - Tamaño máximo: 2MB
     * - Directorio: storage/app/public/avatars (seguro, accesible vía enlace simbólico)
     */
    private const AVATAR_MAX_SIZE = 2048; // 2MB en KB
    private const AVATAR_ALLOWED_TYPES = 'jpeg,jpg,png,webp';
    private const AVATAR_STORAGE_PATH = 'avatars';

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) {
            abort(403);
        }
        
        return view('users.index', ['users' => User::paginate(15)]);
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) {
            abort(403);
        }
        
        return view('users.create', [
            'organizations' => Organization::all()
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,coordinator,user',
            'organization_id' => 'nullable|exists:organizations,id',
            'avatar' => 'nullable|image|mimes:' . self::AVATAR_ALLOWED_TYPES . '|max:' . self::AVATAR_MAX_SIZE,
        ]);

        // Procesar avatar si se subió
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $this->storeAvatar($request->file('avatar'));
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'organization_id' => $validated['organization_id'] ?? null,
            'face_image_path' => $avatarPath,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) {
            abort(403);
        }
        
        return view('users.edit', [
            'user' => User::findOrFail($id),
            'organizations' => Organization::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) {
            abort(403);
        }
        
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,coordinator,user',
            'organization_id' => 'nullable|exists:organizations,id',
            'avatar' => 'nullable|image|mimes:' . self::AVATAR_ALLOWED_TYPES . '|max:' . self::AVATAR_MAX_SIZE,
            'remove_avatar' => 'nullable|boolean',
        ]);

        // Manejar contraseña
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Manejar avatar
        if ($request->boolean('remove_avatar')) {
            // Eliminar avatar existente
            $this->deleteAvatar($user->face_image_path);
            $validated['face_image_path'] = null;
        } elseif ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            $this->deleteAvatar($user->face_image_path);
            // Guardar nuevo avatar
            $validated['face_image_path'] = $this->storeAvatar($request->file('avatar'));
        }

        // Limpiar campos que no van a la BD
        unset($validated['avatar'], $validated['remove_avatar']);

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) {
            abort(403);
        }
        
        $user = User::findOrFail($id);
        
        // Eliminar avatar si existe
        $this->deleteAvatar($user->face_image_path);
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Almacena el avatar en el disco público
     * Las fotos se guardan en: storage/app/public/avatars/
     * Accesibles vía: /storage/avatars/nombre_archivo
     */
    private function storeAvatar($file): string
    {
        return $file->store(self::AVATAR_STORAGE_PATH, 'public');
    }

    /**
     * Elimina un avatar del almacenamiento
     */
    private function deleteAvatar(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
