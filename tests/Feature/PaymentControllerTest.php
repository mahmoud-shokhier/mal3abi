<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use DatabaseMigrations;
    
    /*
    * expected Unauthenticated execption cause wrong signature
    */
    public function test_payment_redirect_with_wrong_signature()
    {
        $requestBody = [
            "cowpay_reference_id"          => 187889,
            "payment_gateway_reference_id" => "975424519",
            "merchant_reference_id"        => "358",
            "customer_merchant_profile_id" => "10",
            "merchant_code"                => "qKB2pmqxI0Yt",
            "order_status"                 => "PAID",
            "amount"                       => "1.06",
            "signature"                    => "39d99e45b231ab7084852297284695b",
            "callback_type"                => "order_status_update"
        ];
        
        $this->json('POST', '/api/v1/payment/redirect', $requestBody)
            ->assertJson(["Unauthenticated"])
            ->assertStatus(401);
    }
    
    /*
     * expected success message , create new transaction but no update in reservation status
    */
    public function test_payment_redirect_with_status_EXPIRED()
    {
        $reservation = \factory(\App\Reservation::class)->create();

        $requestBody = [
            "cowpay_reference_id"          => 187889,
            "payment_gateway_reference_id" => "975424519",
            "merchant_reference_id"        => $reservation->id,
            "customer_merchant_profile_id" => "10",
            "merchant_code"                => "qKB2pmqxI0Yt",
            "order_status"                 => "EXPIRED",
            "amount"                       => "1.06",
            "signature"                    => "10c460838109513b6369755d595726b3",
            "callback_type"                => "order_status_update"
        ];
        
        $response = $this->json('post', '/api/v1/payment/redirect', $requestBody)
            ->assertJson(["status" => true])
            ->assertStatus(200);
        
        $this->assertDatabaseHas('transactions', [
            'merchant_reference_id'        => $requestBody['merchant_reference_id'],
            'payment_gateway_reference_id' => $requestBody['payment_gateway_reference_id'],
            'cowpay_reference_id'          => $requestBody['cowpay_reference_id'],
            'order_status'                 => $requestBody['order_status'],
            'reservation_id'               => $requestBody['merchant_reference_id'],
            'data'                         => json_encode($requestBody)
        ]);
        
        $this->assertDatabaseHas('reservations', [
            'id'        => $reservation->id,
            'status'    => 'pending'
        ]);
    }
        
    /*
    * expected success message , create new transaction and update reservation status
    */
    public function test_payment_redirect_with_right_signature()
    {
        $reservation = \factory(\App\Reservation::class)->create();

        $requestBody = [
            "cowpay_reference_id"          => 187889,
            "payment_gateway_reference_id" => "975424519",
            "merchant_reference_id"        => $reservation->id,
            "customer_merchant_profile_id" => "10",
            "merchant_code"                => "qKB2pmqxI0Yt",
            "order_status"                 => "PAID",
            "amount"                       => "1.06",
            "signature"                    => "042b805f200ea79e74c828781da7b7ad",
            "callback_type"                => "order_status_update"
        ];
        
        $response = $this->json('post', '/api/v1/payment/redirect', $requestBody)
            ->assertJson(["status" => true])
            ->assertStatus(200);
        
        $this->assertDatabaseHas('transactions', [
            'merchant_reference_id'        => $requestBody['merchant_reference_id'],
            'payment_gateway_reference_id' => $requestBody['payment_gateway_reference_id'],
            'cowpay_reference_id'          => $requestBody['cowpay_reference_id'],
            'order_status'                 => $requestBody['order_status'],
            'reservation_id'               => $requestBody['merchant_reference_id'],
            'data'                         => json_encode($requestBody)
        ]);
        
        $this->assertDatabaseHas('reservations', [
            'id'        => $reservation->id,
            'status'    => 'confirmed'
        ]);
    }
}
