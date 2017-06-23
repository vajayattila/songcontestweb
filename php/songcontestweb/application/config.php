<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file config.php
 *  @brief Configuration file for SongContestWeb. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.09
 *  @version 1.0.0.0
 */

/** @brief system's settings*/
$config['system']=array(
	'baseurl' => 'http://127.0.0.1:8001/',
// sesshandler extension		
	'sessionpath' => 'application/session',
	'sessiontimeout' => 3600,
	'sessiontype' => 'file',  // file
	'sessionisencrypted' => true,
	'sessionencryptionkey' => 'aklZhj354n56dFfvoodmnbx676743mmh', // key's length is 32	
	'sessionencryptionnonce' => 'jjdkjd883nndbbvgsf3sd8js', // nonce's length is 24 for libsodium extension
);
	
/** @brief logger's settings*/
$config['logger']=array(
	'error' => true,
	'warning' => true,
	'info' => true,
	'system' => true,
	'requestinfo' => true, 
	'session' => true, // for sesshandler extension
);

/** @brief routes*/
$config['routes']=array(
//	'default' => 'defaultcontroller/index',
	'default' => 'songcontestwebcontroller/index',
	'service' => 'songcontestwebcontroller/service',
	'test' => 'songcontestwebcontroller/test'			
);		

/** @brief languages*/
$config['languages']=array( // for languagehandler extension
	'default' => 'english', 
);

/** @brief design*/
$config['design']=array(
	'css_template' => 'default' // default, dark, positive, negative
);
