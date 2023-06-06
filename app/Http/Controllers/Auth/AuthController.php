<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur ou mot de passe incorect'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Utilisateur ou mot de passe incorect'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'success' => true,
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getPermissionNames(),
            'token' => $token,
        ]);
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
