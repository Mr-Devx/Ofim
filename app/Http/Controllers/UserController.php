<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(
            User::orderBy('created_at', 'DESC')->get()
        );
    }

    public function store(Request $request)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|string|email|unique:users,email,',
            'address' => 'nullable|string',
            'profil' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'id_ChefSection' => 'nullable|exists:sections,id',
            'id_ChefDivision' => 'nullable|exists:divisions,id',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        DB::beginTransaction();
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        // Set other fields as needed
        $data->save();

        DB::commit();
        return response()->json(new UserResource($data), 201);
    }

    public function show($id)
    {
        $args = [];
        $args['error'] = false;

        $data = User::find($id);
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }

        return response()->json(new UserResource($data), 200);
    }

    public function edit($id)
    {
        // Implement if needed
    }

    public function update(Request $request, $id)
    {
        $args = [];
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'address' => 'nullable|string',
            'profil' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'id_ChefSection' => 'nullable|exists:sections,id',
            'id_ChefDivision' => 'nullable|exists:divisions,id',
        ]);

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $data = User::find($id);
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        } else {
            $data->update($request->all());
            return response()->json(new UserResource($data), 200);
        }
    }

    public function destroy($id)
    {
        $args = array();
        $args['error'] = false;

        $data = User::find($id);
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        } else {
            $data->delete();
            return response()->json(new UserResource($data), 200);
        }
    }
}
