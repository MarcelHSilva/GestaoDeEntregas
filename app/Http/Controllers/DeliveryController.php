<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveries = Delivery::orderBy('date', 'desc')->paginate(15);
        
        // Dados dos últimos 30 dias para os cards
        $thirtyDaysAgo = now()->subDays(30);
        $recentDeliveries = Delivery::where('date', '>=', $thirtyDaysAgo)->get();
        
        // Agrupar manutenções por dia
        $maintenances = Maintenance::all();
        $maintenancesByDay = $maintenances->groupBy(function($maintenance) {
            return Carbon::parse($maintenance->date)->format('Y-m-d');
        });
        
        return view('deliveries.index', compact('deliveries', 'recentDeliveries', 'maintenancesByDay'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('deliveries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'km_start' => 'nullable|numeric|min:0',
            'km_end' => 'required|numeric|min:0',
            'delivery_count' => 'required|integer|min:1',
            'price_per_delivery' => 'required|numeric|min:0',
            'km_per_liter' => 'nullable|numeric|min:0',
            'price_per_liter' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        
        // Definir km_start como 0 se não informado
        if (empty($data['km_start'])) {
            $data['km_start'] = 0;
        }
        
        // Calcular gross (quantidade de entregas × valor por entrega)
        $data['gross'] = $data['delivery_count'] * $data['price_per_delivery'];
        
        // Calcular consumo de combustível baseado em KM rodados e KM por litro
        $kmRodados = $data['km_end'] - $data['km_start'];
        
        if ($data['km_per_liter'] && $data['price_per_liter'] && $kmRodados > 0) {
            // Calcular litros consumidos: KM rodados ÷ KM por litro
            $data['liters'] = $kmRodados / $data['km_per_liter'];
            $data['fuel_total'] = $data['liters'] * $data['price_per_liter'];
        } else {
            $data['liters'] = null;
            $data['fuel_total'] = null;
        }
        
        // Calcular net (gross - fuel_total - soma de manutenções do mesmo dia)
        $maintenanceCosts = Maintenance::whereDate('date', $data['date'])->sum('cost');
        $fuelCost = $data['fuel_total'] ?? 0;
        $data['net'] = $data['gross'] - $fuelCost - $maintenanceCosts;

        Delivery::create($data);

        return redirect()->route('deliveries.index')->with('success', 'Entrega criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        return view('deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery)
    {
        return view('deliveries.edit', compact('delivery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $request->validate([
            'date' => 'required|date',
            'km_start' => 'nullable|numeric|min:0',
            'km_end' => 'required|numeric|min:0',
            'delivery_count' => 'required|integer|min:1',
            'price_per_delivery' => 'required|numeric|min:0',
            'km_per_liter' => 'nullable|numeric|min:0',
            'price_per_liter' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        
        // Definir km_start como 0 se não informado
        if (empty($data['km_start'])) {
            $data['km_start'] = 0;
        }
        
        // Calcular gross (quantidade de entregas × valor por entrega)
        $data['gross'] = $data['delivery_count'] * $data['price_per_delivery'];
        
        // Calcular consumo de combustível baseado em KM rodados e KM por litro
        $kmRodados = $data['km_end'] - $data['km_start'];
        
        if ($data['km_per_liter'] && $data['price_per_liter'] && $kmRodados > 0) {
            // Calcular litros consumidos: KM rodados ÷ KM por litro
            $data['liters'] = $kmRodados / $data['km_per_liter'];
            $data['fuel_total'] = $data['liters'] * $data['price_per_liter'];
        } else {
            $data['liters'] = null;
            $data['fuel_total'] = null;
        }
        
        // Calcular net (gross - fuel_total - soma de manutenções do mesmo dia)
        $maintenanceCosts = Maintenance::whereDate('date', $data['date'])->sum('cost');
        $fuelCost = $data['fuel_total'] ?? 0;
        $data['net'] = $data['gross'] - $fuelCost - $maintenanceCosts;

        $delivery->update($data);

        return redirect()->route('deliveries.index')->with('success', 'Entrega atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return redirect()->route('deliveries.index')->with('success', 'Entrega excluída com sucesso!');
    }
}
