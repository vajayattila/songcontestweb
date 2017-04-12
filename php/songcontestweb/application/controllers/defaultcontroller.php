<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file workframe.php
 *  @brief Demonstrate controllers using in MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.11
 *  @version 1.0.0.0
 */

class defaultcontroller extends workframe{
	
	public function __construct(){
		$this->setup_dependencies(
				$this->get_class_name(), '1.0.0.0',
			array(
				'workframe'=>'1.0.0.2'
			)
		);
	}
	
	public function get_class_name() {
		return 'defaultcontroller';
	}
	
	public function index(){
		$model=$this->load_model('defaultmodel');
		$lang=$this->load_extension('languagehandler');
		if($this->get_query_parameter('lang')=='hun'){
			$lang->set_language('hungarian');
		} else if($this->get_query_parameter('lang')=='eng'){
			$lang->set_language('english');
		}
		$data=array(
			'baseurl' => $model->get_base_url(),
			'message' => $model->get_message($lang),
			'request_uri' => $this->get_request_uri(),
			'dependencies' => $this->get_array_of_dependencies(),
			'lang' => $lang
		);
		$this->load_view('defaultview', $model, $data);		
	}
	
}