<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        "cowpay_reference_id"           => sprintf("%06d", mt_rand(1, 99999999)),
        "payment_gateway_reference_id"  => sprintf("%06d", mt_rand(1, 99999999)),
        "merchant_reference_id"         => 1,
        "customer_merchant_profile_id"  => "14023",
        "order_status"                  => "UNPAID",
        "amount"                        => "100.00",
        "signature"                     => "4b490927f6f6dc66f6591426874d0a8d",
        "callback_type"                 => "charge_request"
    ];
});
