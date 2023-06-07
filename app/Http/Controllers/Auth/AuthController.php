<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use App\Http\Resources\AuthResource;
use Laravel\Sanctum\PersonalAccessTokenFactory;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $code = mt_rand(100000, 999999); // Générer un code aléatoire à 6 chiffres
        $expiresAt = now()->addMinutes(15); // Définir une expiration pour le code (15 minutes dans cet exemple)







        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur ou mot de passe incorect'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Utilisateur ou mot de passe incorect'], 401);
        }

        // Enregistrer le code de vérification dans la base de données
        $verificationCode = new VerificationCode([
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);
        $user->verificationCode()->save($verificationCode);
        // Envoyer le code de vérification par e-mail
        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return response()->json(['User_id' =>$user->id ,'message' => 'Le code de vérification a été envoyé par e-mail.']);
        // $token = $user->createToken('auth_token')->plainTextToken;

        // return response()->json([
        //     'status' => 200,
        //     'success' => true,
        //     'user' => $user,
        //     'roles' => $user->getRoleNames(),
        //     'permissions' => $user->getPermissionNames(),
        //     'token' => $token,
        // ]);
    }



    public function verifyCode(Request $request)
    {

        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'user_id' => 'required|exists:verification_codes,user_id',
            'code' => 'required',

        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $userCode = VerificationCode::where('user_id', $request->user_id)->first();
        // dd($userCode->code);
        $user = User::where('id', $request->user_id)->first();
        // Vérifier si l'utilisateur a un code de vérification enregistré
        if (!$userCode->code) {
            return response()->json(['error' => 'Aucun code de vérification trouvé.'], 422);
        }

        $verificationCode = $userCode->code;

        // Vérifier si le code de vérification a expiré
        if ($userCode->expires_at < now()) {
            return response()->json(['error' => 'Le code de vérification a expiré.'], 422);
        }

        // Vérifier si le code de vérification est correct
        if ($verificationCode !== $request->code) {
            return response()->json(['error' => 'Le code de vérification est incorrect.'], 422);
        }

       

        // Générer le jeton d'authentification Sanctum
        $token = $user->createToken('two-factor')->plainTextToken;
         // Supprimer le code de vérification après confirmation
         $userCode->delete();

        return response()->json(['User' => $user,'Roles' =>$user->getRoleNames(),'Permissions' =>$user->getPermissionNames(),'access_token' => $token]);
    }


    public function logout(Request $request)
    {
        if (Auth::guard('sanctum')->check()) {
            $request->user('sanctum')->tokens()->delete();
        }

        return response()->json([
            'message' => 'Vous avez été déconnecté'
        ], 200);
    }
}
