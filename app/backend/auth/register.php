<?php
require_once 'app/backend/core/Init.php';

if (Input::exists()) {
    if (Token::check(Input::get('csrf_token'))) {
        $validate = new Validation();

        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),

            'password' => array(
                'required' => true,
                'min' => 6
            ),

            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            )
        ));

        if ($validate->passed()) {
            try {
                $user->create(array(
                    'username'  => Input::get('username'),
                    'password'  => Password::hash(Input::get('password')),
                    'joined'    => date('Y-m-d H:i:s'), //AMERICAN DATE FORMAT CHANGE TO EUROPEAN ONE DAY!
                    'group_id'    => 1
                ));
                Session::flash('register-success', 'Thanks for registering! You can login now.');
                Redirect::to('index.php');
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo '<div class="alert alert-danger"><strong></strong>' . cleaner($error) . '</div>';
            }
        }
    }
    // This file checks if the form is submitted. If it is, it checks if the csrf_token is valid. If it is, it validates the name, username, password and password_again fields.
}