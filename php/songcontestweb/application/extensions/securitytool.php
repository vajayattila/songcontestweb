<?php

/** @brief security helper class*/
class securitytool extends dependency{
	
	public function __construct(){
		parent::__construct();
		securitytool::setup_dependencies(
				securitytool::get_class_name(), '1.0.0.0',
				array('confighandler'=>'1.0.0.2')
				);
	}
	
	public function get_class_name(){
		return 'securitytool';
	}
	
	public function get_version(){
		return '1.0.0.0';
	}
	
	/*
	 * @brief Generate unique ID
	 */
	function getuuid(){
		return $this->md5touuid(md5(uniqid(rand(), true)));
	}
	
	/*
	 * @brief Format md5 to uuid
	 */
	function md5touuid($md5s) {
		$md5s =
		substr ( $md5s, 0, 8 ) . '-' .
		substr ( $md5s, 8, 4 ) . '-' .
		substr ( $md5s, 12, 4 ) . '-' .
		substr ( $md5s, 16, 4 ) . '-' .
		substr ( $md5s, 20 );
		return $md5s;
	}
	
	
}
