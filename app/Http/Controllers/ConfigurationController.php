<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigurationRequest;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index ()
    {
        return view('config.index', [
            'configurations' => Configuration::latest()->paginate(10),
        ]); 
    }

    public function create ()
    {
        return view('config.create');
    }

    public function store (ConfigurationRequest $request)
    {
        Configuration::create($request->validated());
        return to_route('configuration.index')->with('success_message', 'Configuration enregistrée.');
    }

    public function delete (Configuration $config)
    {
        $config->delete();
        return to_route('configuration.index')->with('success_message', 'Configuration supprimée.');
    }
}
