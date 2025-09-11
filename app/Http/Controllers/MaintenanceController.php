<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenances = Maintenance::orderBy('date', 'desc')->paginate(15);
        return view('maintenances.index', compact('maintenances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('maintenances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'km' => 'nullable|numeric|min:0'
        ]);

        Maintenance::create($request->all());

        return redirect()->route('maintenances.index')->with('success', 'Manutenção criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        return view('maintenances.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        return view('maintenances.edit', compact('maintenance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'km' => 'nullable|numeric|min:0'
        ]);

        $maintenance->update($request->all());

        return redirect()->route('maintenances.index')->with('success', 'Manutenção atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success', 'Manutenção excluída com sucesso!');
    }
}
