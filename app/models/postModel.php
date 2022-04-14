<?php

class PostModel extends Model {

    public function insertPost(array $data){

        $post = [
            'id' => intval(microtime(true) * 1000),
            'createdBy' => $data['createdBy'],
            'body' => $data['body'],
            'postingDate' => date('c')
        ];

        $result = $this->writeJSON('posts', $post);

        return $result;
    }

    public function search(array $data){

        $this->data = $data;
        $posts = $this->getPosts();

        // Case insensitive
        $this->search = ($this->data['search']) ? "/(?i)" . $this->data['search'] . "/" : ''; 
        // Since this app can't edit postingDates, first item in the posts array has the first post made in the system
        $this->from = $data['from'] ?? date_format(new DateTime($posts[0]['postingDate']), 'Y-m-d');
        // Today date is the default value for this field 
        $this->to = $data['to'] ?? date('Y-m-d');

        $posts = array_filter($posts, function($post){

            $postDate = date_format(new DateTime($post['postingDate']), 'Y-m-d');

            if($this->from <= $postDate && $this->to >= $postDate){
                if($this->search){
                    if(preg_match($this->search, $post['body'])){
                        return $post;
                    }
                } else {
                    return $post;
                }
            }
        });

        $posts = array_splice($posts, 0);

        return array_reverse($posts);

    }

    public function getPosts(){
        $posts = $this->getBlogPosts();

        return $posts;
    }
}

?>