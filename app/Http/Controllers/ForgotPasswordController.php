<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationResetCodeMail;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $args = [];
        $args['error'] = false;
        $code= mt_rand(100000, 999999); // generer un token unique
        $v = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $exists = User::where('email', $request->email)->exists();
        $results = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$exists) {
            return response()->json(['message' => 'L\'e-mail n\'existe pas !.']);
        }

        if ($results) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            $reset = DB::insert('insert into password_reset_tokens (email, code, created_at) values (?, ?,?)', [$request->email, $code, now()]);
            Mail::to($request->email)->send(new VerificationResetCodeMail($code));
            return   response()->json(['message' => 'Lien de réinitialisation envoyé par e-mail']);
        } else {

            $reset = DB::insert('insert into password_reset_tokens (email, code, created_at) values (?, ?,?)', [$request->email, $code, now()]);
            Mail::to($request->email)->send(new VerificationResetCodeMail($code));
            return   response()->json(['message' => 'Lien de réinitialisation envoyé par e-mail']);
        }

    }

    private function broker()
    {
        return Password::broker();
    }
}
