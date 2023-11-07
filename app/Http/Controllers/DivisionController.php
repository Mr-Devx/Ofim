<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direction;
use App\Models\Division;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::all();
        return view('divisions.index', compact('divisions'));
    }

    public function create()
    {
        $directions = Direction::all();
        return view('divisions.create', compact('directions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'direction_id' => 'required|exists:directions,id'
        ]);

        $division = new Division;
        $division->name = $request->input('name');
        $division->direction_id = $request->input('direction_id');
        $division->save();

        return redirect()->route('division.index')
            ->with('success', 'Division créée avec succès.');
    }

    public function show($id)
    {
        $division = Division::findOrFail($id);
        return view('divisions.show', compact('division'));
    }

    public function edit($id)
    {
        $division = Division::findOrFail($id);
        $directions = Direction::all();
        return view('divisions.edit', compact('division', 'directions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'direction_id' => 'required|exists:directions,id'
        ]);

        $division = Division::findOrFail($id);
        $division->name = $request->input('name');
        $division->direction_id = $request->input('direction_id');
        $division->save();

        return redirect()->route('division.index')
            ->with('success', 'Division mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();

        return redirect()->route('division.index')
            ->with('success', 'Division supprimée avec succès.');
    }
}
