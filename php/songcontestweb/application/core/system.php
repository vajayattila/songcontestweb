<?php
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
			helper::getclassname(), '1.0.0.0',
			array('dependency'=>'1.0.0.0')	
		);
	}
	
	/**
	 * @brief Get the class name
	 *
	 * @return Return the class name
	 */
	public function getclassname() {
		return 'helper';
	}
	
	protected function extension_loaded($p_extension_name){
		$retval=extension_loaded ($p_extension_name);
		if(!$retval){
			die('Please load the following extension in the php.ini: '.$p_extension_name);
		}
	}
	
}

/** @brief system class */
class system extends helper{
	public function __construct(){
		parent::__construct();
		// Setup dependencies
		system::setup_dependencies(
			system::getclassname(), '1.0.0.0',
			array(
			  'helper'=>'1.0.0.0',
			  'dependency'=>'1.0.0.0'
			)
		);
	}
	
	/**
	 * @brief Get the class name
	 *
	 * @return Return the class name
	 */
	public function getclassname() {
		return 'system';
	}
	
}