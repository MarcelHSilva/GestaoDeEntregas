<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar entregas dos últimos 30 dias
        $totalKm = 50000; // KM total do odômetro
        
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Pular alguns dias aleatoriamente
            if (rand(1, 10) <= 3) continue;
            
            // Zerar o trip diariamente (KM inicial sempre 0)
            $kmStart = 0;
            $kmEnd = rand(80, 250);
            
            // Quantidade de entregas e valor por entrega
            $deliveryCount = rand(15, 35);
            $pricePerDelivery = 4.50;
            
            // 30% de chance de não abastecer neste dia
            $hasRefuel = rand(1, 10) > 3;
            
            // Calcular valor bruto
            $gross = $deliveryCount * $pricePerDelivery;
            
            if ($hasRefuel) {
                $liters = rand(8, 15) + (rand(0, 99) / 100);
                $pricePerLiter = 5.50 + (rand(0, 50) / 100);
                $fuelTotal = $liters * $pricePerLiter;
                $net = $gross - $fuelTotal;
                
                Delivery::create([
                    'date' => $date->format('Y-m-d'),
                    'km_start' => $kmStart,
                    'km_end' => $kmEnd,
                    'delivery_count' => $deliveryCount,
                    'price_per_delivery' => $pricePerDelivery,
                    'km_per_liter' => rand(250, 350) / 10,
                    'liters' => $liters,
                    'price_per_liter' => $pricePerLiter,
                    'fuel_total' => $fuelTotal,
                    'gross' => $gross,
                    'net' => $net,
                ]);
            } else {
                // Dia sem abastecimento
                Delivery::create([
                    'date' => $date->format('Y-m-d'),
                    'km_start' => $kmStart,
                    'km_end' => $kmEnd,
                    'delivery_count' => $deliveryCount,
                    'price_per_delivery' => $pricePerDelivery,
                    'km_per_liter' => rand(250, 350) / 10,
                    'liters' => null,
                    'price_per_liter' => null,
                    'fuel_total' => null,
                    'gross' => $gross,
                    'net' => $gross,
                ]);
            }
        }
        
        // Criar algumas manutenções
        $maintenances = [
            [
                'date' => Carbon::now()->subDays(25)->format('Y-m-d'),
                'description' => 'Troca de óleo e filtro',
                'cost' => 85.00,
                'km' => 50500,
            ],
            [
                'date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'description' => 'Calibragem dos pneus',
                'cost' => 15.00,
                'km' => 51200,
            ],
            [
                'date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'description' => 'Limpeza do filtro de ar',
                'cost' => 25.00,
                'km' => 51800,
            ],
            [
                'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'description' => 'Ajuste da corrente',
                'cost' => 30.00,
                'km' => 52300,
            ],
            [
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'description' => 'Troca das pastilhas de freio',
                'cost' => 120.00,
                'km' => 52800,
            ],
        ];
        
        foreach ($maintenances as $maintenance) {
            Maintenance::create($maintenance);
        }
    }
}
