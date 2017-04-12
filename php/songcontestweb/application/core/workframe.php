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
	private $m_request_method=false;
	private $m_request_uri=false;
	
	public function __construct(){
		parent::__construct();
		// Setup dependencies
		workframe::setup_dependencies(
			workframe::get_class_name(), '1.0.0.2',
			//array(
			  array(
			  	'system' => '1.0.0.2',
			  )
			//)
		);
		// Loads a PHP extensions at runtime
		//$this->extension_loaded('curl');
		if($this->m_request_method===false){
			$this->m_request_method = $_SERVER['REQUEST_METHOD'];
		}
		if($this->m_request_uri===false){
			$this->m_request_uri = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
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
		if($this->m_request_method===false){
			$this->m_request_method = $_SERVER['REQUEST_METHOD'];
		}
		return $this->m_request_method;	
	}

	/** @brief The URI which was given in order to access this page; for instance, '/index.html'. */
	public function get_request_uri(){
		if($this->m_request_uri===false){
			$this->m_request_uri = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
		return $this->m_request_uri;
	}
	
	/** @brief Application entry point.*/
	public function run(){
		$controllername=$this->get_controller_name();
		$filepath='application/controllers/'.$controllername.'.php';
		require_once $filepath;
		$controller=new $controllername();
		$array_of_dependencies=$this->get_array_of_dependencies();
		$controller->add_dependencies($array_of_dependencies['dependencies']);
		$controller->add_classes($array_of_dependencies['registred_classes']);
		if(method_exists ( $controller, $this->get_function_name() )){
			$this->log_message('system', 'Call \''.$this->get_controller_name().'\' controller\'s \''.$this->get_function_name().'\' function.');
			$controller->{$this->get_function_name()}();
			$this->log_message('system', 'Ready.');
		} else {
			$err='The called function of controller does not exist! Check routes setting in config.php! ('.$this->get_function_name().')';
			$this->log_message('error', $err);
			die($err);
		}
	}
	
	/** @brief load a model */
	public function load_model($modelname){
		$filepath='application/models/'.$modelname.'.php';
		require_once $filepath;
		$model=new $modelname();
		$array_of_dependencies=$model->get_array_of_dependencies();
		$this->add_dependencies($array_of_dependencies['dependencies']);
		$this->add_classes($array_of_dependencies['registred_classes']);
		return $model;
	}

	/** @brief load a view */
	public function load_view($viewname, $model, $data){
		$filepath='application/views/'.$viewname.'.php';
		require_once $filepath;
		$this->log_message('system', 'Call \''.$viewname.'\' view.');
	}
	
	/** @brief load a model */
	public function load_extension($extension_name){
		$filepath='application/extensions/'.$extension_name.'.php';
		require_once $filepath;
		$ext=new $extension_name();
		$array_of_dependencies=$ext->get_array_of_dependencies();
		$this->add_dependencies($array_of_dependencies['dependencies']);
		$this->add_classes($array_of_dependencies['registred_classes']);
		return $ext;
	}
	
	
}

$app=new workframe();
$app->run();