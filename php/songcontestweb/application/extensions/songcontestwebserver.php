<?php

/**
 *  @file songcontestwebserver.php
 *  @brief Songcontestweb RestFul server. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.06.12-2017.06.15
 *  @version 1.0.0.0
 */

require_once ('restserver.php');
require_once ('database.php');
require_once ('securitytool.php');
require_once ('languagehandler.php');
require_once ('sendmail.php');
require_once ('songcontestwebhelpers/initdatabase.php');
require_once ('songcontestwebhelpers/dbmethods.php');

class songcontestwebserver extends restserver{
	use initdatabase, dbmethods;
	protected $db;
	protected $sectool;
	protected $dbname='songcontestweb.db';
	protected $languagehandler;
	protected $sendmail;

	function __construct(){
		parent::__construct();
		songcontestwebserver::setup_dependencies(
			songcontestwebserver::get_class_name(), songcontestwebserver::get_version(), 'extension',
			array(
				'restserver'=>'1.0.0.0',
				'sqlitedb'=>'1.0.0.0',
				'securitytool'=>'1.0.0.1',
				'dependency'=>'1.0.1.0',
				'languagehandler'=>'1.0.0.1',
				'sendmail'=>'1.0.0.0',
			)
		);
		$this->db=new sqlitedb();
		$this->sectool=new securitytool();
		$this->languagehandler=new languagehandler();
		$this->sendmail=new sendmail();
		// for dependencies
		$this->registerobject($this, $this->db);
		$this->registerobject($this, $this->sectool);
		$this->registerobject($this, $this->languagehandler);
		$this->registerobject($this, $this->sendmail);
		// for register actions
		$this->registeractions();
		$this->checkdatabase(); // defined in initdatabase
	}

	protected function registeractions(){
		$this->registredActions=[
			'GET' => ['getversion', 'getmenuitems'],
			'POST' => ['registration'],
			'PUT' => [],
			'DELETE' => []
		];
	}

	public function get_class_name(){
		return 'songcontestwebserver';
	}

	public function get_version(){
		return '1.0.0.1';
	}

	protected function getversion(){
		$vers=$this->dbgetversion();
		$this->response($vers);
	}

	protected function getmenuitems(){
		$statuscode=STATUS_OK;
		$status='';
		$rootitems=$this->dbgetrootmenuitems();
		if($rootitems['statuscode']==STATUS_OK){
			foreach($rootitems['menuitems'] as &$rootitem){
				$subitems=$this->dbgetsubmenuitems($rootitem['id']);
				$rootitem['subItems']=$subitems['subitems'];
				if($subitems['statuscode']!==STATUS_OK){
					$rootitems['statuscode']=$subitems['statuscode'];
					$rootitems['status']=$subitems['status'];
					break;
				}
			}
		}
		$this->response($rootitems);
	}

	protected function registration(){
		$username=$this->requestArgs["userName"];
		$email=$this->requestArgs["email"];
		$password=$this->requestArgs["password"];
		$action=$this->requestArgs["action"];
		$language=$this->languagehandler->get_langname_by_code(
			$this->requestArgs["language"]
		);
		$this->languagehandler->set_language($language);
		$response=$this->dbregistration($username, $email, $password, $action);
		if($response["statuscode"]===STATUS_OK){
			// send activation email
			$response=$this->sendActivationEmail($email, $language);
		}
		$this->response($response);
	}

	protected function sendActivationEmail($email, $language){
		// find user by email
		$return=array();
		$user=$this->dbgetuserbyemail($email);
		$return["statuscode"]=$user["statuscode"];		
		$return["status"]=$user["status"];
		if($return["statuscode"]===STATUS_OK){ // if found it then send activation email
			if($user["user"][0]["verified"]===0){ // if not activated then activate
				$data=array(
					'name' => $user["user"][0]["name"],
					'activation_code' => $user["user"][0]["verifycode"],
					'baseurl' => $this->get_base_url()
				);
				$from_name = $this->get_config_value("sendmail", "sender_name");
				$from = $this->get_config_value("sendmail", "sender_email");
				$subject =$this->languagehandler->get_item("activation_subject");
				if(
					!$this->sendmail->sendmail(
						$from, $from_name, $email, $subject, 'activation_email', 
						$data, $language
					)
				){
					$return["statuscode"]=STATUS_TEMPLATE_CAN_NOT_LOADED;
				}
			} else { // else set statuscode STATUS_EMAIL_IS_ALREADY_ACTIVATED
				$return["statuscode"]=STATUS_EMAIL_IS_ALREADY_ACTIVATED;
			}
		} else { // else set statoscode to STATUS_EMAIL_NOT_FOUND or STATUS_SQL_ERROR
			if($return["statuscode"]===STATUS_USER_NOT_FOUND){
				$return["statuscode"]=STATUS_EMAIL_NOT_FOUND;	
			}
		}
		$return['status']=$this->get_item(STATUS[$return["statuscode"]]);
		//$this->log_message('debug', "sendActivationEmail.return=".print_r($return, true));
		return $return;
	}

	function test(){
		echo 'test is works!';
	}

	function get_item($key){
		return $this->languagehandler->get_item($key);
	}

	function log_message($logtype, $message){
		return $this->languagehandler->log_message($logtype, $message);
	}

}
