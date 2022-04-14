<?php 

class BlogController extends ApplicationController {

    public function __construct(){
        $this->postDB = new PostModel();
    }

    public function indexAction(){
        $this->view->setTitle('Blog | ' . APP_TITLE);
        if (!$this->userIsLogged()) $this->redirect('/login'); // AuthGuard

        // Send the posts to the view
        $this->view->posts = array_reverse($this->postDB->getPosts());

        if (!$_POST) return;

        $search = $_POST['search'] ?? '';
        $from = ($_POST['from'] ?? '') ? $_POST['from'] : null;
        $to = ($_POST['to'] ?? '') ? $_POST['to'] : null;
        $post = $_POST['postMessage'] ?? '';
        $type = $_POST['type'] ?? '';

        if($type === 'post'){

            // Empty posts or bigger than 255 chars can't continue
            if(!strlen(trim($post)) || strlen(trim($post)) > 255) $this->selfRedirect();

            $data = array(
                'body' => $post,
                'createdBy' => $_SESSION['loggedUser']['username']
            );

            $result = $this->postDB->insertPost($data);

            if($result) $this->selfRedirect(); // Redirects to prevent form resubmission on reload
        }

        if($type === 'search'){

            // If search term, from & to dates are empty the app will self reload 
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

            // Send the obtained results to the view
            $this->view->posts = $posts;
        }
    }

}

?>