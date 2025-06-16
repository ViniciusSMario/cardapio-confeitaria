<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Http\Requests\StoreConfigurationRequest;
use App\Http\Requests\UpdateConfigurationRequest;
use Illuminate\Support\Facades\Request;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConfigurationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuration $configuration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuration $configuration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $config = Configuration::first();
        $config->mercado_pago_enabled = $request->has('mercado_pago_enabled');
        $config->save();

        return redirect()->back()->with('success', 'Configuração salva com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuration $configuration)
    {
        //
    }
}
