<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' ); 

/**
 *  @file dbmethods.php
 *  @brief Database manipulation and query helper class for songcontestweb. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.06.15-2017.06.15
 *  @version 1.0.0.0
 */

require_once ('status.php');

trait dbmethods{

    protected function setStatus(&$arr, $statuscode){
        $arr['statuscode']=$statuscode;
        if($statuscode==STATUS_SQL_ERROR){
            $arr['status']=$this->get_item(STATUS[$statuscode]).': '.$db->getlasterrormessage();  
        }else{
            $arr['status']=$this->get_item(STATUS[$statuscode]);
        }        
    }

    protected function getIsExistsStatus($arr, $isexiststatus){
        $statuscode=$arr['statuscode'];
        if($statuscode===STATUS_OK){
            if($arr['isexist']){
                $statuscode=$isexiststatus;    
            }  
        }      
        return $statuscode;   
    }

    /** @brief Get versions of modules
      */
    protected function dbgetversion(){
        $statuscode=STATUS_OK;
        $db=&$this->db;
        $vers=array();
        $modules=array();
        $result=$db->query(
            'select * from version order by modulname, partname, name'
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
            $statuscode=STATUS_SQL_ERROR;
        }
        $this->setStatus($vers, $statuscode);
        return $vers;
    }

    /** @brief Get root menu items
      */
    protected function dbgetrootmenuitems(){
        $statuscode=STATUS_OK;
        $db=&$this->db;
        $menuitems['menuitems']=array();
        $result=$db->query(
            'select * from menu where parent is NULL'
        );
        if($result!==false){
            foreach($result as $row){
                $menuitem=&$menuitems['menuitems'][];
                $uuid=$row['uuid'];
                $menuitem=array(
                    'id' => $uuid,
                    'caption' => $row['caption'],
                    'route' => $row['route'],
                    'showifauthenticated' => $db->sqlToBOOL($row['showifauthenticated']),
                    'showdefault' => $db->sqlToBOOL($row['showdefault']),
                    'subItems' => array()
                );
            }
        } else {
            $statuscode=STATUS_SQL_ERROR;           
        }
        $this->setStatus($menuitems, $statuscode);
        return $menuitems;
    }    

    /** @brief Get sub menu items
      */
    protected function dbgetsubmenuitems($rootitemuuid){
        $statuscode=STATUS_OK;
        $db=&$this->db;        
        $return=array();                
        $subitems=$db->query(
            "select * from menu where parent is '$rootitemuuid'"
        );
        if($subitems!==false){            
            $return['subitems']=array();    
            foreach($subitems as $sirow){
                $return['subitems'][]=array(
                    'id' => $sirow['uuid'],
                    'caption' => $sirow['caption'],
                    'route' => $sirow['route'],
                    'showifauthenticated' => $db->sqlToBOOL($sirow['showifauthenticated']),
                    'showdefault' => $db->sqlToBOOL($sirow['showdefault'])
                );
            }
        } else {
            $statuscode=STATUS_SQL_ERROR;          
        }
        $this->setStatus($return, $statuscode);
        return $return;
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

    function insertmenu(
        $uuid, $caption, $route, $showifauthenticated=false, $showdefault=true, $parent=null
    ){
        $db=&$this->db;        
        $showifauthenticated=$db->boolToSql($showifauthenticated);
        $showdefault=$db->boolToSql($showdefault);
        $parent=$parent===null?$parent='NULL':"'$parent'";
        $db=&$this->db;                
        $ret=$db->exec("insert into menu (
            uuid, caption, route, showifauthenticated, showdefault, parent
        ) values (
            '$uuid', '$caption', '$route', $showifauthenticated, $showdefault, $parent
        )");
        return $ret;
    }
 
    function fieldvalueisalreadyexists($table, $fieldname, $fieldvalue){
        $statuscode=STATUS_OK;
        $return=array(
            'isexist' => false    
        ); 
        $db=&$this->db; 
        $fieldvalue=$db->escapeString($fieldvalue);
        $result=$db->query(
            "select * from $table where $fieldname is '$fieldvalue'"
        );
        if($result!==false){
            if(0<count($result)){
                $return['isexist'] = true;   
            }
        }else{
            $statuscode=STATUS_SQL_ERROR;  
        }
        $this->setStatus($return, $statuscode);
        return $return;
    }

    function dbusernameisalreadyexists($username){
        return $this->fieldvalueisalreadyexists('user', 'name', $username);
    }

    function dbemailisalreadyexists($email){
        return $this->fieldvalueisalreadyexists('user', 'email', $email);
    }

    function dbregistration($username, $email, $password, $action){
        $statuscode=STATUS_OK;
        $db=&$this->db;        
        $return=array(); 
        $username=$db->escapeString($username);
        $email=$db->escapeString($email);
        $password=$db->escapeString($password);
        $action=$db->escapeString($action);
        // check action name
        if($action!=='registration'){
            $statuscode=STATUS_INVALID_ACTION;
        } 
        if($statuscode===STATUS_OK){
            $statuscode=$this->getIsExistsStatus(
                $this->dbusernameisalreadyexists($username),
                STATUS_USERNAME_IS_ALREADY_EXISTS
            );
        }       
        if($statuscode===STATUS_OK){
            $statuscode=$this->getIsExistsStatus(
                $this->dbemailisalreadyexists($email),
                STATUS_EMAIL_IS_ALREADY_EXISTS
            );           
        }
        if($statuscode===STATUS_OK){
            $uuid=$this->sectool->getuuid();
            $pass=md5($password);
            $verifycode=$this->sectool->getuuid();
            $verified=$db->boolToSql(false);
            $ret=$db->exec("insert into user (
                uuid, name, password, email, verifycode, verified
            ) values (
                '$uuid', '$username', '$pass', '$email', '$verifycode', $verified
            )");
            if($ret!==true){
                $statuscode=STATUS_SQL_ERROR;
            }
            $return["id"]=$uuid;
        }
        $this->setStatus($return, $statuscode);
        return $return;
    }

    function dbgetuserbyemail($email){
        $statuscode=STATUS_OK;
        $db=&$this->db;
        $return=array();     
        $email=$db->escapeString($email);
        $result=$db->query(
            "select 
                uuid, name, email, verifycode, verified 
            from 
                user 
            where upper(email) = upper('$email')"
        );
        if($result!==false) {
            if(0<count($result)) {
                $return['user'] = $result;  
            } else {
                $statuscode=STATUS_USER_NOT_FOUND;
            }
        } else {;  
            $statuscode=STATUS_SQL_ERROR; 
        }        

        $this->setStatus($return, $statuscode);
        return $return;
    }
     
}

