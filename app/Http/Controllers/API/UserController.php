<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use App\Primary_Notification;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    //
    public function user_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'c_email' => 'required|same:email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $name = $request->name;
        $lname = $request->region;

        $user = User::where('email', $request->email)->first();
        if(!$user)
            return response()->json(['message' => 'Unauthorized, Your Email Dose not Exists in our database'], 401);


        User::where('email', $request->email)->update([
            'email' => $request->email,
            'name' => $request->name,
            'lname' => $request->lname
                ]);
    
    
        return response()->json([
        'message' => 'User details successfully updated.!'
        ], 201);
    }

    public function user_update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = auth()->user();

        User::where('id', $user->id)->update([
            'password' => bcrypt($request->password)
                ]);
    
    
        return response()->json([
        'message' => 'User Password successfully updated.!'
        ], 201);
    }

    public function user_notification_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = auth()->user();

        $notification = new Primary_Notification;
        $notification->user_id = $user->id;
        $notification->email = $request->email;

        $notification->save();
    
    
        return response()->json([
        'message' => 'Notification Email Successfully updated.!'
        ], 201);
    }

    public function user_notification_get()
    {
        $user = auth()->user();

        $customs = Primary_Notification::where('user_id', $user->id)->where('status', 'active')->orderBy('id','desc')->get();

        return response()->json($customs,200);

    }
}
