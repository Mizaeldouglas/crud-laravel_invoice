<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 16|1ORPtDPMKGgt4K6UUshtxuesc5jax19QAsAiJ73i -> invoice
// 18|dVTJJMnShpQAce4dAlCttboEgqxkcpTBucWTpXqv -> user

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->response('autorizado', 200, [
                'token' => $request->user()->createToken('invoice')->plainTextToken,
            ]);
        }
        return response()->json([
            'message' => 'Invalid login details',
            'status' => 403,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->response('Logged out', 200);
    }
}
