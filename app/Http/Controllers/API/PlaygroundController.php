<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Requests\PlaygroundRequest;
use App\Http\Resources\PlayGroundResource;
use App\Playground;
use App\Rate;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlaygroundController extends Controller
{
    /**
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = \auth()->user();
        if ($user->role == User::User) {
            $playgrounds = Playground::withRate()
                ->name($request->get('name'))
                ->phone($request->get('phone'))
                ->opened()
                ->orderBy('rate', $request->get('rate_order') ?? 'desc');
        } else
            $playgrounds = $user->playgrounds();
        return PlayGroundResource::collection(
            $playgrounds->distance($request->get('lat'), $request->get('long'))
                ->with('images', 'reservations')
                ->get()
        );
    }

    /**
     * @param Request $request
     * @param         $playground_id
     *
     * @return PlayGroundResource
     */
    public function show(Request $request, $playground_id)
    {
        /** @var User $user */
        $user = \auth()->user();
        if ($user->role == User::User) {
            $playground = Playground::withRate()
                ->distance($request->get('lat'), $request->get('long'))
                ->orderBy('rate', $request->get('rate_order') ?? 'desc');
        } else
            $playground = $user->playgrounds();
        return PlayGroundResource::make($playground->with('images', 'reservations')->firstOrFail($playground_id));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function setRate(Request $request)
    {
        /** @var User $user */
        $user = \auth()->user();
        $request->validate(
            [
                'rate'          => 'required|numeric|min:1|max:5',
                'playground_id' => 'required|exists:playgrounds,id'
            ]
        );
        Rate::updateOrCreate(
            [
                'user_id'       => $user->id,
                'playground_id' => $request->get('playground_id')],
            ['rate' => $request->get('rate')]
        );
        return response()->json(['status' => true]);
    }

    /**
     * @param PlaygroundRequest $request
     *
     * @return AnonymousResourceCollection
     * @throws \Throwable
     */
    public function store(PlaygroundRequest $request)
    {
        /** @var User $user */
        $user = \auth()->user();
        \DB::transaction(function () use ($user, $request) {
            $playground = $user->playgrounds()->create($request->validated());
            $playground->setImages($request->file('images'));
        });
        \DB::commit();
        return PlayGroundResource::collection($user->playgrounds()->with('images', 'reservations')->get());
    }

    /**
     * @param PlaygroundRequest $request
     * @param                   $playground_id
     *
     * @return AnonymousResourceCollection
     * @throws \Throwable
     */
    public function update(PlaygroundRequest $request, $playground_id)
    {
        /** @var User $user */
        $user = \auth()->user();
        $playground = $user->playgrounds()->findOrFail($playground_id);
        \DB::transaction(function () use ($playground, $request) {
            $playground->update($request->validated());
            $playground->setImages($request->file('images'));
        });
        \DB::commit();
        return PlayGroundResource::collection($user->playgrounds()->with('images', 'reservations')->get());
    }
}
