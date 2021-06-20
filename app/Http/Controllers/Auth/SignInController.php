<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\User;

class SignInController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index','register']]);
    }

  public function register(Request $request)
  {
    $this->validate($request,[
      'email' => 'required|email|unique:users',
      'password' => 'required|string|min:6',
    ]);

    $user = new User();
    $user->name = 'User_'.$request->email;
    $user->email = $request->email;
    $user->password  =  Hash::make($request->password);
    $user->save();

    event(new Registered($user));

    return response()->json([
      'status' => true,
      'message' => 'New user added Successfully.',
    ]);
  }

  public function index(Request $request)
    {
      $this->validate($request,[
        'email' => 'required|email',
        'password' => 'required|string|min:6',
      ]);

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
