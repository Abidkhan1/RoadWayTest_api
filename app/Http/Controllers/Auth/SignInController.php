<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SignInController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

  public function index(Request $request)
    {
      // return response()->json([
      //   'msg' => 'hello login API',
      //   'req' => $request->all(),
      // ]);

      if (!$token = auth()->attempt($request->only('email','password'))) {
        return response(null, 401);
      }

      return response()->json([
        'token' => $token
      ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
