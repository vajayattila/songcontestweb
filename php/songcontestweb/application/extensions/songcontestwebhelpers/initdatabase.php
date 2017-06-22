<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' ); 

/**
 *  @file createdatabase.php
 *  @brief Database creation helper class for songcontestweb. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.06.15-2017.06.15
 *  @version 1.0.0.0
 */

trait initdatabase{
    protected $db;
    protected $sectool;

    function checkdatabase(){
        $db=&$this->db;
        $dbname=$this->dbname;

        if(!$db->databaseisexist($dbname)){ // database is not exist
            $db->open($dbname);        
            $this->createdbstructure();
            $this->initdbdata();
        }else{
            $db->open($dbname);                    
        }
    }

    public function createdbstructure(){
        $db=&$this->db;        
        $ret=$db->createtableifnotexists('version', // version table
            $db->set(
                ', ',
                $db->field('id', 'INTEGER', $db->set(' ', 
                    $db->primary('PK_TEST', 'ASC', '' , 'AUTOINCREMENT'), $db->notnull(), $db->unique())
                ),
                $db->field('uuid', 'TEXT', $db->set(' ',  $db->notnull(), $db->unique())),
                $db->field('name', 'TEXT', $db->set(' ', $db->notnull(), $db->unique(), $db->collate('NOCASE'))),
                $db->field('modulname', 'TEXT', $db->set($db->notnull(), $db->collate('NOCASE'))),                
                $db->field('partname', 'TEXT', $db->set($db->notnull(), $db->collate('NOCASE'))),                                
                $db->field('version', 'TEXT', $db->notnull()),
                $db->field('tstamp', 'TIMESTAMP', $db->notnull())
            )
        );
        if($ret===true){
            $ret=$db->createtableifnotexists('menu', // menu
                $db->set(
                    ', ',
                    $db->field('id', 'INTEGER', $db->set(' ', 
                        $db->primary('PK_TEST', 'ASC', '' , 'AUTOINCREMENT'), $db->notnull(), $db->unique())
                    ),
                    $db->field('uuid', 'TEXT', $db->set(' ',  $db->notnull(), $db->unique())),
                    $db->field('caption', 'TEXT', $db->set(
                        ' ', $db->notnull(), $db->unique(), $db->collate('NOCASE'))
                    ),
                    $db->field('route', 'TEXT', $db->set(' ', $db->notnull(), $db->collate('NOCASE'))),
                    $db->field('showifauthenticated', 'BOOL', $db->notnull()),
                    $db->field('showdefault', 'BOOL', $db->notnull()),
                    $db->field('parent', 'TEXT', '')
                )
            );
        }    
        if($ret===true){
            $ret=$db->createtableifnotexists('genre', // genre
                $db->set(
                    ', ',
                    $db->field('id', 'INTEGER', $db->set(' ', 
                        $db->primary('PK_TEST', 'ASC', '' , 'AUTOINCREMENT'), $db->notnull(), $db->unique())
                    ),
                    $db->field('uuid', 'TEXT', $db->set(' ',  $db->notnull(), $db->unique())),
                    $db->field('name', 'TEXT', $db->set(' ', $db->notnull(), $db->unique(), $db->collate('NOCASE'))),
                    $db->field('image', 'BLOB', '')
                )
            );
        }    
        if($ret!==true){
            die($db->getlasterrormessage());
        }
    }

    public function initdbdata(){
        $st=&$this->sectool;
        $db=&$this->db;        
        // versions
        $ret=$this->insertversion( // defined in dbmethods trait
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
        // menus
        if($ret===true){ 
            $ret=$this->insertmenu($st->getuuid(), 'Home', 'home', true, true); // defined in dbmethods trait
        }
        if($ret===true){ 
            $ret=$this->insertmenu($st->getuuid(), 'Profile', 'profile', true, false);
        }
        if($ret===true){
            $uuid=$st->getuuid();                    
            $ret=$this->insertmenu($uuid, 'Login/Registration', 'loginmain', false, true);
            $ret=$this->insertmenu($st->getuuid(), 'Login', 'login', false, true, $uuid);            
            $ret=$this->insertmenu($st->getuuid(), 'Registration', 'registration',  false, true, $uuid);
            $ret=$this->insertmenu($st->getuuid(), 'Activation', 'activation',  false, true, $uuid);            
        }
        if($ret===true){
            $uuid=$st->getuuid();                    
            $ret=$this->insertmenu($uuid, 'Base data', 'basedatamain', true, true);
            $ret=$this->insertmenu($st->getuuid(), 'Genres', 'genres', true, true, $uuid);            
            $ret=$this->insertmenu($st->getuuid(), 'Regions', 'regions', true, true, $uuid);                        
        }
        if($ret===true){
            $ret=$this->insertmenu($st->getuuid(), 'About', 'about', true, true);
        }
        if($ret===true){ 
            $ret=$this->insertmenu($st->getuuid(), 'Logout', 'logout', true, false);
        }

        if($ret!==true){
            die($db->getlasterrormessage());
        }
    }

}
