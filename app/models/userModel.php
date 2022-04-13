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

    public function insertUser(array $data){

        $user = [
            'id' => intval(microtime(true) * 1000),
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'email' => $data['email'],
            'registerDate' => date('c')
        ];

        $result = $this->writeJSON('users', $user);

        if ($result){
            $result = ['type' => 'success'];
            $_SESSION['loggedUser'] = $user;
        } else {
            $result = ['type' => 'error'];
        }

        return $result;
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