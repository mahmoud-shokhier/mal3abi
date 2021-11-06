<?php

namespace App\Console\Commands;

use App\Reservation;
use Illuminate\Console\Command;

class RemovePendingReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Pending Reservation From 1 hr ago';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed|void
     * @throws \Exception
     */
    public function handle()
    {
        Reservation::whereStatus(Reservation::STATUS_PENDING)
            ->where('created_at', '<', now()->subHour()->format('Y-m-d H:i:s'))->delete();
    }
}
