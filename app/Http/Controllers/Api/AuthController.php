<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //v1
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {  //if guard
            return new JsonResponse([
                'message' => [trans('auth.failed')]
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::whereEmail($request->email)->firstOrFail();

        $token = $user->createToken('auth-token')->plainTextToken; //jwt token

        $cookie = cookie('jwt-token', $token, 60*24); // 1 day

        return response()->json([
            'message' => 'success',
            'user' => $user->name,
            'email' => $user->email,
        ])->withCookie($cookie);
    }

    public function logout(){
        $cookie = Cookie::forget('jwt-token');     
        Auth::logout();
        return response()->json([
            'message' => 'success',
        ])->withCookie($cookie);
    }
    public function user(){
        return Auth::user();
    }


    //v2 
    public function loginV2(Request $request) :JsonResponse
    { 
        $credentials = $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {  //if guard
            return new JsonResponse([
                'message' => [trans('auth.failed')]
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::whereEmail($request->email)->firstOrFail();

        $token = $user->createToken('auth-token')->plainTextToken; //jwt token

        $cookie = cookie('jwt-token', $token, 60*24); // 1 day

        return response()->json([
            'message' => 'success',
            'user' => $user->name,
            'email' => $user->email,
        ])->withCookie($cookie);
    }
}
