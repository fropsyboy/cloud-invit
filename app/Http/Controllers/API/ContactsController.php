<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contact;
use Validator;
use App\Mailing_Contact;

class ContactsController extends Controller
{
    //

    public function contact($mailing_id)
    {
        try {

        $user = auth()->user();

        $mailing = Mailing_Contact::with('mailing', 'contact')->where('user_id',$user->id)->where('mailing_id', $mailing_id)->orderBy('id','desc')->get();

        return response()->json($mailing,200);

    } catch (\Throwable $e) {

        return response()->json(['error' => 'Mailing Id Dose not Exist'], 401);

    }

    }

    public function contact_all()
    {
        try {

        $user = auth()->user();

        $mailing = contact::where('user_id',$user->id)->orderBy('id','desc')->get();

        return response()->json($mailing,200);

    } catch (\Throwable $e) {

        return response()->json(['error' => 'Error occured while fetching Mailing'], 401);

    }

    }

    public function contact_get($contact_id, $mailing_id)
    {

        try {

        $customs = Mailing_Contact::with('mailing', 'contact')->where('contact_id',$contact_id)->where('mailing_id', $mailing_id)->orderBy('id','desc')->first();

        return response()->json($customs,200);

        } catch (\Throwable $e) {

            return response()->json(['error' => 'Mailing Id or contact Id Dose not Exist'], 401);

        }

    }


    public function contact_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'mailing_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = auth()->user();

        $contact = new Contact([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
        ]);
        $contact->save();

        $mailing_Contact = new Mailing_Contact([
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'mailing_id' => $request->mailing_id,
            'plus' => $request->plus ? $request->plus : 0
        ]);
        $mailing_Contact->save();

        return response()->json([
            'message' => 'Contact Successfully Added!'
        ], 201);
    }

    public function send_mailing($mailing_id)
    {
        try {

        Mailing_Contact::where('id', $mailing_id)->update([
            'status' => 'sent',
            ]);

            return response()->json([
                'message' => 'Mailing Successfully Sent!'
            ], 200);

        } catch (\Throwable $e) {

            return response()->json(['error' => 'Mailing Id Dose not Exist'], 401);

        }

    }


}
