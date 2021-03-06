<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class LoginController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('guest')->except('logout');
//    }
    public function login(Request $request)
    {
        $validator = $this->rules_users($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 402);
        }
        if (!Auth::attempt($request->except('remember'))) {
            return response()->json(['message' => 'Erro no login ou senha'], 402);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
      
        $token->expires_at = Carbon::now()->addDay();
        // Passport::tokensExpireIn(Carbon::now()->addMinute(1));
        // Passport::refreshTokensExpireIn(Carbon::now()->addMinute(1));
        $token->save();
        return response()->json([
            'user' => $user,
            'user_function'=> $user->getRole()->descricao,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
            ],200);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
        return response()->json([
            'message' => 'Logout feito com sucesso'
        ],200);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function rules_users(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        return $validator;
    }

}
