<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file securitytool.php
 *  @brief Security extension for MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.04.26-2017.04.27
 *  @version 1.0.0.1
 */


/** @brief security helper class*/
class securitytool extends helper{
	protected $m_cipher;
	protected $m_mode;
	protected $m_encryptername=false;
	private $m_nonce;
	
	public function __construct(){
		parent::__construct();
		securitytool::setup_dependencies(
			securitytool::get_class_name(), securitytool::get_version(), 'extension',
			array('helper'=>'1.0.0.1')
		);
		// Loads a PHP extensions at runtime
		$phpversion=phpversion();
		if('7.1.0'<=$phpversion){ // 7.1.0<=PHP or 
			if($this->extension_loaded('libsodium')){ // if libsodium extension is exists
				$this->m_encryptername='libsodium';
			}
		} else {
			if($this->extension_loaded('libsodium', false)){ // if libsodium extension is exists
				$this->m_encryptername='libsodium';
			} else {
				if(!function_exists('mcrypt_encrypt')) {
					if($this->extension_loaded('mcrypt')){ // PHP<7.0.0 try to load mcrypt extension
						$this->m_encryptername='mcrypt';
					} 
				}else{
					$this->m_encryptername='mcrypt';
				}
			}
		}			
		$this->extension_loaded('zlib');
		$this->m_nonce=$this->get_config_value('system', 'sessionencryptionnonce');
	}
	
	public function get_class_name(){
		return 'securitytool';
	}
	
	public function get_version(){
		return '1.0.0.1';
	}
	
	/*
	 * @brief Generate unique ID
	 */
	function getuuid(){
		return $this->md5touuid(md5(uniqid(rand().uniqid(microtime(),true), true)));
	}
	
	/*
	 * @brief Format md5 to uuid
	 */
	function md5touuid($md5s) {
		$md5s =
		substr ( $md5s, 0, 8 ) . '-' .
		substr ( $md5s, 8, 4 ) . '-' .
		substr ( $md5s, 12, 4 ) . '-' .
		substr ( $md5s, 16, 4 ) . '-' .
		substr ( $md5s, 20 );
		return $md5s;
	}
	
	/*
	 * @brief Generate random string
	 */
	function generateRandomString($length = 16) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	/*
	 * @brief Generate enryption key
	 */
	function generateecriptionkey(){
		$key=md5($this->generateRandomString(16));
		return $key;
	}
	
	/*
	 * @brief Add/remove xor noise
	 */
	protected function xornoise($str, $key) {
		$strlen=strlen($str);
		$keylen=strlen($key);
		$retval = '';
		for($i = 0, $j=0; $i < $strlen; $i ++, $j++) {
			if($keylen<=$j){
				$j=0;
			}
			$retval.=chr(ord($str[$i]) ^ ord($key[$j]));
		}
		return $retval;
	}
	
	/*
	 * @brief Encode, compress and returns a base64 string
	 */
	function encodestring($str, $key) {
		$retval=false;
		if($this->m_encryptername=='mcrypt'){
			$init_size = mcrypt_get_iv_size ( $this->m_cipher, $this->m_mode );
			$init_vect = mcrypt_create_iv ( $init_size, MCRYPT_RAND );
			$mcrypt=mcrypt_encrypt ( $this->m_cipher, $key, gzcompress ( $str, 9 ), $this->m_mode, $init_vect );
			$retval=base64_encode ( $this->xornoise ( $mcrypt, $key ) );
		} else { // libsodium
			$encrypted = \Sodium\crypto_secretbox(gzcompress ( $str, 9 ), $this->m_nonce, $key); 
			$retval=base64_encode ( $this->xornoise ( $encrypted, $key ) );
		}
		return $retval;
	}
	
	/*
	 * @brief Decode and uncompress a string
	 */
	function decodestring($str, $key) {
		$retval = false;
		if($this->m_encryptername=='mcrypt'){
			if (strlen ( $str ) != 0) {
				$noised = base64_decode ( $str );
				$encoded = $this->xornoise ( $noised, $key );
				$init_size = mcrypt_get_iv_size ( $this->m_cipher, $this->m_mode );
				if ($init_size <= strlen ( $encoded )) {
					$init_vect = substr ( $encoded, 0, $init_size );
					$decoded = mcrypt_decrypt ( $this->m_cipher, $key, $encoded, $this->m_mode, $init_vect );
				}
				$retval=gzuncompress ( $decoded );
			}
		} else { // libsodium
			if (strlen ( $str ) != 0) {
				$noised = base64_decode ( $str );
				$encoded = $this->xornoise ( $noised, $key );
				$retval= gzuncompress ( \Sodium\crypto_secretbox_open($encoded, $this->m_nonce, $key));
			}				
		}
		return $retval;
	}
	
}
