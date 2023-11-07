<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dagle;
use App\Models\Direction;

class DirectionController extends Controller
{
    public function index()
    {
        $directions = Direction::all();
        return view('directions.index', compact('directions'));
    }

    public function create()
    {
        return view('directions.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'secretariat' => 'required|string|max:255',
            'dagle_id' => 'required|exists:dagles,id', // Validez que l'ID Dagle existe
        ]);

        $direction = new Direction($validatedData);
        $direction->save();

        $dagle = Dagle::findOrFail($validatedData['dagle_id']);
        $dagle->save();

        return redirect()->route('directions.index')->with('success', 'Direction créée avec succès.');
    }

    public function show($id)
    {
        $direction = Direction::findOrFail($id);
        return view('directions.show', ['direction' => $direction]);
    }

    public function edit($id)
    {
        $direction = Direction::findOrFail($id);
        $dagle = Dagle::all();

        return view('directions.edit', ['direction' => $direction]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'secretariat' => 'required|string|max:255',
            'dagle_id' => 'required',
        ]);

        $direction = Direction::findOrFail($id);
        $direction->name = $validatedData['name'];
        $direction->secretariat = $validatedData['secretariat'];
        $direction->dagle_id = $validatedData['dagle_id'];
        $direction->save();

        return redirect()->route('directions.index')->with('success', 'Direction mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $direction = Direction::findOrFail($id);
        $direction->delete();

        return redirect()->route('directions.index')->with('success', 'Direction supprimée avec succès.');
    }
}
