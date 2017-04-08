<?php
/**
 *  @file abstracts.php
 *  @brief Abstract classes for SongContestWeb. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.07
 *  @version 1.0.0.0
 */

/** @dependancy class */
abstract class dependency{
	protected $m_classes=array();
	protected $m_dependencies=array();
	
	public function __construct(){
		// Setup dependencies
		dependency::add_class('dependency', '1.0.0.0');
	}
	
	/**
	 * @brief get class name
	 * 
	 * @return a string
	 */
	abstract public function getclassname();
	
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
		//print_r($dependency);
		$this->m_dependencies[$classname]=$dependency;
	}
	
	protected function setup_dependencies($classname, $version, $dependencies=NULL){
		$this->add_class($classname::getclassname(), $version);
		if($dependencies){
			$this->add_dependency($classname, $dependencies);
		}
	}
}