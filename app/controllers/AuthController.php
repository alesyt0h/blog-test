<?php 

class AuthController extends ApplicationController {

    public function __construct(){
        // $this->db = new JsonModel();
    }
    
    public function loginAction(){
        $this->db = new UserModel();
        $this->db->writingTest();

        $this->view->message = "Hello from auth#login";
        $this->view->setTitle('Login Page');
    }

    public function registerAction(){
        $this->db = new PostModel();
        $this->db->writingTest();
        
        $this->view->message = "Hello from auth#register";
        $this->view->setTitle('Register Page');
    }

}

?>