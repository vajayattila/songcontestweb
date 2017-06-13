<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file restserver.php
 *  @brief RestFul server base class for MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.06.12
 *  @version 1.0.0.0
 */

class restserver extends helper{
    protected $method;
    protected $action;
    protected $registredActions=[];
    protected $requestArgs=[];
    protected $statusCode;
    protected $contentType;

    function __construct(){
        $this->statusCode=200;
        $this->contentType="application/json";
        $this->registredActions=[
            'GET' => [],
            'POST' => [],
            'PUT' => [],
            'DELETE' => []
        ];
		// Dependency
		restserver::setup_dependencies(
			restserver::get_class_name(), restserver::get_version(), 'extension',
            array('helper'=>'1.0.0.1')
        );
    }

	public function get_class_name(){
		return 'restserver';
	}

    public function get_version(){
        return '1.0.0.0';
    }

    protected function getAction($method){
        $act=false;
        switch(strtoupper($method)){
            case 'GET':
                $this->requestArgs = $this->cleanInputs($_GET);
                break;
            case 'POST':
                $this->requestArgs = $this->cleanInputs($_POST);
                break;
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents("php://input"), $this->requestArgs);
                $this->requestArgs = $this->cleanInputs($this->requestArgs);                
                break;
            default:
                $this->response('Method Not Allowed',405);
        }
        $temp=isset($this->requestArgs['action'])?$this->requestArgs['action']:NULL;
        if($temp!==NULL){
            if(in_array($temp, $this->registredActions[$method])){
                $act=$temp;
            } else {
                $this->response('Unknown action', 400);    
            }   
        } else {
            $this->response('The action parameter is not set', 400);    
        }
        return $act;        
    }

    protected function cleanInputs($data){
        $clean_input = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            if(get_magic_quotes_gpc()) {
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $clean_input = trim($data);
        }
        return $clean_input;
    }     

    protected function get_status_message(){
        $status = array(
        200 => 'OK', 
        204 => 'No Content',  
        400 => 'Bad request',
        404 => 'Not Found',  
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        500 => 'Internal Server Error');
        $code=isset($status[$this->statusCode])?$this->statusCode:500;
        return $status[$code];
    }

    protected function set_headers() {
        header("HTTP/1.1 ".$this->statusCode." ".$this->get_status_message());
        header("Content-Type:".$this->contentType);
    }

    protected function response($data, $status = 200) {
       $this->statusCode = $status;
       $this->set_headers();
       if (is_array($data)){
          echo json_encode($data, JSON_PRETTY_PRINT);
       }
       else{
          echo $status!=200?$data.' ('.$status.')':$data;
       }
       exit;
    }     

    public function execute(){
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->action = $this->getAction($this->method);
        $this->{$this->action}();    
    }

}

