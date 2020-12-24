<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Http\Resources\Contact as ContactResource;

class ContactController extends Controller
{
    public function index()
    {
        // Get articles
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(10);

        // Return collection of contacts as a resource
        return ContactResource::collection($contacts);
    }


    public function store(Request $request)
    {
        $contact = $request->isMethod('put') ? Contact::findOrFail($request->contact_id) : new Contact;

        $contact->id = $request->input('contact_id');
        $contact->first_name = $request->input('first_name');
        $contact->last_name = $request->input('last_name');
        $contact->email = $request->input('email');
        $contact->phone = $request->input('phone');

        if($contact->save()) {
            return new ContactResource($contact);
        }
        
    }

    public function show($id)
    {
        // Get contact
        $contact = Contact::findOrFail($id);

        // Return single contacte as a resource
        return new ContactResource($contact);
    }

    public function destroy($id)
    {
        // Get contact
        $contact = Contact::findOrFail($id);

        if($contact->delete()) {
            return new ContactResource($contact);
        }    
    }









}
