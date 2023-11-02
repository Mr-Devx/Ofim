<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use App\Http\Resources\UserResource;

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
        $tokenUnique = Str::random(40); // generer un token unique
        // dd($tokenUnique);
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
        // $verificationCode = new VerificationCode([
        //     'code' => $code,
        //     'expires_at' => $expiresAt,
        //     'token' => $tokenUnique,
        // ]);
        $token = $user->createToken('two-factor')->plainTextToken;


        // $user->verificationCode()->save($verificationCode);
        // Mail::to($user->email)->send(new VerificationCodeMail($code));

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function loginWithCode(Request $request)
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
        $tokenUnique = Str::random(40); // generer un token unique
        // dd($tokenUnique);
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
            'token' => $tokenUnique,
        ]);

        $user->verificationCode()->save($verificationCode);
        // Envoyer le code de vérification par e-mail
        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return response()->json(['Verify_Token' => $tokenUnique, 'message' => 'Le code de vérification a été envoyé par e-mail.']);
    }



    public function verifyCode(Request $request)
    {

        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'verify_token' => 'required|exists:verification_codes,token',
            'code' => 'required',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $userCode = VerificationCode::where('token', $request->verify_token)->first();
        // dd($userCode->code);
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


        $user = User::where('id', $userCode->user_id)->first();
        // Générer le jeton d'authentification Sanctum
        $token = $user->createToken('two-factor')->plainTextToken;
        // Supprimer le code de vérification après confirmation
        $userCode->delete();

        return response()->json(['User' => $user, 'Roles' => $user->getRoleNames(), 'Permissions' => $user->getPermissionNames(), 'access_token' => $token]);
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


    public function profile($id)
    {
        $User = User::find($id);
        $all = null;
        if (is_null($User)) {
            $args['error'] = true;
            $args['message'] = 'Ressource introuvable';
            return response()->json($args, 404);
        } else {
            return response()->json(new UserResource($User), 201);
        }
    }

    public function update(Request $request, $id)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'username' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'nullable',
            'profil' => 'nullable',
        ]);


        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $user = User::find($id);
        $all = $request->all();
        if(is_null($user))
        {
            $args['error'] = true;
            $args['message'] = 'Ressource introuvable';
            return response()->json($args, 404);
        }
        else{
            $user->update($all);
            return response()->json(new UserResource($user), 200);
        }
    }



    public function delete($id)
    {
        $args = array();
        $args['error'] = false;

        $user = User::find($id);
        if(is_null($user))
        {
            $args['error'] = true;
            $args['message'] = 'Ressource introuvable';
            return response()->json($args, 404);
        }
        else{
            $user->delete();
            return response()->json(new UserResource($user), 200);
        }
    }



    public function changePassword(Request $request, $id)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);


        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $user = User::find($id);
        if(is_null($user))
        {
            $args['error'] = true;
            $args['message'] = 'Ressource introuvable';
            return response()->json($args, 404);
        }
        else{

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['message' => 'Ancien mot de passe incorrect !'], 401);
            }
            $user->password = bcrypt($request->new_password);
            $user->update();
            return response()->json(['message' => 'votre mot de passe a ete modifier'], 401);
        }
    }

}
