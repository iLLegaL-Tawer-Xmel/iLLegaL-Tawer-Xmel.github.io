<?php

session_start();

class errors {
    public function new_error($color, $title, $text) {
        $_SESSION['error_color'] = $color;
        $_SESSION['error_title'] = $title;
        $_SESSION['error_text'] = $text;
    }

    public function view() {
        $error_color = $_SESSION['error_color'];
        $error_title = $_SESSION['error_title'];
        $error_text = $_SESSION['error_text'];

        if (!empty($error_color) && $error_color !== ' ' && !empty($error_text) && $error_text !== ' '){
            $data_error = '
               <div class="alert alert-'.$error_color.'" role="alert">
                      <h4 class="alert-title">'.$error_title.'</h4>
                      <div class="text-muted">'.$error_text.'</div>
                </div>
            ';
            $_SESSION['error_color'] = '';
            unset($_SESSION['error_color']);
            $_SESSION['error_text'] = '';
            unset($_SESSION['error_text']);
            return $data_error;
        }
    }
}