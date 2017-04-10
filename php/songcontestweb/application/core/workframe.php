<?php
/**
 *  @file workframe.php
 *  @brief Workframe for MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.07
 *  @version 1.0.0.0
 */

define ( 'mutyurphpmvc_inited', true );

require_once('application/core/system.php');

/** @brief Workframe class*/
class workframe extends system{
	private $m_request_method;
	private $m_request_uri;
	
	public function __construct(){
		parent::__construct();
		// Setup dependencies
		workframe::setup_dependencies(
			workframe::get_class_name(), '1.0.0.0',
			//array(
			  array(
			  	'system' => '1.0.0.1',
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
	public function get_class_name() {
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
		$filepath='application/controllers/'.$this->get_method_name().'.php';
		echo $filepath;
		
		echo 'Request method: '.$this->m_request_method;
		echo 'Request uri: '.$this->m_request_uri;
		print_r(
			$this->get_array_of_dependencies(), false
		);
		$this->log_message('system', 'Ready.');
	}
}

$app=new workframe();
$app->run();