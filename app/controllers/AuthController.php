<?php 

class AuthController extends ApplicationController {
    
    protected $errMsg = '';

    public function __construct(){}
    
    public function loginAction(){
        $this->view->setTitle('Login | ' . APP_TITLE);

        if (!$_POST) return;

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->validate(array('username' => $username, 'password' => $password));
        $result = array_search('invalid', $result);

        if($result){
            $_SESSION['error'] = $this->errMsg;
            $this->selfRedirect();
        }

    }

    public function registerAction(){
        $this->db = new PostModel();
        $this->db->writingTest();
        
        $this->view->message = "Hello from auth#register";
        $this->view->setTitle('Register Page');
    }

    /**
     * Validates multiple fields at once
     * 
     * @param array the data to be evaluated
     * @return array the data with some values being invalid if validation failed for those fields
     */
    public function validate(array $data){
        foreach ($data as $key => $fieldValue) {
            switch ($key) {
                case 'username':
                    if(!preg_match_all('/(?=.*[0-9].*[0-9]){2,}(?=.*[a-zA-Z].*[a-zA-Z]){4,}/', $fieldValue)){
                        $data[$key] = 'invalid';
                        $this->errMsg .= '• Usernames must have at least 4 letters and 2 numbers, and should not contain special characters.<br>';
                    }
                    break;
                case 'password':
                    if(!preg_match_all('/(?=.*[A-Z])(?=.*[-]){6,}/', $fieldValue)){
                        $data[$key] = 'invalid';
                        $this->errMsg .= '• Passwords must be at least 6 characters long and contain a hyphen and an uppercase letter.<br>';
                    }
                    break;
            }
        }

        return $data;
    }

}

?>