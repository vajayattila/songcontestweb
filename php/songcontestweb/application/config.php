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
		'baseurl' => 'http://localhost:8000/'
	);
	
/** @brief logger's settings*/
$config['logger']=array(
	'error' => true,
	'warning' => true,
	'info' => true,
	'system' => true,
	'requestinfo' => true	
);

/** @brief routes*/
$config['routes']=array(
	'default' => 'defaultcontroller/index',
);		
		