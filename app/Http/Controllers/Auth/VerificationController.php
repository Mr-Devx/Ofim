<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class VerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $user = User::find($request->id);

        if ($user->email_verified_at == null) {
            $user->email_verified_at = now();
            $user->update();
            return redirect('/mailValidate')->with('success', 'Votre adresse email a été vérifiée.');
        } else {
            return redirect('/alreadymailValidate')->with('success', 'Votre adresse email a été vérifiée.');
        }
    }
}
