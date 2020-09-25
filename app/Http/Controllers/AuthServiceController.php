<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['login','register']);
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);
        try{
            $user=User::create(['name'=>$request->name, 'email'=>$request->email, 'password'=>bcrypt($request->password)]);
            $token = $user->createToken('patricia-app')->plainTextToken;
            $payload=['status'=>'success','message'=>'Successfully Registered','token'=>$token];
        }
        catch (\Exception $e){
            $payload=['status'=>'fail','message'=>$e->getMessage()];
        }
        return response()->json($payload, 200);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        try{
            $credentials=[
                'email' =>$request->email,
                'password'=>$request->password
            ];
            if (Auth::attempt($credentials)){
                $user =  User::whereEmail($credentials['email'])->first();
                $token = $user->createToken('patricia-app')->plainTextToken;
                $payload=['status'=>'success','message'=>'Successfully Authenticated','token'=>$token];
            }
            else{
                $payload=['status'=>'fail','message'=>'unauthenticated'];
                return response()->json($payload, 403);
            }
        }
        catch (\Exception $e){
            $payload=['status'=>'fail','message'=>$e->getMessage()];
        }
        return response()->json($payload, 200);
    }

    public function renewToken(){
        try {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('patricia-app')->plainTextToken;
            $payload=['status'=>'success','message'=>'Successfully Renewed Token','token'=>$token];
        }
        catch (\Exception $e){
            $payload=['status'=>'fail','message'=>$e->getMessage()];
        }
        return response()->json($payload, 200);
    }

    public function userData(){
        return Auth::user();
    }
}
