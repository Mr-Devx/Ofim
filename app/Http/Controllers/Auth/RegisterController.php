<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'username' => 'required|unique:users',
            'phone' => 'required',
            'email' => 'required|email|unique:users',
            'address' => 'nullable',
            'profil' => 'nullable',
            'password' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $User = new User();
        $User->firstname = $request->firstname;
        $User->lastname = $request->lastname;
        $User->username = $request->username;
        $User->email = $request->email;
        $User->address = $request->address;
        $User->phone =  $request->phone;
        $User->profil = $request->profil;
        $User->password =  bcrypt($request->password);
        $User->role_id = 2;
        $User->country_id =  $request->country_id;
        $User->city_id =  $request->city_id;
        $User->save();
        $User->sendEmailVerificationNotification();
        return response()->json(new UserResource($User), 201);
    }
}
