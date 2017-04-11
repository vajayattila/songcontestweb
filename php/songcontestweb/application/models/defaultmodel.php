<?php

class defaultmodel extends workframe{
	
	public function __construct(){
		$this->setup_dependencies(
			$this->get_class_name(), '1.0.0.0',
			array(
				'workframe'=>'1.0.0.2'
			)
		);
	}
	
	public function get_class_name() {
		return 'defaultmodel';
	}
	
	public function get_message(){
		return 'Hello World!';
	}
	
}