<?php 

class BlogController extends ApplicationController {

    public function __construct(){
        $this->postDB = new PostModel();
    }

    public function indexAction(){
        $this->view->setTitle('Blog | ' . APP_TITLE);

        if (!$this->userIsLogged()) $this->redirect('/login');
        // Get Posts and send them to view
        // $this->view->posts = $posts;
        if (!$_POST) return;

        $search = $_POST['search'] ?? '';
        $from = $_POST['from'] ?? '';
        $to = $_POST['to'] ?? '';
        $post = $_POST['postMessage'] ?? '';
        $type = $_POST['type'] ?? '';

        if($type === 'post'){

            if(!strlen(trim($post)) || strlen(trim($post)) > 255) $this->selfRedirect();

            $data = array(
                'body' => $post,
                'createdBy' => $_SESSION['loggedUser']['username']
            );

            $result = $this->postDB->insertPost($data);

        }
        
    }

}

?>