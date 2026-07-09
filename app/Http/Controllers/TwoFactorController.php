<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class TwoFactorController extends Controller
{
    public function enable(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user());

        return redirect('/perfil/2fa/qr')->with('status', '2FA habilitada. Escanea el código QR para confirmar.');
    }

    public function qr(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_secret) {
            return redirect('/perfil')->with('status', 'Primero debes habilitar 2FA.');
        }

        return view('auth.two-factor-qr', [
            'qrCode' => $user->twoFactorQrCodeSvg(),
            'secretKey' => decrypt($user->two_factor_secret),
        ]);
    }

    public function disable(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $disable($request->user());

        return redirect('/perfil')->with('status', '2FA deshabilitada correctamente.');
    }
}
