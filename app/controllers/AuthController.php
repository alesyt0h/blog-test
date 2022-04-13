<?php 

class AuthController extends ApplicationController {

    public function loginAction(){
        $this->view->message = "Hello from auth#login";
        $this->view->setTitle('Login Page');
    }

    public function registerAction(){
        $this->view->message = "Hello from auth#register";
        $this->view->setTitle('Register Page');
    }

}

?>