<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' ); 

require_once('application/extensions/securitytool.php');
	
/**
 *  @file sessionhandler.php
 *  @brief Session handler classes for MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.20-2017.04.27
 *  @version 1.0.0.1
 */

/** @brief File session handler interface*/
class fsesshdlr implements SessionHandlerInterface{
	private $m_obj;
	private $m_session_path;
	private $m_session_lifetime;
	private $m_securitytool=NULL;
	private $m_sessionisencrypted=false;
	private $m_sessionencryptionkey;
	
	public function set_object(&$obj){
		$this->m_obj=$obj;
		$this->m_sessionisencrypted=$this->m_obj->get_config_value('system', 'sessionisencrypted');
		if($this->m_sessionisencrypted!==false){
			$this->m_sessionencryptionkey=$this->m_obj->get_config_value('system', 'sessionencryptionkey');
			if($this->m_sessionencryptionkey===false){
				$this->m_obj->log_message('session', "Missing the system/sessionencryptionkey value in config.");
				die("Missing the system/sessionencryptionkey value in config.");
			}
		}
	}
	
	/* Methods */
	public function open($save_path , $session_name){
		// read config
		$path=$this->m_obj->get_config_value('system', 'sessionpath');
		if($path){
			$this->m_session_path=$path;
		} else {
			$this->m_session_path=$save_path;
		}
		if (!is_dir($this->m_session_path)) {
			mkdir($this->m_session_path, 0777);
		}
		$this->gc(3600);
		return true;
	}
	
	public function close(){
		return true;
	}
	
	public function read($id){
		$this->m_obj->log_message('session', "Read session data from $this->m_session_path/sess_$id");
		$data=(string)@file_get_contents("$this->m_session_path/sess_$id");
		if($this->m_sessionisencrypted!==false){
			$data=$this->m_obj->decodestring($data, $this->m_sessionencryptionkey);
			if($data===false){
				$data='';
			}
		}
		return $data;
	}
	
	public function write($id, $data){
		$this->m_obj->log_message('session', "Ready: Write session data to $this->m_session_path/sess_$id");
		$sessionisencrypted=$this->m_obj->get_config_value('system', 'sessionisencrypted');
		if($this->m_sessionisencrypted!==false){
			$data=$this->m_obj->encodestring($data, $this->m_sessionencryptionkey);
		}	
		return @file_put_contents("$this->m_session_path/sess_$id", $data) === false ? false : true;
	}
	
	public function destroy($id)
	{
		$this->m_obj->log_message('session', "Destroy $this->m_session_path/sess_$id session file ");
		$file = "$this->m_session_path/sess_$id";
		if (@file_exists($file)) {
			@unlink($file);
		}
		
		return true;
	}
	
	public function gc($maxlifetime)
	{
		$lifetime=$this->m_obj->get_config_value('system', 'sessiontimeout');
		if($lifetime){
			$this->m_session_lifetime=$lifetime;
		} else {
			$this->m_session_lifetime=$maxlifetime;
		}
		foreach (@glob("$this->m_session_path/sess_*") as $file) {
			if (@filemtime($file) + $this->m_session_lifetime< time() && @file_exists($file)) {
				$this->m_obj->log_message('session', "Destroy expired $this->m_session_path/$file session file ");
				@unlink($file);
			}
		}
		
		return true;
	}
}

class sessionlogic extends securitytool{
	protected $m_timeout;

	public function __construct(){
		parent::__construct();
		// Constans
		$this->conddefine('SESSION_OK', 0);
		$this->conddefine('SESSION_EXPIRED', 1);
		$this->conddefine('SESSION_INVALID_SESSIONID', 2);
		$this->conddefine('SESSION_IPADDRESS_MISMATCH', 3);
		$this->conddefine('SESSION_USERAGENT_MISMATCH', 4);
		$this->conddefine('SESSION_SESSIONID_MISMATCH', 5);
		$this->conddefine('SESSION_IPADDRESS_MISSING', 6);
		$this->conddefine('SESSION_LASTACTIVITY_MISSING', 7);
		// Dependencies
		sessionlogic::setup_dependencies(
			sessionlogic::get_class_name(), sessionlogic::get_version(), 'extension',
			array(
				'securitytool'=>'1.0.0.1'
			)
		);
		// Get timeout value
		$this->m_timeout=$this->get_config_value('system', 'sessiontimeout');
		if($this->m_timeout===false){
			$this->m_timeout=3600; // default 1 hour
		}
	}	
	
