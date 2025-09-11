<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::all();
        $maintenances = Maintenance::all();
        
        $totalDeliveries = $deliveries->count();
        $totalRevenue = $deliveries->sum('gross');
        $totalFuelCost = $deliveries->sum('fuel_total');
        $totalMaintenanceCost = $maintenances->sum('cost');
        $totalKm = $deliveries->sum(function($delivery) {
            return $delivery->km_end - $delivery->km_start;
        });
        $totalProfit = $totalRevenue - $totalFuelCost - $totalMaintenanceCost;
        
        // Agrupar entregas por dia
        $deliveriesByDay = $deliveries->groupBy(function($delivery) {
            return Carbon::parse($delivery->date)->format('Y-m-d');
        });
        
        // Agrupar manutenções por dia
        $maintenancesByDay = $maintenances->groupBy(function($maintenance) {
            return Carbon::parse($maintenance->date)->format('Y-m-d');
        });
        
        return view('reports.index', compact(
            'totalDeliveries',
            'totalRevenue',
            'totalFuelCost',
            'totalMaintenanceCost',
            'totalKm',
            'totalProfit',
            'deliveries',
            'maintenances',
            'deliveriesByDay',
            'maintenancesByDay'
        ));
    }

    public function weekly(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(6));
        $endDate = Carbon::parse($startDate)->addDays(6);

        $deliveries = Delivery::whereBetween('date', [$startDate, $endDate])->get();
        $maintenances = Maintenance::whereBetween('date', [$startDate, $endDate])->get();

        $report = [
            'period' => 'Semanal',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_km' => $deliveries->sum(function($delivery) {
                return $delivery->km_end - $delivery->km_start;
            }),
            'total_fuel' => $deliveries->sum('fuel_total'),
            'total_liters' => $deliveries->sum('liters'),
            'total_gross' => $deliveries->sum('gross'),
            'total_net' => $deliveries->sum('net'),
            'total_maintenance_cost' => $maintenances->sum('cost'),
            'deliveries_count' => $deliveries->count(),
            'maintenances_count' => $maintenances->count()
        ];

        $totalRevenue = $deliveries->sum('gross');
        $totalMaintenanceCost = $maintenances->sum('cost');
        $totalFuelCost = $deliveries->sum('fuel_total');
        $totalKm = $deliveries->sum(function($delivery) {
            return $delivery->km_end - $delivery->km_start;
        });
        $totalFuel = $deliveries->sum('liters');
        $totalProfit = $totalRevenue - $totalMaintenanceCost - $totalFuelCost;
        
        // Agrupar entregas por dia
        $deliveriesByDay = $deliveries->groupBy(function($delivery) {
            return Carbon::parse($delivery->date)->format('Y-m-d');
        });
        
        // Agrupar manutenções por dia
        $maintenancesByDay = $maintenances->groupBy(function($maintenance) {
            return Carbon::parse($maintenance->date)->format('Y-m-d');
        });
        
        return view('reports.weekly', compact('deliveries', 'maintenances', 'startDate', 'endDate', 'totalRevenue', 'totalMaintenanceCost', 'totalFuelCost', 'totalKm', 'totalFuel', 'totalProfit', 'deliveriesByDay', 'maintenancesByDay'));
    }

    public function monthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        
        // Se não foi especificado mês/ano, mostrar últimos 30 dias
        if (!$request->has('month') && !$request->has('year')) {
            $startDate = Carbon::now()->subDays(29);
            $endDate = Carbon::now();
        } else {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        }

        $deliveries = Delivery::whereBetween('date', [$startDate, $endDate])->get();
        $maintenances = Maintenance::whereBetween('date', [$startDate, $endDate])->get();

        $report = [
            'period' => 'Mensal',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_km' => $deliveries->sum(function($delivery) {
                return $delivery->km_end - $delivery->km_start;
            }),
            'total_fuel' => $deliveries->sum('fuel_total'),
            'total_liters' => $deliveries->sum('liters'),
            'total_gross' => $deliveries->sum('gross'),
            'total_net' => $deliveries->sum('net'),
            'total_maintenance_cost' => $maintenances->sum('cost'),
            'deliveries_count' => $deliveries->count(),
            'maintenances_count' => $maintenances->count()
        ];

        $totalRevenue = $deliveries->sum('gross');
        $totalMaintenanceCost = $maintenances->sum('cost');
        $totalFuelCost = $deliveries->sum('fuel_total');
        $totalFuel = $deliveries->sum('liters');
        $totalKm = $deliveries->sum(function($delivery) {
            return $delivery->km_end - $delivery->km_start;
        });
        $totalProfit = $totalRevenue - $totalMaintenanceCost - $totalFuelCost;
        
        // Agrupar entregas por dia
        $deliveriesByDay = $deliveries->groupBy(function($delivery) {
            return Carbon::parse($delivery->date)->format('Y-m-d');
        });
        
        // Agrupar manutenções por dia
        $maintenancesByDay = $maintenances->groupBy(function($maintenance) {
            return Carbon::parse($maintenance->date)->format('Y-m-d');
        });
        
        return view('reports.monthly', compact('deliveries', 'maintenances', 'startDate', 'endDate', 'totalRevenue', 'totalMaintenanceCost', 'totalFuelCost', 'totalFuel', 'totalKm', 'totalProfit', 'deliveriesByDay', 'maintenancesByDay'));
    }

    public function custom(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $deliveries = Delivery::whereBetween('date', [$startDate, $endDate])->get();
        $maintenances = Maintenance::whereBetween('date', [$startDate, $endDate])->get();

        $totalRevenue = $deliveries->sum('gross');
        $totalMaintenanceCost = $maintenances->sum('cost');
        $totalFuelCost = $deliveries->sum('fuel_total');
        $totalFuel = $deliveries->sum('liters');
        $totalKm = $deliveries->sum(function($delivery) {
            return $delivery->km_end - $delivery->km_start;
        });
        $totalProfit = $totalRevenue - $totalMaintenanceCost - $totalFuelCost;
        
        // Agrupar entregas por dia
        $deliveriesByDay = $deliveries->groupBy(function($delivery) {
            return Carbon::parse($delivery->date)->format('Y-m-d');
        });
        
        // Agrupar manutenções por dia
        $maintenancesByDay = $maintenances->groupBy(function($maintenance) {
            return Carbon::parse($maintenance->date)->format('Y-m-d');
        });
        
        return view('reports.custom', compact('deliveries', 'maintenances', 'startDate', 'endDate', 'totalRevenue', 'totalMaintenanceCost', 'totalFuelCost', 'totalFuel', 'totalKm', 'totalProfit', 'deliveriesByDay', 'maintenancesByDay'));
    }
}
