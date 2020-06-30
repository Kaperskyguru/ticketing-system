<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreUser;
use App\Http\Requests\ValidateLogin;
use App\Notifications\RegistrationNotification;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(StoreUser $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => false
        ]);

        if ($user) {
            Cache::put('user_id_' . $user->id, $user, 60);

            Log::info('New User with id: ' . $user->id . ' created and cached');

            $user->notify(new RegistrationNotification($user));

            return $this->response('Successfully created user!', 201, $user, 'user');
        }

        Log::debug('User was not created, something wrong with server');
        return $this->response('Internal Server Error', 500);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     * @return [object] user
     */
    public function login(ValidateLogin $request)
    {

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            Log::error('User with email' . $request->email . ' could not log in');
            throw new AuthenticationException('Login details not valid');
        }

        $user = $request->user();

        if ($user->is_admin) {
            $tokenResult = $user->createToken('Personal Access Token', ['can-edit', 'can-add', 'can-delete']);
        } else {
            $tokenResult = $user->createToken('Personal Access Token', ['can-view', 'can-buy', 'can-join']);
        }

        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        Log::info('User with id: ' . $user->id . ' logged in successfully');

        return response()->json([
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {

        if (auth()->user()->token()->revoke() && auth()->user()->token()->delete()) {
            Log::info('User with id: ' . $request->user()->id . ' logout in successfully');
            return $this->response('Successfully logged out', 200);
        }
        Log::debug('User with id: ' . $request->user()->id . ' could not logout, Internal Server Error');
        return $this->response('Internal Server Error, User not logged out', 500);
    }
}
