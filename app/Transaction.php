<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Transaction
 *
 * @property int         $id
 * @property int         $merchant_reference_id
 * @property string      $payment_gateway_reference_id
 * @property string      $cowpay_reference_id
 * @property string      $order_status
 * @property string      $reservation_id
 * @property array       $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction whereCowpayReferenceId($value)
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereData($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereMerchantReferenceId($value)
 * @method static Builder|Transaction whereOrderStatus($value)
 * @method static Builder|Transaction wherePaymentGatewayReferenceId($value)
 * @method static Builder|Transaction whereReservationId($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Transaction extends Model
{

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_reference_id', 'payment_gateway_reference_id', 'cowpay_reference_id', 'order_status', 'reservation_id', 'data'
    ];
}
