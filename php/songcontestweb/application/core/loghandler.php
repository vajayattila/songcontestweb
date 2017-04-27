<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
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
	private $m_confighandler;
	
	public function __construct(){
		$this->m_confighandler=new confighandler();
	}
	
	public static function get_class_name(){
		return 'loghandler';
	}
	
	public static function get_version(){
		return '1.0.0.0';
	}
	
	/**
	 *  @brief Log message
	 *
	 *  @param [in] $logtype Defined in config.php logger sections. for example: 'error', 'warning', 'info', 'system'
	 *  @param [in] $message
	 */
	public function log_message($logtype, $message){
		$value=$this->m_confighandler->get_value('logger', $logtype);
		$maxlabellength=$this->get_max_label_length();
		if($value!==false){
		// check log folder
			if (!file_exists('application/logs')) {
				mkdir('application/logs', 0777, true);
			}
			// log file name
			$filename='log-'.@date("Y-m-d").'.log';
			$fh=fopen('application/logs/'.$filename, 'a');
			if($fh!==FALSE)
			{
				$logline=@sprintf('%-'.$maxlabellength.'s', strtoupper($logtype)).' - '.@date("Y-m-d H:i:s").' --> '.$message."\r\n";
				fputs($fh, $logline);
				fclose($fh);
			}
		}
	}
	
	/** @brief Return length of the longest logtype's name */
	public function get_max_label_length(){
		$retval=0;
		$arr=$this->m_confighandler->get_keys_by_group_name('logger');
		if($arr!==false){
			foreach($arr as $item){
				$length=strlen($item);
				if($retval<$length){
					$retval=$length;
				}
			}
		}
		return $retval;
	}

}