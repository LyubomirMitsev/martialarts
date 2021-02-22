<?php namespace LyubomirMitsev\Contact\Components;

use Cms\Classes\ComponentBase;
use Input;
use Mail;
use Validator;
use Redirect;
use Flash;

class ContactForm extends ComponentBase {

    public function componentDetails()
    {
        return [
            'name' => 'Contact Form',
            'description' => 'Simple contact form'
        ];
    }

    public function onSend()
    {
        $validator = Validator::make(
            [
                'name' => Input::get('name'),
                'email' => Input::get('email'),
                'content' => Input::get('content')
            ],
            [
                'name' => 'required',
                'email' => 'required|email',
                'content' => 'required|min:10'
            ]
        );

        if($validator->fails()) 
        {
            // return Redirect::back()->withErrors($validator);

            return ['#result' => $this->renderPartial('contactform::errors', [
                'errorMessages' => $validator->messages()->all(),
                'fieldMessages' => $validator->messages()
            ])];
        }
        else
        {
            $vars = ['name' => Input::get('name'), 'email' => Input::get('email'), 'content' => Input::get('content')];

            Mail::send('lyubomirmitsev.contact::mail.message', $vars, function($message) {
    
                $message->to('admin@gmail.com', 'Admin Person');
                $message->subject('New message from contact form!');
    
            });

            Flash::success('Email Send!');
            return Redirect::back();
        }
    }
}