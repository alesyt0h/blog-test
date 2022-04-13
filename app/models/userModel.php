<?php

class UserModel extends Model {

    public function checkCredentials(array $data){

        $this->getUsers();

        $match = false;

        for ($i=0; $i < count($this->_users); $i++) { 
            
            if($this->_users[$i]['username'] === $data['username']){
                $validation = password_verify($data['password'], $this->_users[$i]['password']);

                if($validation){
                    $_SESSION['loggedUser'] = $this->_users[$i];
                    $match = true;
                    break;
                } 
            }
        }

        $this->_users = [];

        return $match;
    }

    public function writingTest(){

        $user = [
            'id' => intval(microtime(true) * 1000),
            'username' => 'Juanito',
            'password' => password_hash('123456-W', PASSWORD_DEFAULT),
            'phone' => '3176845451',
            'registerDate' => date('Y-m-d H:i:s')
        ];

        $this->writeJSON('users', $user);

    }

}

?>