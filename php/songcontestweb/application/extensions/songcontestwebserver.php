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
require_once ('songcontestwebhelpers/initdatabase.php');
require_once ('songcontestwebhelpers/dbmethods.php');

class songcontestwebserver extends restserver{
	use initdatabase, dbmethods;
	protected $db;
	protected $sectool;
	protected $dbname='songcontestweb.db';

	function __construct(){
		parent::__construct();
		songcontestwebserver::setup_dependencies(
			songcontestwebserver::get_class_name(), songcontestwebserver::get_version(), 'extension',
			array(
				'restserver'=>'1.0.0.0',
				'sqlitedb'=>'1.0.0.0',
				'securitytool'=>'1.0.0.1',
				'dependency'=>'1.0.1.0'
			)
		);
		$this->db=new sqlitedb();
		$this->sectool=new securitytool();
		// for dependencies
		$this->registerobject($this, $this->db);
		$this->registerobject($this, $this->sectool);
		// for register actions
		$this->registeractions();
		$this->checkdatabase(); // defined in initdatabase
	}

	protected function registeractions(){
		$this->registredActions=[
			'GET' => ['getversion', 'getmenuitems'],
			'POST' => [],
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
		$rootitems=$this->dbgetrootmenuitems();
		foreach($rootitems as &$rootitem){
			$rootitem['subItems']=$this->dbgetsubmenuitems($rootitem['id']);
		}
		$this->response(array('menuitems' => $rootitems));
	}

	function test(){
		echo 'test is works!';
	}


}
