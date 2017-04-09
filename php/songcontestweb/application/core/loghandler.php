<?php

/**
 *  @file loghandler.php
 *  @brief Logging handler class for SongContestWeb. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.09
 *  @version 1.0.0.0
 */

require_once('application/core/confighandler.php');

/** @brief Workframe class*/
class loghandler{
	protected $m_confighandler;
	
	public function __construct(){
		$this->m_confighandler=new confighandler();
	}
	
	public function getclassname(){
		return 'loghandler';
	}
	
	public function getversion(){
		return '1.0.0.0';
	}
}