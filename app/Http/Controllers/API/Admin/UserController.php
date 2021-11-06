<?php

namespace App\Http\Controllers\API\Admin;

use App\User;
use App\Playground;
use App\Helpers\Utilities;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUser;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::name($request->get('name'))
            ->email($request->get('email'))
            ->phone($request->get('phone'))
            ->address($request->get('address'))
            ->role($request->get('role'));

        if ($request->role == User::Playground) {
            $query->addSelect("playground_info.lat", "playground_info.long", "playground_info.price_day", "playground_info.price_night")
                ->join('playground_info', 'playground_id', '=', 'users.id');
            if ($request->lat && $request->long) {
                $distanceQuery = Utilities::distanceQuery($request->lat, $request->long, 'playground_info');
                $query->selectRaw("{$distanceQuery} as distance");
                $query->orderBy("distance", "ASC");
            }
        }

        $users = $query->get();

        if ($request->role == User::Playground) {
            $users = $users->map(function ($user) {
                unset($user->playground_id);
                if (isset($user->distance)) {
                    $user->distance = Utilities::convertMilesToKm($user->distance);
                }
                return $user;
            });
        }

        return response()->json(['status' => true, 'users' => $users]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json(['status' => true, 'user' => $user]);
    }

    public function store(CreateUser $request)
    {
        $user = User::create($request->all());

        if ($user->role == User::Playground) {
            $user->playgroundInfo()->create($request->all());
            $user->load('playgroundInfo');
        }

        return response()->json(['status' => true, 'user' => $user]);
    }

    public function update(UpdateUser $request, $id)
    {
        $user = User::with('playgroundInfo')->findOrFail($id);
        //check if upload new avatar
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            //remove old avatar image
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('users/avatar', 'public');
        }
        $user->update($request->except('role'));

        if ($user->role == User::Playground) {
            Playground::updateOrCreate(
                ['playground_id' => $user->id],
                $request->all()
            );

            $user->load('playgroundInfo');
        }

        return response()->json(['status' => true, 'user' => $user]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['status' => true]);
    }
}
