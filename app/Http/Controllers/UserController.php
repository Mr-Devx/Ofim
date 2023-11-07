<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Section;
use App\Models\Division;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $sections = Section::all();
        $divisions = Division::all();
        return view('users.create', compact('roles', 'sections', 'divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|string|email|unique:users',
            'address' => 'nullable|string',
            'profil' => 'nullable|string',
            'password' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'id_ChefSection' => 'nullable|exists:sections,id',
            'id_ChefDivision' => 'nullable|exists:divisions,id',
        ]);

        $user = new User;
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->username = $request->input('username');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->address = $request->input('address');
        $user->profil = $request->input('profil');
        $user->password = bcrypt($request->input('password'));
        $user->role_id = $request->input('role_id');
        $user->id_ChefSection = $request->input('id_ChefSection');
        $user->id_ChefDivision = $request->input('id_ChefDivision');
        $user->save();

        return redirect()->route('user.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $sections = Section::all();
        $divisions = Division::all();
        return view('users.edit', compact('user', 'roles', 'sections', 'divisions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
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

        $user = User::findOrFail($id);
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->username = $request->input('username');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->address = $request->input('address');
        $user->profil = $request->input('profil');
        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->role_id = $request->input('role_id');
        $user->id_ChefSection = $request->input('id_ChefSection');
        $user->id_ChefDivision = $request->input('id_ChefDivision');
        $user->save();

        return redirect()->route('user.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
