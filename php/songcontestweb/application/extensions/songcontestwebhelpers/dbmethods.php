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

trait dbmethods{

    /** @brief Get versions of modules
      */
    protected function dbgetversion(){
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
            die($db->getlasterrormessage());
        }
        return $vers;
    }

    /** @brief Get root menu items
      */
    protected function dbgetrootmenuitems(){
        $db=&$this->db;
        $menuitems=array();
        $result=$db->query(
            'select * from menu where parent is NULL'
        );
        if($result!==false){
            foreach($result as $row){
                $menuitem=&$menuitems[];
                $uuid=$row['uuid'];
                $menuitem=array(
                    'id' => $uuid,
                    'caption' => $row['caption'],
                    'route' => $row['route'],
                    'verified' => $row['verified']===1?true:false,
                    'subItems' => array()
                );
            }
        } else {
            die($db->getlasterrormessage());            
        }
        return $menuitems;
    }    

    /** @brief Get sub menu items
      */
    protected function dbgetsubmenuitems($rootitemuuid){
        $db=&$this->db;        
        $return=array();                
        $subitems=$db->query(
            "select * from menu where parent is '$rootitemuuid'"
        );
        if($subitems!==false){                
            foreach($subitems as $sirow){
                $return[]=array(
                    'id' => $sirow['uuid'],
                    'caption' => $sirow['caption'],
                    'route' => $sirow['route'],
                    'verified' => $sirow['verified']===1?true:false
                );
            }
        } else {
            die($db->getlasterrormessage());            
        }
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

    function insertmenu($uuid, $caption, $route, $verified=false, $parent=null){
        $verified=$verified===true?1:($verified===false?0:$verified);
        $parent=$parent===null?$parent='NULL':"'$parent'";
        $db=&$this->db;                
        $ret=$db->exec("insert into menu (
            uuid, caption, route, verified, parent
        ) values (
            '$uuid', '$caption', '$route', $verified, $parent
        )");
        return $ret;
    }
     

}

