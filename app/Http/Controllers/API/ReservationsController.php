<?php

namespace App\Http\Controllers\API;

use App\Helpers\Config;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Playground;
use App\Reservation;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ReservationsController extends Controller
{
    /**
     * @param ReservationRequest $request
     * @param                    $playground_id
     *
     * @return ReservationResource
     */
    public function store(ReservationRequest $request, $playground_id)
    {
        /** @var User $user */
        $user = \auth()->user();
        $playground = Playground::where('playground_id', $playground_id)->firstOrFail();
        $reservation = $playground->reservations()->create($request->validated() + ['user_id' => $user->id]);
        return ReservationResource::make($reservation);
    }


    /**
     * @param $reservation_id
     *
     * @return ReservationResource
     */
    public function show($reservation_id)
    {
        /** @var User $user */
        $user = \auth()->user();
        $reservation = $user->reservations()->with('playground', 'user')->findOrFail($reservation_id);
        return ReservationResource::make($reservation);
    }

    /**
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = \auth()->user();
        $reservations = $user->reservations()
            ->day($request->get('day'))
            ->status($request->get('status'));
        return ReservationResource::collection($reservations);
    }

    /**
     * @param ReservationRequest $request
     * @param                    $reservation_id
     *
     * @return ReservationResource|JsonResponse
     */
    public function update(ReservationRequest $request, $reservation_id)
    {
        /** @var User $user */
        $user = \auth()->user();
        $reservation = $user->reservations()->findOrFail($reservation_id);
        if ($reservation->status == Reservation::STATUS_DONE) {
            return response()->json(['status' => false, 'message' => 'عفوا! لا يمكنك التعديل على الحجز'], 400);
        }
        $reservation->update($request->validated());
        return ReservationResource::make($reservation);
    }

    /**
     * @param $reservation_id
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete($reservation_id)
    {
        /** @var User $user */
        $user = \auth()->user();
        $reservation = $user->reservations()->findOrFail($reservation_id);
        if ($reservation->status != Reservation::STATUS_DONE) {
            $reservation->delete();
            return response()->json(['status' => true, 'message' => 'reservation removed']);
        }
        return response()->json(['status' => false, 'message' => 'unauthorized.'], 401);
    }
}
