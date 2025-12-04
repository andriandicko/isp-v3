<?php

namespace App\Console\Commands;

use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBillingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update billing status to overdue if past due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();

        // Update overdue billings
        $updated = Billing::where('status', 'pending')
            ->where('due_date', '<', $today)
            ->update(['status' => 'overdue']);

        $this->info("Updated {$updated} billing(s) to overdue status.");

        return Command::SUCCESS;
    }
}
