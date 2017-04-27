<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file workframe.php
 *  @brief Demonstrate models using in MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.11
 *  @version 1.0.0.0
 */

class defaultmodel extends workframe{
	
	public function __construct(){
		$this->setup_dependencies(
			$this->get_class_name(), $this->get_version(), 'model',
			array(
					'workframe'=>'1.0.0.2',
					'languagehandler'=>'1.0.0.0'
			)
		);
	}
	
	public function get_class_name() {
		return 'defaultmodel';
	}

	public function get_version(){
		return '1.0.0.2';
	}
	
	public function get_message($lang){
		return $lang->get_item('message');
	}
	
}