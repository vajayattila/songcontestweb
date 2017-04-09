<?php
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
	protected $m_classes=array();
	protected $m_dependencies=array();
	protected $m_confighandler;
	protected $m_loghandler;
	
	public function __construct(){
		// Setup dependencies
		$m_confighandler=new confighandler();
		$m_loghandler=new loghandler();
		dependency::add_class($m_confighandler->getclassname(), $m_confighandler->getversion());
		dependency::add_class($m_loghandler->getclassname(), $m_loghandler->getversion());
		dependency::setup_dependencies(
			'dependency', '1.0.0.1',
			array(
				'confighandler'=>'1.0.0.0',
				'loghandler'=>'1.0.0.0'
			)
		);
	}
	
	/**
	 * @brief get class name
	 * 
	 * @return a string
	 */
	public function getclassname(){
		return 'dependency';	
	}
	
	/**
	 * @brief get version
	 * 
	 * @return a string for example: 1.0.0.0
	 */
	public function getversion(){
		return $this->m_classes[$this->getclassname()];	
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
		$this->add_class($classname::getclassname(), $version);
		if($dependencies){
			$this->add_dependency($classname, $dependencies);
		}
	}
}