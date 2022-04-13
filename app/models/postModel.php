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

    public function writingTest(){

        $post = [
            'id' => intval(microtime(true) * 1000),
            'createdBy' => intval(microtime(true) * 1000),
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque odio, nihil dicta id eum quis, nostrum officia itaque 
                       optio quam nemo expedita laudantium sint aspernatur ullam debitis perspiciatis! Earum, aspernatur.',
            'postingDate' => DateTime::createFromFormat('U.u', microtime(true))->format('m-d-Y H:i:s.u')
        ];

        $this->writeJSON('posts', $post);

    }

}

?>