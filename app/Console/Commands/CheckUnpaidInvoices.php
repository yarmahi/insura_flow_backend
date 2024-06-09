<?php

namespace App\Console\Commands;

use App\Jobs\SendInvoiceReminderEmail;
use App\Models\Invoice;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckUnpaidInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-unpaid-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected array $invoices;

    /**
     * Execute the console command.
     */
    public function handle()
    {

        echo "hey";
        $vehicles = Vehicle::all();

        foreach ($vehicles as $vehicle) {
            $this->invoices[] = Invoice::where([
                'vehicle_id' => $vehicle->id,
                'status' => 'paid'
            ])->latest()->first();
        }

        // dd($this->invoices);

        foreach ($this->invoices as $invoice) {
            if($invoice != null && $this->checkInvoice($invoice)) {
                SendInvoiceReminderEmail::dispatch($invoice);
            }
        }

        
    }


    function checkInvoice(Invoice $invoice) {

        $daysLeft = $invoice->daysLeft();

        if ($daysLeft > 0 && $daysLeft < 4) {
            return true;
        }

        return false;
    }
}
