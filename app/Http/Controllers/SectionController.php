<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Division;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('sections.index', compact('sections'));
    }

    public function create()
    {
        $divisions = Division::all();
        return view('sections.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'division_id' => 'required|exists:divisions,id'
        ]);

        $section = new Section;
        $section->name = $request->input('name');
        $section->division_id = $request->input('division_id');
        $section->save();

        return redirect()->route('section.index')
            ->with('success', 'Section créée avec succès.');
    }

    public function show($id)
    {
        $section = Section::findOrFail($id);
        return view('sections.show', compact('section'));
    }

    public function edit($id)
    {
        $section = Section::findOrFail($id);
        $divisions = Division::all();
        return view('sections.edit', compact('section', 'divisions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'division_id' => 'required|exists:divisions,id'
        ]);

        $section = Section::findOrFail($id);
        $section->name = $request->input('name');
        $section->division_id = $request->input('division_id');
        $section->save();

        return redirect()->route('section.index')
            ->with('success', 'Section mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect()->route('section.index')
            ->with('success', 'Section supprimée avec succès.');
    }
}
