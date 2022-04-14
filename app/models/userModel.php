<?php

class UserModel extends Model {

    /**
     * Checks the given credentials 
     * 
     * @param array an array containing the username and the password
     * @return bool
     */
    public function checkCredentials(array $data){

        $this->getUsers();

        $match = false;

        for ($i=0; $i < count($this->_users); $i++) { 
            
            if(strtolower($this->_users[$i]['username']) === strtolower($data['username'])){
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

    /**
     * Inserts a user in the JSON DB
     * 
     * @param array receives the data, containing the username, password, phone and email
     * @return array an array containing the type (sucess or error) and message to return if the email or user are taken
     */
    public function insertUser(array $data){

        $taken = $this->emailOrUsernameTaken($data);

        if($taken) return ['type' => 'error', 'msg' => $taken];

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

    /**
     * Checks if the email or the username are taken
     * 
     * @param array a full user array (username, phone, email, password)
     * @return string containing the is taken message, empty otherwise
     */
    public function emailOrUsernameTaken(array $data){

        $this->getUsers();

        $errMsg = '';

        for ($i=0; $i < count($this->_users); $i++) { 
            if(strtolower($this->_users[$i]['username']) === strtolower($data['username'])){
                $errMsg .= '• This username is taken, please choose another.<br>';
                
            }

            if(strtolower($this->_users[$i]['email']) === strtolower($data['email'])){
                $errMsg .= '• This email address is taken, please choose another.<br>';
            }
        }

        return $errMsg;
    }

}

?>