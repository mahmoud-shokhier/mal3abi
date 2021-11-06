<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\User;
use App\Helpers\Config;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfile;
use Illuminate\Support\Facades\Storage;
use function auth;

class AuthController extends Controller
{
    /**
     * @param UserRequest $request
     *
     * @return UserResource
     * @throws
     */
    public function signup(UserRequest $request)
    {
        return 2;
        $user = User::create($request->validated());
        $token = auth()->attempt(request(['email', 'password']));
        return UserResource::make($user)->additional([
            'token' => $token
        ]);
    }

    /**
     * @param SignInRequest $request
     *
     * @return UserResource|JsonResponse
     */
    public function signin(SignInRequest $request)
    {
        if (!$token = auth()->attempt(request(['email', 'password']))) {
            return response()->json(['error' => 'كلمة المرور غير صحيحة'], 401);
        }
        $user = auth()->user();
        return UserResource::make($user)->additional([
            'token' => $token
        ]);
    }

    public function sendVerificationCode(Request $request)
    {
        $user = $request->user();
        $this->_sendVerificationCode($user->phone, $user->verification_code);
        return response()->json(['status' => true]);
    }

    private function _sendVerificationCode($phone, $code)
    {
        $phone = Config::PhoneKey . $phone;
        $msg = getMsgCode(Config::MsgVerificationCode, $code);
        return _sendSmsByNexmo($phone, $msg);
    }

    /**
     * @return UserResource
     */
    public function profile()
    {
        /** @var User $user */
        $user = auth()->user();
        return UserResource::make($user);
    }

    /**
     * @param UpdateProfile $request
     *
     * @return UserResource
     */
    public function UpdateProfile(UpdateProfile $request)
    {
        
        /** @var User $user */
        $user = auth()->user();
        $user->update($request->validated());
        $token = auth()->attempt(['email' => $user->email, 'password' => $user->password]);
        return UserResource::make($user)->additional([
            'token' => $token
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate(['phone' => 'required|exists:users,phone']);
        $code = mt_rand(1111, 9999);
        User::wherePhone($request->get('phone'))->update(['reset_code' => $code]);
        $phone = Config::PhoneKey . $request->get('phone');
        $msg = getMsgCode(Config::MsgResetPassword, $code);
        _sendSmsByNexmo($phone, $msg);
        return response()->json(['status' => true]);
    }

    /**
     * @param ChangePasswordRequest $request
     *
     * @return JsonResponse
     */
    public function resetPasswordChange(ChangePasswordRequest $request)
    {
        if (!$user = User::wherePhone($request->get('phone'))->whereResetCode($request->get('reset_code'))->first()) {
            return response()->json(['status' => false, 'message' => "الكود غير صحيح"], 400);
        }
        $user->update(['password' => $request->get('password'), 'reset_code' => null]);
        return response()->json(['status' => true]);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteProfile()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
        return response()->json(['status' => true]);
    }
}
