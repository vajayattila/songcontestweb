<?php

class defaultcontroller extends workframe{
	
	public function __construct(){
		$this->setup_dependencies(
				$this->get_class_name(), '1.0.0.0',
			array(
				'workframe'=>'1.0.0.2'
			)
		);
	}
	
	public function get_class_name() {
		return 'defaultcontroller';
	}
	
	public function index(){
		$model=$this->load_model('defaultmodel');
		$this->get_request_uri();
		$data=array(
				'baseurl' => $model->get_base_url(),
				'message' => $model->get_message(),
				'request_uri' => $this->get_request_uri(),
				'dependencies' => $this->get_array_of_dependencies()
		);
		
		$this->load_view('defaultview', $data);		
	}
	
}