	public function get_class_name(){
		return 'sessionlogic';
	}
	
	public function get_version(){
		return '1.0.0.1';
	}

	public function set($name, $value){
		$this->log_message('session', "Set session variable: $name=>$value");
		$_SESSION[$name]=$value;
	}
	
	public function get($name){
		$retval=false;
		if(isset($_SESSION[$name])){
			$this->log_message('session', "Get session variable: $name=>$_SESSION[$name]");
			$retval=$_SESSION[$name];
		}
		return $retval;
	}
	
	public function uset($name){
		if(isset($_SESSION[$name])){
			$this->log_message('session', "Unset session variable: $name");
			unset($_SESSION[$name]);
		}
	}
	
	/** @brief Create session identifiers*/
	protected function create(){
		$retval = SESSION_OK;
		if($this->get('session_id')){
			$retval = $this->validate();
			if ($retval == SESSION_OK) {
				$this->set ( 'last_activity', date ( 'c' ) );
				$temp=$this->get('last_activity');
				$this->log_message('session', "Updated last_activity session variable: $temp");
			} else if ($retval == SESSION_EXPIRED) {
				// destroyed session in validate method
			}
		} else { // new session 
			$this->destroy ();
			$this->set('session_id', $this->getuuid ());
			$this->set('ip_address', $_SERVER ['REMOTE_ADDR']);
			$this->set('user_agent', $_SERVER ['HTTP_USER_AGENT']);
			$this->set('last_activity', date ( 'c' ));
			$this->log_message('session', "Created new session");
		}
	}
	
	protected function destroy(){
		$this->log_message('session', "Unset all session variables");
		foreach($_SESSION as $key => $value){
			$this->uset($key);
		}
	}

	protected function validate($sessionid = '') {
		$retval = SESSION_OK;
		$ipaddress = $_SERVER ['REMOTE_ADDR'];
		$useragent = $_SERVER ['HTTP_USER_AGENT'];
		
		if (preg_match ( '/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$/', $this->get ( 'session_id' ) ) != 1) {
			$retval = SESSION_INVALID_SESSIONID;
			$this->destroy();
		} else if ($this->get ( 'ip_address' ) === false) {
			$retval = SESSION_IPADDRESS_MISSING;
			$this->destroy();
		} else if ($ipaddress != $this->get ( 'ip_address' )) {
			$retval = SESSION_IPADDRESS_MISMATCH;
			$this->destroy();
		} else if ($useragent != $this->get ( 'user_agent' )) {
			$retval = SESSION_USERAGENT_MISMATCH;
			$this->destroy();
		} else if ($sessionid != '') {
			if ($sessionid != $this->get ( 'session_id' )) {
				$retval = SESSION_SESSIONID_MISMATCH;
				$this->destroy();
			}
		} else if($this->get ( 'last_activity' )===false){
			$retval = SESSION_LASTACTIVITY_MISSING;
			$this->destroy();
		} else if ((time () - strtotime($this->get ( 'last_activity' ))) > $this->get_time_out()) {
			$retval = SESSION_EXPIRED;
			$this->destroy();
		}
		$this->log_message('session', "Session validate returned: $retval");
		return $retval;
	}
	
	protected function get_time_out(){
		return $this->m_timeout;
	}
	
	protected function conddefine($name, $value){
		if(defined($name)===FALSE)
		{
			define($name, $value);
		}
	}
}


/** @brief File session handler */
class sesshandler extends sessionlogic{
	protected $m_handler;
	
	public function __construct(){
		parent::__construct();
		// Dependency
		sesshandler::setup_dependencies(
			sesshandler::get_class_name(), sesshandler::get_version(), 'extension',
			array(
				'sessionlogic'=>'1.0.0.1'
			)
		);
		// set session type
		$sesstype=$this->get_config_value('system', 'sessiontype');
		if($sesstype===false){
			$sesstype='file';
		}
		if(strtoupper($sesstype)=='FILE'){
			$this->m_handler=new fsesshdlr();
		} else {
			die ("Unknown session type! Please set the system/sessiontype's value in the config.php!");
		}
		$this->m_handler->set_object($this);
		session_set_save_handler($this->m_handler, true);
		session_start();
		$this->create();
		$this->validate();
	}
	
	public function get_class_name(){
		return 'sesshandler';
	}
	
	public function get_version(){
		return '1.0.0.1';
	}
	
}
	