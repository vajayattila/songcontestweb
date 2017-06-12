<?php

require_once ('restserver.php');

class songcontestwebserver extends restserver{

    function __construct(){
        $this->registredActions=[
            'GET' => ['getversion'],
            'POST' => [],
            'PUT' => [],
            'DELETE' => []
        ];
		restserver::setup_dependencies(
			restserver::get_class_name(), restserver::get_version(), 'extension',
			array()
        );
		songcontestwebserver::setup_dependencies(
			songcontestwebserver::get_class_name(), songcontestwebserver::get_version(), 'extension',
			array('restserver'=>'1.0.0.0')
        );
    }

	public function get_class_name(){
		return 'songcontestwebserver';
	}

    public function get_version(){
        return '1.0.0.0';
    }

    protected function getversion(){
        $vers=array(
            'versions' => array(
                array(
                    'moduleName' => 'SongCatalog',
                    'parts' => array( 
                        array(
                            'partName' => 'Server',
                            'version' => songcontestwebserver::get_version()
                        )
                    )
                ),
                array(
                    'moduleName' => 'MutyurPHPMVC',                    
                    'parts' => array( 
                        array(
                            'partName' => 'Restful server',                    
                            'version' => restserver::get_version()
                        )
                    )
                )
            )
        );
        $this->response($vers);
    }
}

/*$rs=new SongCatalog();
$rs->execute();*/