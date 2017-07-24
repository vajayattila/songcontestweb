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
require_once ('songcontestwebhelpers/initdatabase.php');
require_once ('songcontestwebhelpers/dbmethods.php');

class songcontestwebserver extends restserver{
	use initdatabase, dbmethods;
	protected $db;
	protected $sectool;
	protected $dbname='songcontestweb.db';
	protected $languagehandler;

	function __construct(){
		parent::__construct();
		songcontestwebserver::setup_dependencies(
			songcontestwebserver::get_class_name(), songcontestwebserver::get_version(), 'extension',
			array(
				'restserver'=>'1.0.0.0',
				'sqlitedb'=>'1.0.0.0',
				'securitytool'=>'1.0.0.1',
				'dependency'=>'1.0.1.0',
				'languagehandler'=>'1.0.0.0'
			)
		);
		$this->db=new sqlitedb();
		$this->sectool=new securitytool();
		$this->languagehandler=new languagehandler();
		// for dependencies
		$this->registerobject($this, $this->db);
		$this->registerobject($this, $this->sectool);
		$this->registerobject($this, $this->languagehandler);
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
		$response=$this->dbregistration($username, $email, $password, $action);
		$this->log_message('debug', print_r($response, true));
		$this->response($response);
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
