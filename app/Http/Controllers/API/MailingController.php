<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;
use App\Primary_Notification;
use App\Mailing;
use App\Custom;
use App\Favourite;
use App\Image;

class MailingController extends Controller
{
    //
    public function invite_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = auth()->user();

        $mailing = new Mailing([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $mailing->save();


        return response()->json([
            'message' => 'Successfully created Mailing!'
        ], 201);
    }

    public function invite()
    {
        $user = auth()->user();

        $mailing = Mailing::where('user_id',$user->id)->orderBy('id','desc')->get();

        return response()->json($mailing,201);

    }

    public function custom()
    {
        $customs = Custom::orderBy('id','desc')->get();

        return response()->json($customs,201);

    }

    public function custom_images($custom_id)
    {
        $customs = Image::where('custom_id',$custom_id)->orderBy('id', 'desc')->get();

        return response()->json($customs,201);

    }

    public function favourite_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'custom_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $checker = Custom::where('id', $request->custom_id)->count();

        if($checker < 1)
        return response()->json(['error' => 'Your Custome Image ID Dose not Exist'], 401);

        $user = auth()->user();

        $favourite = new Favourite([
            'user_id' => $user->id,
            'custom_id' => $request->custom_id
        ]);
        $favourite->save();


        return response()->json([
            'message' => 'Successfully created Mailing!'
        ], 201);
    }
}
