<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Jobs\PrintSaleReceiptJob;
use Exception;

class PrintPastSale extends Command
{
    protected $signature = 'sale:print {sale_id : The ID of the sale to reprint} {--mode=Ticket : Print mode: "Ticket" or "Nota de Venta"}';
    protected $description = 'Reprints a past sale receipt using the specified mode.';

    public function handle(): int
    {
        $saleId = $this->argument('sale_id');
        $mode = $this->option('mode');

        $this->info("Fetching sale ID: {$saleId}...");

        $sale = Sale::find($saleId);

        if (!$sale) {
            $this->error("Sale not found in the database.");
            return Command::FAILURE;
        }

        $this->info("Dispatching print job ({$mode} mode) for Sale Ticket #{$sale->number}...");

        try {
            PrintSaleReceiptJob::dispatchSync($sale->id, $mode);
            $this->info('✅ Print job dispatched successfully to the Node.js printer server.');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error("Failed to print sale: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}