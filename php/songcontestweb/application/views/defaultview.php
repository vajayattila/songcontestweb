<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file workframe.php
 *  @brief Demonstrate views using in MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.11
 *  @version 1.0.0.0
 */

$lang=$data['lang'];

function get_expected($dependencies, $keypar){
	$retval='';
	foreach($dependencies['dependencies'] as $key => $value){
		foreach($value as $key2 => $value2){
			if($key2==$keypar && $retval<$value2){
				$retval=$value2;
			}
		}
	}
	if($retval==''){
		$retval='---';
	}
	return $retval;
}

function registred_classes($dependencies, $langpar){
	$retval='<table>';
	$retval.='<caption>'.$langpar->get_item('registred_classes').'</caption>';
	$retval.='<tr>';
	$retval.='<th>'.$langpar->get_item('modul').'</th>';
	$retval.='<th>'.$langpar->get_item('installed').'</th>';
	$retval.='<th>'.$langpar->get_item('expected').'</th>';
	$retval.='</tr>';
	foreach($dependencies['registred_classes'] as $key => $value){
		$retval.='<tr>';
		$retval.='<td>'.$key.'</td>';
		$retval.='<td>'.$value.'</td>';
		$retval.='<td>'.get_expected($dependencies, $key).'</td>';
		$retval.='</tr>';
	}
	$retval.='</table>';
	return $retval; 
}

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >';
echo '</head>';
echo '<body>';

echo '<div id="header">';
echo '<h1>'.$lang->get_item('caption').'</h1>';
echo '</div>';

echo '<div id="content">';
echo '<ul>'.$lang->get_item('controller').'<b>'.$this->get_class_name().'</b></ul>';
echo '<ul>'.$lang->get_item('model').'<b>'.$model->get_class_name().'</b></ul>';
echo '<ul>'.$lang->get_item('message_label').'<b>'.$data['message'].'</b></ul>';
echo '<ul>'.$lang->get_item('request_uri').'<b>'.$data['request_uri'].'</b></ul>';
echo '<ul>'.registred_classes($data['dependencies'], $lang).'</ul>';
echo '</div>';

// print_r($data);

echo '<div id="footer">';
echo '<h6>'.$lang->get_item('footer').' - ';
if($lang->get_language()=='english'){
	echo '<a href="'.$data['baseurl'].'index.php?lang=hun">Magyar</a>';
} else {
	echo '<a href="'.$data['baseurl'].'index.php?lang=eng">English</a>';
}
echo '</h6>';
echo '</div>';

echo '</body>';
echo '</html>';