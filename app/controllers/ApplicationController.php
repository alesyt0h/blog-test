<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ApplicationController extends Controller 
{

    /**
     * Verifies if there is a loggedUser stored in Session
     * @return boolean true if logged, false otherwise 
     */
    protected function userIsLogged(){
        return (isset($_SESSION['loggedUser'])) ? true : false;
    }

    /**
     * Redirects the user to the webroot or another path if specified
     * 
	 * @param string $path must start with slash. Eg. /blog
	 * @return void
	 */
    protected function redirect(string $path = ''){
		header('Location: ' . WEB_ROOT . $path);
        die();
	}

    /**
	 * Redirects the user to same URL, mostly to prevent form resubmission
     * 
	 * @return void
	 */
    protected function selfRedirect(){
		header('Location: ' . WEB_ROOT . substr($_SERVER['REQUEST_URI'], strlen(WEB_ROOT)));
		die();
	}
}
