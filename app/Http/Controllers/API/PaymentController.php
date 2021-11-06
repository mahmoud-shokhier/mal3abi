<?php

namespace App\Http\Controllers\API;

use App\Reservation;
use App\Transaction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RenderCowpay;
use App\Http\Controllers\Controller;
use App\Http\Requests\CowpayWebhook;
use App\Http\Requests\GetTransactionStatus;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * index forms by event Id
     *
     * @param RenderCowpay $request
     *
     * @return Json|Application|Factory|View
     */
    public function renderCowpay(RenderCowpay $request)
    {
        return view('cowpay', ['cowpayReferenceId' => $request->get('cowpayReferenceId')]);
    }

    /**
     * webhook callback to save the transaction data
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function paymentRedirect(CowpayWebhook $request)
    {
        Transaction::Create([
            'merchant_reference_id'        => $request->get('merchant_reference_id'),
            'payment_gateway_reference_id' => $request->get('payment_gateway_reference_id'),
            'cowpay_reference_id'          => $request->get('cowpay_reference_id'),
            'order_status'                 => $request->get('order_status'),
            'reservation_id'               => $request->get('merchant_reference_id'),
            'data'                         => json_encode($request->all())
        ]);

        if ($request->get('order_status') == 'PAID') {
            Reservation::whereId($request->get('merchant_reference_id'))->update(['status' => Reservation::STATUS_DONE]);
        }
        
        return response()->json(['status' => true]);
    }

    /**
     * get the payment status by id
     *
     * @param GetTransactionStatus $request
     *
     * @return Json|JsonResponse
     */
    public function getTransactionStatus(GetTransactionStatus $request)
    {
        $transaction = Transaction::findOrFail($request->get('reservation_id'));

        return response()->json(['payment_status' => $transaction->order_status]);
    }
}
