<?php

namespace App\Http\Controllers;

use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            AuditoriaService::log('LOGIN_EXITOSO', ['email' => $request->email]);

            return response()->json([
                'token' => auth()->user()->createToken('api')->plainTextToken,
            ]);
        }

        AuditoriaService::security('LOGIN_FALLIDO', 'warning', [
            'email_intento' => $request->email,
        ]);

        return response()->json(['error' => 'Credenciales incorrectas'], 401);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual no coincide.'],
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        AuditoriaService::log('CAMBIO_CONTRASEÑA', [
            'usuario_id' => $user->id,
            'email' => $user->email,
        ]);

        return response()->json(['mensaje' => 'Contraseña actualizada']);
    }
}
