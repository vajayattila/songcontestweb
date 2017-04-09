<?php
/**
 *  @file confighandler.php
 *  @brief Config handler class for SongContestWeb. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.09
 *  @version 1.0.0.0
 */

/** @brief Workframe class*/
class confighandler{

	public function __construct(){

	}
		
	public function get_class_name(){
		return 'confighandler';	
	}
	
	public function get_version(){
		return '1.0.0.0';
	}
	
	public function get_value($pconfig_group_name, $pconfig_key_name){
		require 'application/config.php';
		$retval=false;
		if(isset($config[$pconfig_group_name])){
			if(isset($config[$pconfig_group_name][$pconfig_key_name])){
				$retval=$config[$pconfig_group_name][$pconfig_key_name];
			}
		}
		return $retval;
	}
}