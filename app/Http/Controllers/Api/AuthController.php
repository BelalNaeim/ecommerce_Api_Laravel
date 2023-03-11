<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    use ApiResponseTrait;
    //

    public function register( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'user_name' => 'required|string|unique:users',
            'password' => 'required|max:12|min:6',
            'c_password' => 'required|same:password',
        ] );
        if ( $validator->fails() ) {
            return response()->json( $validator->errors(), 202 );
        }

        $user = new User();
        $user->user_name = $request->user_name;
        $user->password = bcrypt( $request->password );
        $user->save();

        return response()->json( [
            'token' => $user->createToken( 'personalToken' )->accessToken,
            'message' => "$user->user_name successfully Registered.."
        ], 200 );
    }

    public function login( Request $request ) {
        $data = [
            'user_name' => $request->user_name,
            'password' => $request->password
        ];
        $user = User::whereUserName( $request->post( 'user_name' ) )->first();
        if ( auth()->attempt( $data ) ) {
            $token = auth()->user()->createToken( 'LaravelAuthApp' )->accessToken;
            return $this->apiResponse( 'Logged in successfully', [ 'token' => $token, $user ], 200 );
        } else {
            return $this->apiResponse( [ 'error' => 'Unauthorised' ], 401 );
        }
    }

    public function logout( Request $request ) {
        $request->user()->token()->revoke();
        return $this->apiResponse( [
            'message' => $request->user()->name.'Logged out sucessfully'
        ], 200 );
    }

}

