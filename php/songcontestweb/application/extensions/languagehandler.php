<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file languagehandler.php
 *  @brief Language handler class for MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.12
 *  @version 1.0.0.0
 */

class languagehandler extends dependency{
	private $m_language;
	private $m_config;
	private $langcodetoname;
	
	public function __construct(){
		languagehandler::setup_dependencies(
			languagehandler::get_class_name(), languagehandler::get_version(), 'extension',
			array(
				'confighandler'=>'1.0.0.2'
			)
		);
		$this->m_language=$this->get_config_value('languages', 'default');
		$this->log_message('system', 'Default language...');
		$this->set_language($this->m_language);
		if(!$this->m_config){
			$this->m_config=new confighandler();
		}
		$this->initLangCodeToName();
	}
	
	public function get_class_name(){
		return 'languagehandler';
	}
	
	public function get_version(){
		return '1.0.0.1';
	}
	
	public function set_language($language){
		if(!$this->m_config){
			$this->m_config=new confighandler();
		}
		$this->m_config->set_arrayname('lang');
		$this->m_config->set_directory('application/languages');
		$this->m_config->set_filename($language);
		$this->m_language=$language;
		$filepath='application/languages/'.$language.'.php';
		require_once $filepath;
		$this->log_message('system', 'Language is set to '.$language);
	}
	
	public function get_language(){
		return $this->m_language;
	}
	
	public function get_item($identifier){
		return $this->m_config->get_value($this->m_language, $identifier);
	}

	protected function initLangCodeToName(){
		$this->langcodetoname=array(
			'hu' => 'hungarian',
			'hun' => 'hungarian',
			'en' => 'english',
			'en' => 'english',
		);
	}

	public function get_langname_by_code($lngcode){
		$lngcode=strtolower($lngcode);
		return $this->langcodetoname[$lngcode];
	}
 
}