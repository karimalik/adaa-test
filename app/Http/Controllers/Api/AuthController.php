<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;

class AuthController extends BaseController
{
    /**
     * resgister user api
     * @author karim kompissi <karimkompissi@gmail.com>
     * @param \Illumminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function signUp(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'firstname' => 'required',
            'phone' => 'required', 
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validate->fails()) {
            return $this->sendError('Validation error', $validate->errors());
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
        $success['token'] = $user->createToken('API TOKEN')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User register successfully');
    }

    /**
     * sign In method
     * @author karim kompissi <karimkompissi@gmail.com>
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function signIn(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            # code...
            $user = Auth::user();
            $success['token'] = $user->createToken('API TOKEN')->plainTextToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'user signin successfully.');
        }
        else {
            return $this->sendError('Auth fail');
        }
    }

    /**
     * signout user
     * @author karim kompissi <karimkompissi@gmail.com>
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function signOut(Request $request) {
       $user = auth()->user()->tokens()->delete();

       return $this->sendResponse($user, 'User signout successfully');
    }
}
