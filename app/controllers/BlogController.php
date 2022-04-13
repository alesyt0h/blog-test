<?php 

class BlogController extends ApplicationController {

    public function indexAction(){
        $this->view->message = "Hello from blog#index";
        $this->view->setTitle('Blog Page');
    }

}

?>