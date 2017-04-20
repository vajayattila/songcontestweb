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
				$this->get_class_name(), '1.0.0.1',
			array(
				'defaultmodel'=>'1.0.0.0',
				'workframe'=>'1.0.0.2',
				'sesshandler'=>'1.0.0.0',
			)
		);
	}
	
	public function get_class_name() {
		return 'defaultcontroller';
	}
	
	public function index(){
		$session=$this->load_extension('sesshandler');
		$model=$this->load_model('defaultmodel');
		$lang=$this->load_extension('languagehandler');
		$slang=$session->get('language');
		// set language
		if($this->get_query_parameter('lang')=='hun'){
			$lang->set_language('hungarian');
			$session->set('language', 'hungarian');
		} else if($this->get_query_parameter('lang')=='eng'){
			$lang->set_language('english');
			$session->set('language', 'english');
		} else if($slang) {
			$lang->set_language($slang);
		}
		// set template
		$template=$this->get_query_parameter('template');
		if($this->get_query_parameter('template')=='default'){
			$session->set('css_template', 'default');
		} else if($this->get_query_parameter('template')=='dark'){
			$session->set('css_template', 'dark');
		} else if($this->get_query_parameter('template')=='positive'){
			$session->set('css_template', 'positive');
		} else if($this->get_query_parameter('template')=='negative'){
			$session->set('css_template', 'negative');
		} else {
			$template=$session->get('css_template');
		}
		if(!$template){
			$template=$this->get_config_value('design', 'css_template');
			if(!$template){
				$template='default';
			}
		}
		$data=array(
			'baseurl' => $model->get_base_url(),
			'message' => $model->get_message($lang),
			'request_uri' => $this->get_request_uri(),
			'dependencies' => $this->get_array_of_dependencies(),
			'lang' => $lang,
			'template' => $template,	
		);
		$this->load_view('defaultview', $model, $data);		
	}
	
}