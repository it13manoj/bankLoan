<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Roles;
use Auth;
use Hash;
use Mail;
use Session;

class ApiAuthController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::Make($request->password);
        $token = Str::random(60);
        $user->api_token = hash('sha256', $token);
        $user->role_id = $request->role;
        $user->status = $request->status;
        if ($user->save()) {
            $this->success('success', "You are registered Successfully !");
        } else {
            $this->error('error', "Your are not register !");
        }
    }


    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $users = Auth::user();
            $token = Str::random(60);
            $id = Auth::user()->id;

            $users->api_token = hash('sha256', $token);
            $users->update();
            $data =Auth::user();
            $data->role_id = (Auth::user()->role_id ==1) ? "Admin" : "Users";
            $data->api_token = $token;
            $this->success('success', $data);
        } else {
            $this->error('error', 'User not register');
        }
    }



}
