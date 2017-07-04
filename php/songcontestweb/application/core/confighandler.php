<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
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
	private $m_filename;
	private $m_directory;
	private $m_arrayname;

	public function __construct(){
		$this->m_filename='config';
		$this->m_directory='application';
		$this->m_arrayname='config';
	}
		
	public static function get_class_name(){
		return 'confighandler';	
	}
	
	public static function get_version(){
		return '1.0.0.2';
	}
	
	/** @brief get value by group and key*/
	public function get_value($pconfig_group_name, $pconfig_key_name){
		require $_SERVER['DOCUMENT_ROOT'].$this->m_directory.'/'.$this->m_filename.'.php';
		$retval=false;
		if(isset(${$this->m_arrayname}[$pconfig_group_name])){
			if(isset(${$this->m_arrayname}[$pconfig_group_name][$pconfig_key_name])){
				$retval=${$this->m_arrayname}[$pconfig_group_name][$pconfig_key_name];
			}
		}
//		print_r("$this->m_directory/$this->m_filename - $pconfig_group_name => $pconfig_key_name = '$retval' ");
		return $retval;
	}
	
	/** @brief Returns the list of keys by group name*/
	public function get_keys_by_group_name($pconfig_group_name){
		require $_SERVER['DOCUMENT_ROOT'].$this->m_directory.'/'.$this->m_filename.'.php';
		$retval=false;
		if(isset(${$this->m_arrayname}[$pconfig_group_name])){
			$retval=array();
			foreach(${$this->m_arrayname}[$pconfig_group_name] as $key => $value){
				$retval[]=$key;
			}
		}
		return $retval;
	}
	
	/** @brief set directory */
	public function set_directory($directory){
		$this->m_directory=$directory;
	}

	/** @brief set filename */
	public function set_filename($filename){
		$this->m_filename=$filename;
	}

	/** @brief set filename */
	public function set_arrayname($arrayname){
		$this->m_arrayname=$arrayname;
	}
	
}
