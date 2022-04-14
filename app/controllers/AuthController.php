<?php 

class AuthController extends ApplicationController {
    
    // This class property is to show all the validation errors at once
    protected $errMsg = '';

    public function __construct(){
        $this->userDB = new UserModel();
    }
    
    public function loginAction(){
        $this->view->setTitle('Login | ' . APP_TITLE);
        $this->view->appendScript('eyePass.js'); // Script for the password hide/show feature on the input

        if ($this->userIsLogged()) $this->redirect(); // AuthGuard - Don't allow if the user is already logged
        if (!$_POST) return;

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $data = array(
            'username' => $username, 
            'password' => $password
        );

        $loginAttempt = $this->userDB->checkCredentials($data);

        if($loginAttempt){
            $this->redirect('/blog');
        } else {
            // Login doesn't clarify which field is wrong for security reasons
            $_SESSION['error'] = 'Incorrect username or password';
            $this->redirect('/login');
        }
    }

    public function registerAction(){
        $this->view->setTitle('Create Account | ' . APP_TITLE);
        $this->view->appendScript('eyePass.js'); // Script for the password hide/show feature on the input

        if ($this->userIsLogged()) $this->redirect(); // AuthGuard - Don't allow if the user is already logged
        if (!$_POST) return;

        $username = $_POST['username'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $data = $this->validate(array(
            'username' => $username, 
            'phone' => $phone,
            'email' => $email,
            'password' => $password,
        ));

        // Sends to the session the full error message to be displayed on the view
        if(array_search('invalid', $data)){
            $_SESSION['error'] = $this->errMsg;
            $this->selfRedirect();
        }

        $registerAttempt = $this->userDB->insertUser($data);

        if($registerAttempt['type'] === 'success'){
            $this->redirect('/blog');
        } else {
            $_SESSION['error'] = $registerAttempt['msg'];
            $this->selfRedirect();
        } 
    }

    /**
     * Logout from the application removing the User stored in Session and redirects back to login
     */
    public function logoutAction(){
        $this->view->disableView();

        unset($_SESSION['loggedUser']);
        $this->redirect('/login');
    }

    /**
     * Validates multiple fields at once
     * 
     * @param array the data to be evaluated, can be usernames, phones, email or passwords
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
                case 'phone':
                    if(!preg_match_all('/[0-9]{10,}/', $fieldValue)){
                        $data[$key] = 'invalid';
                        $this->errMsg .= '• Phone numbers can only contain numbers and must have at least 10 numbers.<br>';
                    }
                    break;
                case 'email':
                    if(!preg_match_all('/^[a-z0-9._%+-]+@[a-z0-9.-]{2,}\\.[a-z]{2,4}$/', $fieldValue)){
                        $data[$key] = 'invalid';
                        $this->errMsg .= '• Email field must be a valid email address.<br>';
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