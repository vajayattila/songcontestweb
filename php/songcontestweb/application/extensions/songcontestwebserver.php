<?php

require_once ('restserver.php');
require_once ('database.php');        
require_once ('securitytool.php');

class songcontestwebserver extends restserver{
	protected $db; 
    protected $sectool;
    protected $dbname='songcontestweb.db';

    function __construct(){
        parent::__construct(); 
		songcontestwebserver::setup_dependencies(
			songcontestwebserver::get_class_name(), songcontestwebserver::get_version(), 'extension',
			array(
                'restserver'=>'1.0.0.0',
                'sqlitedb'=>'1.0.0.0',
                'securitytool'=>'1.0.0.1',
                'dependency'=>'1.0.1.0'
            )
        );
		$this->db=new sqlitedb();
        $this->sectool=new securitytool();
        // for dependencies
        $this->registerobject($this, $this->db);
        $this->registerobject($this, $this->sectool);        
        // for register actions
        $this->registeractions();
        $this->checkdatabase();
    }

    protected function registeractions(){
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
        return '1.0.0.1';
    }

    protected function getversion(){
        $db=&$this->db;
        $vers=array();
        $modules=array();
        $result=$db->query(
            'select * from version order by modulname COLLATE NOCASE, partname COLLATE NOCASE, name COLLATE NOCASE'
        );
        if($result!==false){
            foreach($result as $row){
                $modules[$row['modulname']]['parts'][]=array(
                            'partName' => $row['partname'],
                            'version' => $row['version']
                );
            }
            foreach($modules as $key=>$module){
                $p=&$vers['versions'][];
                $p=array(
                    'moduleName' => $key,
                    'parts' => array()
                ); 
                foreach($module['parts'] as $part){
                    $p['parts'][]=array(
                        'partName' => $part['partName'],
                        'version' => $part['version']
                    );
                }
            }
        }else{
            die($db->getlasterrormessage());
        }
        $this->response($vers);
    }

    function checkdatabase(){
        $db=&$this->db;
        $dbname=$this->dbname;

        if(!$db->databaseisexist($dbname)){ // database is not exist
            $db->open($dbname);        
            $this->createdbstructure($db);
            $this->initdbdata($db);
        }else{
            $db->open($dbname);                    
        }
    }

    function createdbstructure(&$db){
        $ret=$db->createtableifnotexists('version', // version table
            $db->set(
                ', ',
                $db->field('id', 'INTEGER', $db->set(' ', 
                    $db->primary('PK_TEST', 'ASC', '' , 'AUTOINCREMENT'), $db->notnull(), $db->unique())
                ),
                $db->field('uuid', 'TEXT', $db->set(' ',  $db->notnull(), $db->unique())),
                $db->field('name', 'TEXT', $db->set(' ', $db->notnull(), $db->unique())),
                $db->field('modulname', 'TEXT', $db->notnull()),                
                $db->field('partname', 'TEXT', $db->notnull()),                                
                $db->field('version', 'TEXT', $db->notnull()),
                $db->field('tstamp', 'TIMESTAMP', $db->notnull())
            )
        );
        if($ret===true){
            $ret=$db->createtableifnotexists('genre', // version table
                $db->set(
                    ', ',
                    $db->field('id', 'INTEGER', $db->set(' ', 
                        $db->primary('PK_TEST', 'ASC', '' , 'AUTOINCREMENT'), $db->notnull(), $db->unique())
                    ),
                    $db->field('uuid', 'TEXT', $db->set(' ',  $db->notnull(), $db->unique())),
                    $db->field('name', 'TEXT', $db->set(' ', $db->notnull(), $db->unique())),
                    $db->field('image', 'BLOB', '')
                )
            );
        }    
        if($ret!==true){
            die($db->getlasterrormessage());
        }
    }

    function initdbdata(&$db){
        $st=&$this->sectool;
        $db=&$this->db;        
        $ret=$this->insertversion(
            $st->getuuid(), $db->get_class_name(), $db->get_version(), 'MutyurPHPMVC', 'Sqlite DB Handler'
        );
        if($ret===true){
            $ret=$this->insertversion(
                $st->getuuid(), $this->get_class_name(), $this->get_version(), 'SongContestWeb', 'Server'
            );        
        }
        if($ret===true){
            $ret=$this->insertversion(
                $st->getuuid(), restserver::get_class_name(), restserver::get_version(), 
                'MutyurPHPMVC', 'RestFul Server'
            );        
        }
        if($ret===true){
            $ret=$this->insertversion(
                $st->getuuid(), 'dbhelper', $this->get_version_by_class_name('dbhelper'), 
                'MutyurPHPMVC', 'DB helper'
            );        
        }
        if($ret===true){
            $ret=$this->insertversion(
                $st->getuuid(), 'securitytool', $this->get_version_by_class_name('securitytool'), 
                'MutyurPHPMVC', 'Security extension'
            );        
        }

        if($ret!==true){
            die($db->getlasterrormessage());
        }
    }

    function insertversion($uuid, $name, $version, $modulname, $partname){
        $timestamp = date('Y-m-d H:i:s');
        $db=&$this->db;                
        $ret=$db->exec("insert into version (
            uuid, name, version, tstamp, modulname, partname
        ) values (
            '$uuid', '$name', '$version', '$timestamp', '$modulname', '$partname'
        )");
        return $ret;
    }

    function test(){
        echo 'test is works!';
    }
}