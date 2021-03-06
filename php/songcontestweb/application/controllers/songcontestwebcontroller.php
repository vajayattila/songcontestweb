<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file songcontestwebcontroller.php
 *  @brief Song Contest Web RestFul server. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.06.12
 *  @version 1.0.0.0
 */

require_once ('defaultcontroller.php');

class songcontestwebcontroller extends defaultcontroller{
	protected $restserver;

	 function __construct(){
		parent::__construct();		 
		songcontestwebcontroller::setup_dependencies(
			songcontestwebcontroller::get_class_name(), songcontestwebcontroller::get_version(), 'controller',	
			array(
				'defaultcontroller' => '1.0.0.1',
				'songcontestwebserver'=>'1.0.0.0'
			)
		);
		$this->restserver=$this->load_extension('songcontestwebserver');								
	}

    public function get_version(){
        return '1.0.0.0';
    }

	public function index($caption=''){
		parent::index('SongContestWeb RestFul server');
	}
	
	public function get_class_name() {
		return 'songcontestwebcontroller';
	}
	
	public function service(){
		$this->restserver->execute();
	}

	public function test(){
		$this->restserver->test();
	}		
	
}