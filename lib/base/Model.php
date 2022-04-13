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
        $this->parseJSON('users');
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
    }


}
