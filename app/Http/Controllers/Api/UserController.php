<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    use ApiResponseTrait;
    /**
    * Display a listing of the resource.
    */

    public function index() {
        //
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        //
        $validator = Validator::make( $request->all(), [
            'user_name'=>'required|unique:users,user_name',
            'first_name'=>'required',
            'last_name'=>'required',
            'phone'=>'required|numeric|digits:11|unique:users',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|regex:/^[a-zA-z0-9\-0-9-@#$_.\n\s]+$/|between:8,20',
            'gender' => 'required',
            'address'=>'required',
            'role_name' => 'required|string',
        ] );

        if ( $validator->fails() ) {
            return $this->apiResponseValidation( $validator );
        }
        $user = User::create( [
            'user_name'=>$request->post( 'user_name' ),
            'first_name'=>$request->post( 'first_name' ),
            'last_name'=>$request->post( 'last_name' ),
            'phone'=>$request->post( 'phone' ),
            'email'=>$request->post( 'email' ),
            'password' => Hash::make( $request->post( 'password' ) ),
            'gender'=>$request->post( 'gender' ),
            'address'=>$request->post( 'address' ),
        ] );
        $role_a = $request->role_name;
        if ( $role_a  == 'merchant' ) {
            $role = Role::select( 'id' )->where( 'title', 'merchant' )->first();
            $user->roles()->attach( $role );
            return $this->apiResponse( 'User Created successfully', $user->with( 'roles' )->find( $user->id ) );
        } elseif ( $role_a  == 'consumer' ) {
            $role = Role::select( 'id' )->where( 'title', 'consumer' )->first();
            $user->roles()->attach( $role );
            return $this->apiResponse( 'User Created successfully', $user->with( 'roles' )->find( $user->id ) );
        }

    }

    /**
    * Display the specified resource.
    */

    public function show( int $id ) {
        //
        $user = User::users()->with( 'roles' )->find( $id );
        return $this->apiResponse( $user ?'User received successfully ':'User not found', $user );
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, int $id ) {
        //
        $validator = Validator::make( $request->all(), [
            'user_name'=>'required|unique:users,user_name',
            'first_name'=>'required',
            'last_name'=>'required',
            'phone'=>'required|numeric|digits:11|unique:users',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|regex:/^[a-zA-z0-9\-0-9-@#$_.\n\s]+$/|between:8,20',
            'gender' => 'required',
            'address'=>'required',
            'role_name' => 'required|string',
        ] );

        if ( $validator->fails() ) {
            return response()->json( $validator->errors(), 202 );
        }

        $user = User::users()->find( $id );
        // dd( $user );
        $user->user_name = $request[ 'user_name' ];
        $user->first_name = $request[ 'first_name' ];
        $user->last_name = $request[ 'last_name' ];
        $user->phone = $request[ 'phone' ];
        $user->address = $request[ 'address' ];
        $user->email = $request[ 'email' ];
        if ( $request[ 'password' ] ) {
            $user->password = bcrypt( $request[ 'password' ] );
        }
        $user->gender = $request[ 'gender' ];

        $user->save();

        $role_a = $request->role_name;
        if ( $role_a  == 'merchant' ) {
            $role = Role::select( 'id' )->where( 'title', 'merchant' )->first();
            $user->roles()->sync( $role, true );
            return $this->apiResponse( 'User Updated successfully', $user->with( 'roles' )->find( $user->id ) );
        } elseif ( $role_a  == 'consumer' ) {
            $role = Role::select( 'id' )->where( 'title', 'consumer' )->first();
            $user->roles()->sync( $role, true );
            return $this->apiResponse( 'User Updated successfully', $user->with( 'roles' )->find( $user->id ) );
        }
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( string $id ) {
        //
        User::users()->where( 'id', $id )->delete();
        return $this->apiResponse( 'User Deleted successfully' );
    }
}
