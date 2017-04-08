<?php
/**
 *  @file workframe.php
 *  @brief Workframe for SongContestWeb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.07
 *  @version 1.0.0.0
 */

require_once('application/core/system.php');

/** @brief Workframe class*/
class workframe extends system{
	protected $m_request_method;
	protected $m_request_uri;
	
	public function __construct(){
		parent::__construct();
		// Setup dependencies
		workframe::setup_dependencies(
			workframe::getclassname(), '1.0.0.0',
			//array(
			  array(
			  	'system' => '1.0.0.0',
			  	'helper'=>'1.0.0.0',
			  	'dependency'=>'1.0.0.0'
			  )
			//)
		);
		// Loads a PHP extensions at runtime
		//$this->extension_loaded('curl');
		$this->m_request_method = $_SERVER['REQUEST_METHOD'];
		$this->m_request_uri = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	/**
	 * @brief Get the class name
	 *
	 * @return Return the class name
	 */
	public function getclassname() {
		return 'workframe';
	}
	
	/** @brief Which request method was used to access the page; i.e. 'GET', 'HEAD', 'POST', 'PUT'. */
	public function get_request_method(){
		return $this->m_request_method;	
	}

	/** @brief The URI which was given in order to access this page; for instance, '/index.html'. */
	public function get_request_uri(){
		return $this->m_request_uri;
	}
	
	/** @brief Application entry point.*/
	public function run(){
		echo 'Request method: '.$this->m_request_method;
		echo 'Request uri: '.$this->m_request_uri;
		print_r($this->get_array_of_dependencies(), false);
	}
}

$app=new workframe();
$app->run();