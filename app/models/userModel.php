<?php

class UserModel extends Model {

    public function writingTest(){

        $user = [
            'id' => intval(microtime(true) * 1000),
            'username' => 'Juanito',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'phone' => '3176845451',
            'registerDate' => date('Y-m-d H:i:s')
        ];

        $this->writeJSON('users', $user);

    }

}

?>