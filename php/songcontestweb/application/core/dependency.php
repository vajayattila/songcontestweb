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
	private $m_class_types=array();
	
	public function __construct(){
		// Setup dependencies
		if($this->m_confighandler===NULL){
			$this->m_confighandler=new confighandler();
		}
		if($this->m_loghandler===NULL){
			$this->m_loghandler=new loghandler();
		}
		$this->m_loghandler->log_message('system', 'Loading MÜTYÜR PHP MVC workframe...');
		$this->add_class(confighandler::get_class_name(), confighandler::get_version(), 'core');
		$this->add_class(loghandler::get_class_name(), loghandler::get_version(), 'core');
		dependency::setup_dependencies(
			dependency::get_class_name(), dependency::get_version(), 'core',
			array(
				'confighandler'=>'1.0.0.2',
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
		return '1.0.0.3';	
	}
	
	/**
	 * @brief get array of dependencies
	 */
	public function get_array_of_dependencies(){
		// classes
		ksort($this->m_classes);
		return array(
			'registred_classes' => $this->m_classes,
			'dependencies' => $this->m_dependencies
		);
	}
	
	/**
	 * @brief get array of dependencies
	 */
	public function get_array_of_class_types(){
		// classes
		ksort($this->m_class_types);
		return $this->m_class_types;
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
	
	/** @brief Add dependencies*/
	public function add_dependencies($dependencies){
		if(isset($dependencies)){
			foreach($dependencies as $key => $value){
				$this->m_dependencies[$key]=$value;
			}
		}
	}

	/** @brief Add classes*/
	public function add_classes($classes){
		if(isset($classes)){
			foreach($classes as $key => $value){
				$this->m_classes[$key]=$value;
			}
		}
	}

	/** @brief Add class types*/
	public function add_class_types($class_types){
		if(isset($class_types)){
			foreach($class_types as $key => $value){
				$this->m_class_types[$key]=$value;
			}
		}
	}
	
	/**
	 * @brief Add class to dependency system
	 * @param [in] $classname Name of class
	 * @param [in] $version Version of class
	 */
	protected function add_class($classname, $version, $classtype){
		$this->m_classes[$classname]=$version;
		$this->m_class_types[$classname]=$classtype;
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
	
	protected function setup_dependencies($classname, $version, $class_type, $dependencies/*=NULL*/){
		if($this->m_confighandler===NULL){
			$this->m_confighandler=new confighandler();
		}
		if($this->m_loghandler===NULL){
			$this->m_loghandler=new loghandler();
		}
		$this->m_loghandler->log_message('system', 'Register class: '.$classname);
		$this->add_class($classname::get_class_name(), $version, $class_type);
		if($dependencies){
			$this->add_dependency($classname, $dependencies);
		}
	}
	
	/** @brief set directory */
	public function set_directory($directory){
		$this->m_confighandler->set_directory($directory);
	}
	
	/** @brief set filename */
	public function set_filename($filename){
		$this->m_confighandler->set_filename($filename);
	}

	/**
	 * @brief Return the class type by class name
	 * @param [in] $classname Name of class
	 * @retval return the class type string from the m_class_types array. 
	 */
	public function get_type_by_class_name($classname){
		$retval=false;
		if(isset($this->m_class_types[$classname])){
			$retval=$this->m_class_types[$classname];
		}
		return $retval;
	}
	
}