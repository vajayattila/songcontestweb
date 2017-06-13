<?php

require_once ('restserver.php');
require_once ('database.php');        
require_once ('securitytool.php');

class songcontestwebserver extends restserver{
	protected $db; 
    protected $sectool;

    function __construct(){
        parent::__construct(); 
		songcontestwebserver::setup_dependencies(
			songcontestwebserver::get_class_name(), songcontestwebserver::get_version(), 'extension',
			array(
                'restserver'=>'1.0.0.0',
                'sqlitedb'=>'1.0.0.0',
                'securitytool'=>'1.0.0.1',
            )
        );
		$this->db=new sqlitedb();
        $this->sectool=new securitytool();
        // for dependencies
        $this->registerobject($this, $this->db);
        $this->registerobject($this, $this->sectool);        
        $this->registredActions=[
            'GET' => ['getversion'],
            'POST' => [],
            'PUT' => [],
            'DELETE' => []
        ];
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

    function test(){
        $db=&$this->db;
        $st=&$this->sectool;

        $db->open('test.db');
        $ret=$db->createtableifnotexists('version',
            $db->set(
                ', '    ,
                $db->field('id', 'INTEGER', $db->set(' ', 
                    $db->primary('PK_TEST', 'ASC', '' , 'AUTOINCREMENT'), $db->notnull(), $db->unique())
                ),
                $db->field('uuid', 'TEXT', $db->set(' ',  $db->notnull(), $db->unique())),
                $db->field('name', 'TEXT', $db->set(' ', $db->notnull(), $db->unique())),
                $db->field('version', 'TEXT', $db->notnull()),
                $db->field('tstamp', 'TIMESTAMP', $db->notnull())
            )
        );
        $uuid=$this->sectool->getuuid();
        $name=$db->get_class_name();
        $ver=$db->get_version();
        $timestamp = date('Y-m-d H:i:s');
        $ret=$db->exec("insert into version (
            uuid, name, version, tstamp
        ) values (
            '$uuid', '$name', '$ver', '$timestamp'
        )");

        $uuid=$this->sectool->getuuid();        
        $name=$this->get_class_name();
        $ver=$this->get_version();
        $timestamp = date('Y-m-d H:i:s');
        $ret=$db->exec("insert into version (
            uuid, name, version, tstamp
        ) values (
            '$uuid', '$name', '$ver', '$timestamp'
        )");
        
        if($ret!==true){
            echo $db->getlasterrormessage();
        }else{
            echo 'success';
        }
        $db->close();        
    }
}