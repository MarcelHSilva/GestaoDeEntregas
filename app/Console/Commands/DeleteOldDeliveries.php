<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldDeliveries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deliveries:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all deliveries that are not from today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        // Buscar entregas que não são de hoje
        $oldDeliveries = Delivery::where('date', '!=', $today)->get();
        
        if ($oldDeliveries->count() === 0) {
            $this->info('Nenhuma entrega antiga encontrada para deletar.');
            return 0;
        }
        
        $count = $oldDeliveries->count();
        
        // Confirmar antes de deletar
        if ($this->confirm("Tem certeza que deseja deletar {$count} entregas que não são de hoje?")) {
            // Deletar as entregas
            Delivery::where('date', '!=', $today)->delete();
            
            $this->info("✅ {$count} entregas antigas foram deletadas com sucesso!");
        } else {
            $this->info('Operação cancelada.');
        }
        
        return 0;
    }
}