<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContact;
use App\Mail\ContactConfirmationMail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    /**
     * Show the form for creating mail.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact.create');
    }

    /**
     * Send the message to receiver.
     *
     * @param  \Illuminate\Http\Requests\StoreContact  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function store(StoreContact $request)
    {

        // l'email est envoyé à l'adresse mail de l'équipe du site

        Mail::to($request->email)->send(new ContactConfirmationMail());

        $message = [
            'complete_name' => $request->complete_name,
            'email'         => $request->email,
            'subject'       => $request->subject,
            'message'       => purifier($request->message)
        ];

        // dd($message['email']);

        Mail::to('help.goshr@gmail.com')->send(new ContactMail($message));

        // s'il n'y a des erreurs dans le formulaire
        if (count(Mail::failures()) > 0) {
            // je redirige l'utilisateur avec le.s message.s erreur.s
            return redirect()->route('contact.create')->with('error', "Une erreur s'est produite lors de l'envoi de ton mail. Nous nous excusons de la gêne occasionnée.");
        } else {
            // je redirige l'utilisateur avec un message de confirmation
            return redirect()->route('contact.create')->with('status', "Ton email nous est parvenu ! Nous te remercie d'avoir pris le temps de nous écrire!");
        }
    }
}
