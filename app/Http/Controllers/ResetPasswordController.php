<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;
class ResetPasswordController extends Controller
{

    public function verify(Request $request)
    {
        $v = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        $tokenUnique = Str::random(40);
        $exists= DB::table('password_reset_tokens')->where('code', $request->code)->first();
        if (!$exists) {
            return response()->json(['message' => 'Le code est incorrect !.']);
        }else{
            DB::table('password_reset_tokens')->where('code', $request->code)->update(['token' => $tokenUnique]);
            return response()->json(['token' => $tokenUnique]);
        }

    }

    public function reset(Request $request)
    {
        $v = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',

        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }
        $exists= DB::table('password_reset_tokens')->where('token', $request->token)->first();
        if (!$exists) {
            return response()->json(['message' => 'Token invalide !.']);
        }else{
            $results= DB::table('password_reset_tokens')->where('token', $request->token)->first();
            $user = User::where('email', $results->email)->first();
            if (!$user) {
                return response()->json(['error' => 'Utilisateur introuvable'], 404);
            }
            $user->password = bcrypt($request->password);
            $user->update();
            return response()->json(['message' => 'Votre mot de passe a été modifié avec succès'], 200);
        }



        // $response = $this->broker()->reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function ($user, $password) {
        //         $user->password = bcrypt($password);
        //         $user->save();
        //     }
        // );

        // return $response == Password::PASSWORD_RESET
        //     ? response()->json(['message' => 'Mot de passe réinitialisé avec succès'])
        //     : response()->json(['error' => 'Échec de la réinitialisation du mot de passe'], 422);
    }

    private function broker()
    {
        return Password::broker();
    }
}
