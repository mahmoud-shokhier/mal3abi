<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RemovePendingReservationsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * should remove pending reservations in last hour
     *
     * @return void
     */
    public function test_remove_pending_reservations()
    {
        $reservation1 = \factory(\App\Reservation::class)->create();
        $reservation2 = \factory(\App\Reservation::class)->create(['created_at' => now()->subHour()->format('Y-m-d H:i:s')]);
        $this->artisan('reservations:removePending');
        $this->assertTrue(\App\Reservation::whereId($reservation1->id)->exists());
        $this->assertFalse(\App\Reservation::whereId($reservation2->id)->exists());
    }
}
