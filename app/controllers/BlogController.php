<?php 

class BlogController extends ApplicationController {

    public function __construct(){
        $this->postDB = new PostModel();
    }

    public function indexAction(){
        $this->view->setTitle('Blog | ' . APP_TITLE);
        if (!$this->userIsLogged()) $this->redirect('/login');

        // Send the posts to the view
        $this->view->posts = array_reverse($this->postDB->getPosts());

        if (!$_POST) return;

        $search = $_POST['search'] ?? '';
        $from = ($_POST['from'] ?? '') ? $_POST['from'] : null;
        $to = ($_POST['to'] ?? '') ? $_POST['to'] : null;
        $post = $_POST['postMessage'] ?? '';
        $type = $_POST['type'] ?? '';

        if($type === 'post'){

            if(!strlen(trim($post)) || strlen(trim($post)) > 255) $this->selfRedirect();

            $data = array(
                'body' => $post,
                'createdBy' => $_SESSION['loggedUser']['username']
            );

            $result = $this->postDB->insertPost($data);

            if($result) $this->selfRedirect();
        }

        if($type === 'search'){

            if(!strlen(trim($search)) && !$from && !$to) $this->selfRedirect();

            $this->view->searchInProgress = true;

            $data = array(
                'search' => $search,
                'from' => $from,
                'to' => $to
            );

            $posts = $this->postDB->search($data);

            if(!$posts){
                $this->view->searchFailed = true;
            }

            $this->view->posts = $posts;
        }
    }

}

?>