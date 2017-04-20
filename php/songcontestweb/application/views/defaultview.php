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

function add_tx($template, $data, $type){
	$retval='<div class="'.$template.'_table_'.$type.'">';
	$retval.=$data;
	$retval.='</div>';
	return $retval;	
} 	

function registred_classes($dependencies, $langpar, $template){
	$retval='<div class="'.$template.'_classtable">';
	$retval.='<div class="'.$template.'_table_caption">';
	$retval.=$langpar->get_item('registred_classes');
	$retval.='</div>';
	$retval.='<div class="'.$template.'_table_header">';
	$retval.=add_tx($template, $langpar->get_item('modul'), 'th');
	$retval.=add_tx($template, $langpar->get_item('installed'), 'th');
	$retval.=add_tx($template, $langpar->get_item('expected'), 'th');
	$retval.='</div>';
	foreach($dependencies['registred_classes'] as $key => $value){
		$retval.='<div class="'.$template.'_table_item">';
		$value2=get_expected($dependencies, $key);
		$retval.=add_tx($template, $key, 'td1');
		$retval.=add_tx($template, $value, 'td2');
		$type='';
		if($value2=='---' || $value2<=$value){
			$type='td3';
		} else {
			$type='td4';
		}
		$retval.=add_tx($template, $value2, $type);
		$retval.='</div>';
	}
	$retval.='</div>';
	return $retval; 
}

function add_info_line($template, $key, $value){
	$retval='<div class="'.$template.'_infodatasline">';
	$retval.='<span class="'.$template.'_infobox_label">'.$key.'</span>';
	$retval.='<span class="'.$template.'_infobox_text">'.$value.'</span>';
  	$retval.='</div>';
  	return $retval;
}

$template=$data['template'];
echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<link rel="stylesheet" type="text/css" href="application/css/default.css">';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >';
echo '</head>';
echo '<body class="'.$template.'_body">';
echo '<div class="content">';
echo '<div class="'.$template.'_infoblokk">';
echo '<div class="'.$template.'_header">';
echo '<h1>'.$lang->get_item('caption').'</h1>';
echo '</div>';
echo '<div class="'.$template.'_infodatas">';
echo add_info_line($data['template'], $lang->get_item('controller'), $this->get_class_name());
echo add_info_line($data['template'], $lang->get_item('model'), $model->get_class_name());
echo add_info_line($data['template'], $lang->get_item('message_label'), $data['message']);
echo add_info_line($data['template'], $lang->get_item('request_uri'), $data['request_uri']);
echo '</div>';
echo '</div>';
echo '<div class="'.$template.'_class_list_block">';
echo '<div class="'.$template.'_class_list">';
echo registred_classes($data['dependencies'], $lang, $data['template']);
echo '</div>';
echo '<div class="'.$template.'_footer">';
$anchor=$lang->get_item('footer_anchor');
echo '<div>';
echo '<h6>';
echo '<span class="hideable">'.$lang->get_item('footer_label').':<a href="'.$anchor.'" target="_blank">'.$anchor.'</a>'.'</span>';
echo '<span>'.$lang->get_item('change_language').':';
if($lang->get_language()=='english'){
	echo '<a href="'.$data['baseurl'].'index.php?lang=hun">Magyar</a>';
} else {
	echo '<a href="'.$data['baseurl'].'index.php?lang=eng">English</a>';
}
echo '</span>';
echo '<span class="hideable hideable_template">'.$lang->get_item('set_template').':';
if($template=='default'){
	echo '<a href="'.$data['baseurl'].'index.php?template=dark">Dark</a>';
	echo '<a href="'.$data['baseurl'].'index.php?template=positive">Positive</a>';
	echo '<a href="'.$data['baseurl'].'index.php?template=negative">Negative</a>';
} else if($template=='dark'){
	echo '<a href="'.$data['baseurl'].'index.php?template=default">Default</a>';
	echo '<a href="'.$data['baseurl'].'index.php?template=positive">Positive</a>';
	echo '<a href="'.$data['baseurl'].'index.php?template=negative">Negative</a>';
} else if($template=='positive'){
	echo '<a href="'.$data['baseurl'].'index.php?template=default">Default</a>';
	echo '<a href="'.$data['baseurl'].'index.php?template=dark">Dark</a>';
	echo '<a href="'.$data['baseurl'].'index.php?template=negative">Negative</a>';
} else if($template=='negative'){
	echo '<a href="'.$data['baseurl'].'index.php?template=default">Default</a>';
	echo '<a href="'.$data['baseurl'].'index.php?template=dark">Dark</a>';
	echo '<a href="'.$data['baseurl'].'index.php?template=positive">Positive</a>';
}
echo '</span>';
echo '</h6>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

echo '</body>';
echo '</html>';