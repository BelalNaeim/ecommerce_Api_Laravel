<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Http\Traits\ApiResponseTrait;

class RoleController extends Controller {
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
            'title' => 'required|unique:roles,title',
            'description' => 'nullable',
        ] );

        if ( $validator->fails() ) {
            return $this->apiResponseValidation( $validator );
        }

        $role = new Role();
        $role->title = $request[ 'title' ];
        $role->description = $request[ 'description' ];

        $role->save();

        return $this->apiResponse( 'role Created successfully', $role );
    }

    /**
    * Display the specified resource.
    */

    public function show( string $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, string $id ) {
        //
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( string $id ) {
        //
    }
}
