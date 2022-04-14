<?php

/**
 * This class manages the JSON files, writing and parsing
 */
class Model
{
	protected $_jsonData = [];
    protected $_users = [];
    protected $_blogPosts = [];

	protected $dbDir = ROOT_PATH . '/db/';

	public function __construct(){
        // Parse the DB's
        $this->parseJSON('posts');
        $this->fetchPosts();

        $this->parseJSON('users');
        $this->fetchUsers();
    }

	/**
     * Parses the given JSON file and stores it on $_jsonData
     * @param string the file to parse. Do not include '.json' in the filename
     */
    public function parseJSON(string $file){
        if(substr($file, -5) !== '.json'){
            $file .= '.json';
        }

        if(!file_exists($this->dbDir . $file)){
            file_put_contents($this->dbDir . $file, '');
        }

        $jsonRaw = file_get_contents($this->dbDir . $file);
        $this->_jsonData = json_decode($jsonRaw, true);

        return $this->_jsonData;
    }

    /**
     * Writes the current array state to the respective JSON database
     * @param string $db the database being written. Only users & posts are valid values
     * @param array $data the data to append to the database
     */
    public function writeJSON(string $db, array $data = []){
        $this->_jsonData = $this->parseJSON($db) ?? [];
        $db = $this->dbChecker($db);

        array_push($this->_jsonData, $data);

        $rawData = json_encode($this->_jsonData, JSON_PRETTY_PRINT);
        $result = @file_put_contents($this->dbDir . substr($db, 1) . '.json', $rawData);

        return $result;
    }

    /**
     * Checks if DB is the correct type
     * @param string $db the database
     * @return exception|string returns an exception if DB wasn't users or posts, else append _ to the DB name and returns it 
     */
    private function dbChecker(string $db){
        return ($db !== 'users' && $db !== 'posts') ? throw new Exception('Not a valid Database!') : $db = '_' . $db; 
    }

    /**
     * Fetches the users stored in the _jsonData temp variable
     * @return array returns the users in the system or an empty array if there is no users
     */
    private function fetchUsers(){
        $this->_users = $this->_jsonData ?? [];
        $this->_jsonData = [];

        return $this->_users;
    }

    /**
     * Fetches the blog posts stored in the _jsonData temp variable
     * @return array returns the posts in the system or an empty array if there are no posts
     */
    private function fetchPosts(){
        $this->_blogPosts = $this->_jsonData ?? [];
        $this->_jsonData = [];

        return $this->_blogPosts;
    }

    /**
     * Get Blog Posts for inherited classes
     * @return array returns the posts or an empty array if no there are no posts
     */
    protected function getBlogPosts(){
        return $this->_blogPosts ?? [];
    }

    /**
     * Get Users for inherited classes
     * @return array returns the users or an empty array if no there are no users
     */
    protected function getUsers(){
        return $this->_users ?? [];
    }

}
