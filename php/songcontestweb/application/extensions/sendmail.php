<?php

if (! defined ( 'mutyurphpmvc_inited' ))
exit ( 'No direct script access allowed' );

/**
*  @file sendmail.php
*  @brief Sendmail extension for MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
*	@author Vajay Attila (vajay.attila@gmail.com)
*  @copyright MIT License (MIT)
*  @date 2017.08.23
*  @version 1.0.0.1
*/

/** @brief sendmail class*/
class sendmail extends helper{
    
    public function __construct(){
        parent::__construct();
		sendmail::setup_dependencies(
			sendmail::get_class_name(), sendmail::get_version(), 'extension',
			array('helper'=>'1.0.0.1')
		);
    }

	public function get_class_name(){
		return 'sendmail';
	}
	
	public function get_version(){
		return '1.0.0.0';
    }    

    protected function send($from_mail, $from_name, $mail_to, $mail_subject, $mail_message){
        $encoding = "utf-8";
    
        // Preferences for Subject field
        $subject_preferences = array(
            "input-charset" => $encoding,
            "output-charset" => $encoding,
            "line-length" => 76,
            "line-break-chars" => "\r\n"
        );
    
        // Mail header
        $header = "Content-type: text/html; charset=".$encoding." \r\n";
        $header .= "From: ".$from_name." <".$from_mail."> \r\n";
        $header .= "MIME-Version: 1.0 \r\n";
        $header .= "Content-Transfer-Encoding: 8bit \r\n";
        $header .= "Date: ".date("r (T)")." \r\n";
        $header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);
    
        // Send mail
        mail($mail_to, $mail_subject, $mail_message, $header);
    }
    
    public function sendmail(
        $from, $fromname, $mailto, $subject, $templatename, $data, $language
    ){
        $this->log_message('sendmail', "sendmail.data=".print_r($data, true));
        $return=true;
        $language=strtolower($language);
        $filename=__dir__.'/sendmailtemplates/'.$language.'/'.strtolower($templatename).'.tmplt';
        $this->log_message('sendmail', "try to load ".$filename);
        if (file_exists ($filename)) {
            $this->log_message('sendmail', "is exists");
            $template=file_get_contents($filename);
            if($template!==FALSE){
                $this->log_message('sendmail', "The $filename template file is loaded.");
                $message=$this->applyDataOnTemplate($template, $data);
                // send mail
                // $this->log_message('sendmail', 'message='.$message);
                $this->send($from, $fromname, $mailto, $subject, $message);
            } else {
                $this->log_message('sendmail', "The $filename template file can not loaded.");
                $return=false;
            }
        } else {
            $this->log_message('sendmail', "is not exists");
            $return=false; 
        }
        return $return;
    }

    function applyDataOnTemplate($template, $data){
        $return=$template;
        if(is_array($data)){
            foreach($data as $key => $value){
                $return=str_replace('{{'.$key.'}}', $value, $return);
            }
        }
        return $return;
    }

}