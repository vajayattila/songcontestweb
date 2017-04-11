<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );

/**
 *  @file helper.php
 *  @brief Helper class for SongContestWeb. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.07
 *  @version 1.0.0.0
 */

require_once('application/core/dependency.php');

/** @brief helpers class */ 
class helper extends dependency{
	
	public function __construct(){
		parent::__construct();
		// Setup dependencies
		helper::setup_dependencies(
			helper::get_class_name(), '1.0.0.0',
			array('dependency'=>'1.0.0.3')	
		);
	}
	
	/**
	 * @brief Get the class name
	 *
	 * @return Return the class name
	 */
	public function get_class_name() {
		return 'helper';
	}
	
	protected function extension_loaded($p_extension_name){
		$retval=extension_loaded ($p_extension_name);
		if(!$retval){
			die('Please load the following extension in the php.ini: '.$p_extension_name);
		}
	}
	
}

/** @brief urlhandler class */
class urlhandler extends helper{
	private $m_acturl; 
	private $m_parameters;
	private $m_methodname;
	private $m_controller;
	private $m_functionname; 
	private $m_segments;
	
	public function __construct(){
		parent::__construct();
		
		// Setup dependencies
		urlhandler::setup_dependencies(
			urlhandler::get_class_name(), '1.0.0.1',
			array(
				'helper'=>'1.0.0.0'
			)
		);
		$tbaseurl=$this->get_base_url();
		$tbaseurl=substr($tbaseurl, strpos($tbaseurl, '://')+3);
		$trequesturl = $this->m_acturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$this->log_message('requestinfo', 'Request URI: '.print_r($trequesturl, true));
		if(substr($trequesturl, 0, strlen($tbaseurl))==$tbaseurl){ // check baseurl on the begin of request url 
			$trequesturl=substr($trequesturl, strlen($tbaseurl)); // trim baseurl from the begin of request url
			// Find parameters
			$poz=strpos($trequesturl, '?');
			if($poz!==FALSE){
				$arr=explode('&', substr($trequesturl, $poz+1));
				foreach($arr as $item)
				{
					$par=explode('=', $item);
					$this->m_parameters[]=array(
						'name' => isset($par[0])?urldecode($par[0]):'',
						'value' => isset($par[1])?urldecode($par[1]):''
					);
				}
				$this->log_message ( 'requestinfo', 'found parameters=' . print_r ( $this->m_parameters, true ) );
				$trequesturl=substr($trequesturl, 0, strpos($trequesturl, '?'));
			}else{
				$this->parameters=array();
			}
			if(!$trequesturl|| strtoupper($trequesturl)=='INDEX.PHP'){ // Not found method
				if(false===$this->get_config_value('routes', 'default')){
					$err='The default controller is not set in config.php.';
					$this->log_message ( 'error', $err);
					die($err);
				}
				$trequesturl='default';
			}else{
				// remove index.php
				$trequesturl=str_replace('index.php/', '', $trequesturl);
				$trequesturl=str_replace('index.php', '', $trequesturl);
			}
			if(!$this->parsemethod($trequesturl)){
				/*Show error*/
				$arr=explode('/', $trequesturl);
				$err='The '.$arr[0].' method not found!';
				log_message ( 'error', $err);
				die($err);
			}
		}else{
			$err='The requested url not content the baseurl. Please check the system section\'s baseurl value in the config.php';
			$this->log_message ( 'error', $err);
			die($err);
		}
	}
	
	/**
	 * @brief Get the class name
	 *
	 * @return Return the class name
	 */
	public function get_class_name() {
		return 'urlhandler';
	}
	
	/**
	 * @brief This function returns the baseurl's value from the config.php
	 */
	public function get_base_url(){
		return $this->get_config_value('system', 'baseurl');	
	}
	
	/*
	 * @brief Parse method
	 */
	protected function parsemethod($turl) {
		$retval = false;
		$urlarray = explode ( '/', $turl );
		// $urlarray[1] is the method name
		// Find method
		if (isset ( $urlarray [0] )) {
			$this->m_methodname = $urlarray [0];
			$this->log_message ('requestinfo', 'found methodname=' . print_r ( $this->m_methodname, true ) );
			$value= $this->get_config_value('routes', $this->m_methodname);
			if ($value!==false) {
				$carr = explode ( '/', $value );
				if (isset ( $carr [0] )) {
					$this->m_controller = urldecode($carr [0]);
					$this->log_message ( 'requestinfo', 'found controller=' . print_r ( $this->m_controller, true ) );
				}
				if (isset ( $carr [1] )) {
					$this->m_functionname = $carr [1];
					$this->log_message('requestinfo', 'found functionname='.print_r($this->m_functionname, true ) );
				}
				for($index = 1; isset ( $urlarray [$index] ); $index ++) {
					$this->m_segments [] = urldecode($urlarray [$index]);
				}
				if (sizeof ( $this->m_segments )) {
					$this->log_message ( 'requestinfo', 'found segments=' . print_r ( $this->m_segments, true ) );
				}
				$retval = true;
			}else{
				$err='The '.$this->m_methodname.' controller is not set in config.php.';
				$this->log_message ( 'error', $err);
				die($err);
			}
		}
		return $retval;
	}

	/** @brief return the requested controller's name.*/
	public function get_controller_name(){
		return $this->m_controller;
	}

	/** @brief return the requested methods's name.*/
	public function get_method_name(){
		return $this->m_methodname;
	}
	
	/** @brief return the requested function's name.*/
	public function get_function_name(){
		return $this->m_functionname;
	}
	
}

/** @brief system class */
class system extends urlhandler{
	public function __construct(){
		parent::__construct();
		// Setup dependencies
		system::setup_dependencies(
			system::get_class_name(), '1.0.0.2',
			array(
			  'urlhandler'=>'1.0.0.0'
			)
		);
	}
	
	/**
	 * @brief Get the class name
	 *
	 * @return Return the class name
	 */
	public function get_class_name() {
		return 'system';
	}
	
}
