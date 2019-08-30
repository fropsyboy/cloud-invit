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
    

    public function invite()
    {
        $user = auth()->user();

        $mailing = Mailing::where('user_id',$user->id)->orderBy('id','desc')->get();

        return response()->json($mailing,200);

    }

    public function invite_get($invite_id)
    {
        $customs = Mailing::where('id',$invite_id)->first();

        return response()->json($customs,200);

    }


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
            'sender' => $request->sender_name,
            'venue' => $request->venue,
            'address' => $request->address,
            'date' => $request->date,
            'time' => $request->time,
        ]);
        $mailing->save();


        return response()->json([
            'message' => 'Successfully created Mailing!'
        ], 201);
    }

    public function invite_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mailing_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $invite_id = $request->mailing_id;

        $user = auth()->user();

        $checker = Mailing::where('id',$invite_id)->where('user_id', $user->id)->count();

        if ($checker < 1)
            return response()->json(['error' => 'Mailing Id Not found Please Confirm If ID exists'], 401);

        try {

            Mailing::where('id', $invite_id)->update([
                'name' => $request->name,
                'sender' => $request->sender_name,
                'venue' => $request->venue,
                'address' => $request->address,
                'date' => $request->date,
                'time' => $request->time,
                ]);

            return response()->json('Mailing Details Updated successfully',201);

            } catch (\Throwable $e) {

            return response()->json(['error' => $e], 401);

            }

    }

    public function custom()
    {
        $customs = Custom::where('status', 'active')->orderBy('id','desc')->get();

        return response()->json($customs,200);

    }

    public function custom_images($custom_id)
    {
        $customs = Image::where('custom_id',$custom_id)->where('status', 'active')->orderBy('id', 'desc')->get();

        return response()->json($customs,200);

    }

    public function favourite_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'custom_id' => 'required'
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

    public function custom_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $checker = Custom::where('name', $request->name)->where('status', 'active')->first();

        if($checker >= 1)
        return response()->json(['error' => 'Your Image Name Dose Exist Already'], 401);


        $imageName = time().'.'.request()->image->getClientOriginalExtension();

        request()->image->move(public_path('images'), $imageName);

        $custom = new Custom([
            'name' => $request->name,
            'description' => $request->description,
            'path' => 'images/'.$imageName,
            'status' => 'active'
        ]);
        $custom->save();


        return response()->json([
            'message' => 'Successfully uploaded custome image!'
        ], 201);
    }

    public function custom_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'custom_image_id' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if($request->image){
        $imageName = time().'.'.request()->image->getClientOriginalExtension();

        request()->image->move(public_path('images'), $imageName);
        }

        $details = Custom::where('id', $request->custom_image_id)->first();

        Custom::where('id', $request->custom_image_id)->update([
            'name' => $request->name ? $request->name : $details->name,
            'description' => $request->description ? $request->description : $details->description,
            'path' =>  $request->image ? 'images/'.$imageName : $details->path,
            'status' => $request->status ? $request->status : $details->status
            ]);


        return response()->json([
            'message' => 'Successfully updated custome image!'
        ], 201);
    }

    public function image_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'custom_image_id' => 'required',
            'name' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $imageName = time().'.'.request()->image->getClientOriginalExtension();

        request()->image->move(public_path('images'), $imageName);


        $custom = new Image([
            'name' => $request->name,
            'custom_id' => $request->custom_image_id,
            'path' =>  'images/'.$imageName,
            'status' => 'active'
        ]);
        $custom->save();


        return response()->json([
            'message' => 'Successfully uploaded image!'
        ], 201);
    }

    public function image_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if($request->image){
        $imageName = time().'.'.request()->image->getClientOriginalExtension();

        request()->image->move(public_path('images'), $imageName);
        }

        $details = Image::where('id', $request->image_id)->first();

        Image::where('id', $request->image_id)->update([
            'name' => $request->name ? $request->name : $details->name,
            'path' =>  $request->image ? 'images/'.$imageName : $details->path,
            'status' => $request->status ? $request->status : $details->status
            ]);


        return response()->json([
            'message' => 'Successfully updated  image!'
        ], 201);
    }
}
