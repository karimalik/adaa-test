<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;

class UserController extends BaseController
{
    /**
     * @author karim kompissi <karimkompissi@gmail.com>
     * @return void
     */
    public function __construct(){
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the ressource
     * @author karim kompissi <karimkompissi@gmail.com>
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $users = User::all(); //fetch all data in the table users

        return $this->sendResponse(UserResource::collection($users), 'Users fetched');
    }

    /**
     * created a new resource in db, this function add a new user in the DB
     * @author karim kompissi <karimkompissi@gmail.com>
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        

        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'firstname' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'password' => 'required',
        ]);

        if($validate->fails()) {
            return $this->sendError('Validation Error.', $validate->errors());
        }

        $data = [
            'name' => $request->name,
            'firstname' => $request->firstname,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'password' => Hash::make($request->password),
        ];

        $user = User::create($data);

        return $this->sendResponse(new UserResource($user), 'User created successfully.');
        
    }

    /**
     * Display the specified 
     * 
     * @author karim kompissi <karimkompissi@gmail.com>
     * @param int $id
     * @return \illuminate\Http\Response
     */
    public function show($id) {

        $user = User::find($id);

        if(is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse(new UserResource($user), 'User retrieved successfully');
    }

    /**
     * update the specified resource
     * @author karim kompissi <karimkompissi@gmail.com>
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'firstname' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'password' => 'required',
        ]);

        if($validate->fails()) {
            return $this->sendError('Validation Error.', $validate->errors());
        }

        $data = [
            'name' => $request->name,
            'firstname' => $request->firstname,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'password' => Hash::make($request->password),
        ];
        
        $user->update($data);

        return $this->sendResponse(new UserResource($user), 'User updated successfully');
    }

    /**
     * remove the specified resource 
     * @author karim kompissi <karimkompissi@gmail.com>
     * @param \App\Models\User $user
     */
    public function delete(User $user) {
        $user->delete();

        return $this->sendResponse([], 'User deleted successfully');
    }
}
