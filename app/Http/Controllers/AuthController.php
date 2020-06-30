<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreUser;
use App\Notifications\RegistrationNotification;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    function __construct()
    {
        // if ((App::environment() == 'testing') && array_key_exists("HTTP_Authorization",  Request::server())) {
        //     $headers['Authorization'] = Request::server()["HTTP_Authorization"];
        // }
    }
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
            return response()->json([
                'message' => 'Successfully created user!',
                'user' => $user,
            ], 201);
        }

        Log::debug('User was not created, something wrong with server');
        return response()->json([
            'message' => 'Internal Server Error',
        ], 500);
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
     */
    public function login(Request $request)
    {


        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            Log::error('User with email' . $request->email . ' could not log in');
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
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
            return response()->json([
                'message' => 'Successfully logged out',
            ], 200);
        }
        return response()->json([
            'message' => 'Internal Server Error, User not logged out',
        ], 500);
    }
}
