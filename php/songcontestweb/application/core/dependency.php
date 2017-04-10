<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file dependency.php
 *  @brief Dependency classes for SongContestWeb. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.07
 *  @version 1.0.0.0
 */

require_once('application/core/confighandler.php');
require_once('application/core/loghandler.php');

/** @dependancy class */
class dependency{
	private $m_classes=array();
	private $m_dependencies=array();
	private $m_confighandler;
	private $m_loghandler;
	
	public function __construct(){
		// Setup dependencies
		$this->m_confighandler=new confighandler();
		$this->m_loghandler=new loghandler();
		$this->m_loghandler->log_message('system', 'Loading MÜTYÜR PHP MVC workframe...');
		dependency::setup_dependencies($this->m_confighandler->get_class_name(), $this->m_confighandler->get_version());
		dependency::setup_dependencies($this->m_loghandler->get_class_name(), $this->m_loghandler->get_version());
		dependency::setup_dependencies(
			'dependency', '1.0.0.2',
			array(
				'confighandler'=>'1.0.0.1',
				'loghandler'=>'1.0.0.0'
			)
		);
	}
	
	/**
	 * @brief get class name
	 * 
	 * @return a string
	 */
	public function get_class_name(){
		return 'dependency';	
	}
	
	/**
	 * @brief get version
	 * 
	 * @return a string for example: 1.0.0.0
	 */
	public function get_version(){
		return $this->m_classes[$this->get_class_name()];	
	}
	
	/**
	 * @brief get array of dependencies
	 */
	public function get_array_of_dependencies(){
		return array(
			'registred_classes' => $this->m_classes,
			'dependencies' => $this->m_dependencies
		);
	}
	
	public function get_config_value($pconfig_group_name, $pconfig_key_name){
		return $this->m_confighandler->get_value($pconfig_group_name, $pconfig_key_name);
	}

	/**
	 *  @brief Log message
	 *
	 *  @param [in] $logtype Defined in config.php logger sections. for example: 'error', 'warning', 'info', 'system'
	 *  @param [in] $message
	 */
	public function log_message($logtype, $message){
		$this->m_loghandler->log_message($logtype, $message);
	}
	
	/**
	 * @brief Add class to dependency system
	 * @param [in] $classname Name of class
	 * @param [in] $version Version of class
	 */
	protected function add_class($classname, $version){
		$this->m_classes[$classname]=$version;
	}
	
	/**
	 * @brief Add dependencies
	 * 
	 * @param [in] $classname Name of depended class
	 * @param [in] $dependency Array of dependencies.
	 */
	protected function add_dependency($classname, $dependency){
		$this->m_dependencies[$classname]=$dependency;
	}
	
	protected function setup_dependencies($classname, $version, $dependencies=NULL){
		$this->m_loghandler->log_message('system', 'Register class: '.$classname);
		$this->add_class($classname::get_class_name(), $version);
		if($dependencies){
			$this->add_dependency($classname, $dependencies);
		}
	}
}