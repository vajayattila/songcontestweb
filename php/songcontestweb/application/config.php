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
	'baseurl' => 'http://localhost:8000/',
	'sessionpath' => 'application/session',
	'sessiontimeout' => 3600,
	'sessiontype' => 'file'  // file	
);
	
/** @brief logger's settings*/
$config['logger']=array(
	'error' => true,
	'warning' => true,
	'info' => true,
	'system' => true,
	'requestinfo' => true,	
	'session' => true,
);

/** @brief routes*/
$config['routes']=array(
	'default' => 'defaultcontroller/index',
);		
		
/** @brief languages*/
$config['languages']=array(
	'default' => 'english',
);

/** @brief design*/
$config['design']=array(
	'css_template' => 'default' // default, dark, positive, negative
);
