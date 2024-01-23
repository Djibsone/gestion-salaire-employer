<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployerRequest;
use App\Models\Departement;
use App\Models\Employer;

class EmployerController extends Controller
{
    public function index () 
    {
        return view('employers.index', [
            'employers' => Employer::with('departement')->paginate(10)
        ]);
    }

    public function create ()
    {
        return view('employers.create', [
            'departements' => Departement::select('id', 'name')->get()
        ]);
    }

    public function edit (Employer $employer)
    {
        return view('employers.edit', [
            'employer' => $employer,
            'departements' => Departement::select('id', 'name')->get(),

        ]);
    }

    public function store (EmployerRequest $request)
    {
        Employer::create($request->validated());
        return to_route('employer.index')->with('success_message', 'Employer enregistré.');
    }

    public function update (EmployerRequest $request, Employer $employer)
    {
        $employer->update($request->validated());
        return to_route('employer.index')->with('success_message', 'Employer mis à jours.');

    }

    public function delete (Employer $employer)
    {
        $employer->delete();
        return to_route('employer.index')->with('success_message', 'Employer supprimé.');

    }
}
