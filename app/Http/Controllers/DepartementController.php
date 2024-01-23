<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartementRequest;
use App\Models\Departement;

class DepartementController extends Controller
{
    public function index () 
    {
        $departements = Departement::paginate(10);
        return view('departements.index', [
            'departements' => $departements
        ]);
    }

    public function create ()
    {
        return view('departements.create');
    }

    public function edit (Departement $departement)
    {
        return view('departements.edit', [
            'departement' => $departement
        ]);
    }

    public function store (DepartementRequest $request)
    {
        Departement::create($request->validated());
        return to_route('departement.index')->with('success_message', 'Departement enregistré.');
    }

    public function update (DepartementRequest $request, Departement $departement)
    {
        $departement->update($request->validated());
        return to_route('departement.index')->with('success_message', 'Departement mis à jours.');

    }

    public function delete (Departement $departement)
    {
        $departement->delete();
        return to_route('departement.index')->with('success_message', 'Departement supprimé.');

    }
}
