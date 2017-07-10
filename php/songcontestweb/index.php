<?php
/**
 *  @file index.php
 *  @brief Index file for MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.07
 *  @version 1.0.0.0
 */

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', E_ALL);


function APPPATH(){
    return __dir__.'/'; 
}

function WITHOUT_APPPATH($url){
    $prefix = APPPATH();
    if (substr($url, 0, strlen($prefix)) == $prefix) {
        $url = substr($url, strlen($prefix));
    } 
    return $url;
}


function CondDefine($defname, $value)
{
    if(!defined($defname))
    {
        define($defname, $value);
    }
}

require_once('application/core/workframe.php'); 
