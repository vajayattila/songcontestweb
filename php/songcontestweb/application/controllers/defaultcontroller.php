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
	protected $m_lang;
	protected $m_model;
	protected $m_session;
	
	public function __construct(){
		$this->setup_dependencies(
				$this->get_class_name(), '1.0.0.1', 'controller',	
			array(
				'defaultmodel'=>'1.0.0.2',
				'workframe'=>'1.0.0.2',
				'sesshandler'=>'1.0.0.1',
				'languagehandler'=>'1.0.0.0',
			)
		);
	}
	
	public function get_class_name() {
		return 'defaultcontroller';
	}
	
	public function index($caption=''){
		$this->m_session=$this->load_extension('sesshandler');
		$this->m_model=$this->load_model('defaultmodel');
		$this->m_lang=$this->load_extension('languagehandler');
		$slang=$this->m_session->get('language');
		// set language
		if($this->get_query_parameter('lang')=='hun'){
			$this->m_lang->set_language('hungarian');
			$this->m_session->set('language', 'hungarian');
		} else if($this->get_query_parameter('lang')=='eng'){
			$this->m_lang->set_language('english');
			$this->m_session->set('language', 'english');
		} else if($slang) {
			$this->m_lang->set_language($slang);
		}
		// set template
		$template=$this->get_query_parameter('template');
		if($this->get_query_parameter('template')=='default'){
			$this->m_session->set('css_template', 'default');
		} else if($this->get_query_parameter('template')=='dark'){
			$this->m_session->set('css_template', 'dark');
		} else if($this->get_query_parameter('template')=='positive'){
			$this->m_session->set('css_template', 'positive');
		} else if($this->get_query_parameter('template')=='negative'){
			$this->m_session->set('css_template', 'negative');
		} else {
			$template=$this->m_session->get('css_template');
		}
		if(!$template){
			$template=$this->get_config_value('design', 'css_template');
			if(!$template){
				$template='default';
			}
		}
		$data=array(
			'baseurl' => $this->m_model->get_base_url(),
			'message' => $this->m_model->get_message($this->m_lang),
			'request_uri' => $this->get_request_uri(),
			'dependencies' => $this->get_array_of_dependencies(),
			'template' => $template,	
			'caption' => $caption!==''?$caption:$this->m_lang->get_item('caption')
		);
		$this->load_view('defaultview', $this->m_model, $data);		
	}
	
}