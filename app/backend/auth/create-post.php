<?php
require_once 'app/backend/core/Init.php';

if (Input::exists()) {
    if (Token::check(Input::get('csrf_token'))) {
        $validate = new Validation();
        echo '1';

        $validation = $validate->check($_POST, array(
            'title' => array(
                'required' => true,
                'min' => 2,
                'max' => 25
            ),

            'content' => array(
                'required' => true,
                'min' => 2,
                'max' => 255,
            ),

            'image' => array(
                'required' => false,
                'min' => 2,
                'max' => 255,
            ),
        ));

        if ($validate->passed()) {
            try {
                echo '2';
                Post::create(array(
                    'title'  => Input::get('title'),
                    'content'  => Input::get('content'),
                    'image'  => Input::get('image'),
                    'user_id'      => $user->data()->uid,
                    'channel_id'    => Input::get('channel_id'),
                ));

                Session::flash('create-post-success', 'Thanks for posting.');
                Redirect::to('posts.php?id=' . Input::get('channel_id'));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo '<div class="alert alert-danger"><strong></strong>' . cleaner($error) . '</div>';
            }
        }
    }
    // This file checks if the form is submitted. If it is, it checks if the csrf_token is valid. If it is, it validates the title and content fields. 
}
