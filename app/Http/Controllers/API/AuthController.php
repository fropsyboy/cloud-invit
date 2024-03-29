<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;
use App\Primary_Notification;

class AuthController extends Controller
{
      /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = new User([
            'name' => $request->name,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();

        $notification = new Primary_Notification;
        $notification->user_id = $user->id;
        $notification->email = $user->email;

        $notification->save();

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user(),200);
    }

        /**
     * Login user and create token
     *
     * @param  [string] email
     * @return [json] user object
     */
    public function reset_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::where('email', $request->email)->first();
        if(!$user)
            return response()->json(['message' => 'Unauthorized, The email dose not exist in our database'], 401);

            $uniq =  md5(uniqid(rand(), true));
            // dd($uniq);

            User::where('id', $user->id)->update([
                'reset_link' => $uniq
                 ]);
        
        
            return response()->json([
            'message' => 'Reset Link successfully created and sent to your email.!'
        ], 201);
    }

           /**
     * Login user and create token
     *
     * @param  [string] email
     * @return [json] user object
     */
    public function password_reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'reset_link' => 'required|string',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::where('email', $request->email)->where('reset_link', $request->reset_link)->first();
        if(!$user)
            return response()->json(['message' => 'Unauthorized, Your Reset link is not correct or has expired'], 401);


        User::where('id', $user->id)->update([
            'password' => bcrypt($request->password)
                ]);
    
    
        return response()->json([
        'message' => 'Your Password has been updated successfully.!'
        ], 201);
    }

}
