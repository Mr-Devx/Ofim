<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dagle;

class DagleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dagles = Dagle::all();
        return view('dagles.index', compact('dagles'));
    }

    public function create()
    {
        return view('dagles.create');
    }

    public function store(Request $request)
    {
        // Validez les données du formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'secretariat' => 'required|string|max:255',
        ]);

        // Créez une nouvelle instance de Dagle avec les données validées
        $dagle = new Dagle($validatedData);
        $dagle->save();

        return redirect()->route('dagles.index')->with('success', 'Dagle créé avec succès.');
    }

    public function show($id)
    {
        $dagle = Dagle::findOrFail($id); // Recherche l'enregistrement Dagle par son ID

        return view('dagles.show', ['dagle' => $dagle]);
    }


    public function edit($id)
    {
        $dagle = Dagle::findOrFail($id); // Recherche l'enregistrement Dagle par son ID

        return view('dagles.edit', ['dagle' => $dagle]);
    }


    public function update(Request $request, $id)
{
    // Validez les données du formulaire
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'secretariat' => 'required|string|max:255',
    ]);

    $dagle = Dagle::findOrFail($id); // Recherche l'enregistrement Dagle par son ID

    // Mettez à jour les attributs de l'enregistrement avec les données validées
    $dagle->name = $validatedData['name'];
    $dagle->secretariat = $validatedData['secretariat'];
    $dagle->save();

    // Redirigez l'utilisateur vers la page de liste des dagles ou toute autre page souhaitée
    return redirect()->route('dagles.index')->with('success', 'Dagle mis à jour avec succès.');
}


    public function destroy($id)
    {
        $dagle = Dagle::findOrFail($id); // Recherche l'enregistrement Dagle par son ID

        // Supprimez l'enregistrement de la base de données
        $dagle->delete();

        // Redirigez l'utilisateur vers la page de liste des dagles ou toute autre page souhaitée
        return redirect()->route('dagles.index')->with('success', 'Dagle supprimé avec succès.');
    }
}
