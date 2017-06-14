<?php

if (! defined ( 'mutyurphpmvc_inited' ))
	exit ( 'No direct script access allowed' );
	
/**
 *  @file database.php
 *  @brief Simple database classes for MutyurPHPMVC. Project home: https://github.com/vajayattila/songcontestweb
 *	@author Vajay Attila (vajay.attila@gmail.com)
 *  @copyright MIT License (MIT)
 *  @date 2017.06.13
 *  @version 1.0.0.0
 */

/** @brief databaseintf interface */
interface databaseintf{
    /** @brief open database */
    public function open($dbname);
    /** @brief close database */
    public function close();
    /** @brief database is exists*/
    public function databaseisexist($dbname);
    /** @brief Create database if not exists*/
    public function createtableifnotexists($tname, $columndef);
    /** @brief Drop table*/
    public function droptable($tname);
    /** @brief execute query
      * @return true is success   
      */
    public function exec($sql);
    /** @brief execute query
      * @return result array
      */
    public function query($sql);    
    public function getlasterrormessage();
}

class dbhelper extends helper{

    function __construct(){
        parent::__construct();        
		// Dependency
		dbhelper::setup_dependencies(
			dbhelper::get_class_name(), dbhelper::get_version(), 'extension',
			array(
				'helper'=>'1.0.0.1'
			)
		);
    }

	public function get_class_name(){
		return 'dbhelper';
	}
	
	public function get_version(){
		return '1.0.0.1';
	}

    /** @brief field definition
      * @param $fname [in] field name 
      * @param $ftype [in] field type for example: INTEGER, REAL, TEXT, BLOB, NUMERIC
      * @param $constrains [in] constrains for example PRIMARY KEY ASC AUTOINCREMENT
      * @return field definition string
      */
    function field($fname, $ftype, $constrains=''){
        return " $fname $ftype $constrains ";    
    }

    /** @brief Conflict clause
      * @param $action [in] On conflict, for example: ROLLBACK, ABORT, FAIL, IGNORE, REPLACE   
      * @return Conflict clause definition 
      */
    function conflictclause($action=''){
        $return='';
        if($action!==''){
            $return=" ON CONFLICT $action ";    
        }
        return $return;
    }

    /** @brief Primary key constraint
      * @param $cname [in] Name of constraint
      * @param $order [in] Optional order, for example: ASC, DESC
      * @param $conflictclause [in] Optional Conflict clause
      * @param $autoincrement [in] Optional autoincrement, for example: AUTOINCREMENT
      * @return Constraint definition
      */
    function primary($cname, $order='', $conflictclause='' , $autoincrement=''){
        $temp='';
        if($cname!==''){
            $temp=" CONSTRAINT $cname ";
        }    
        return " $temp PRIMARY KEY $order $conflictclause $autoincrement ";
    }

    /** @brief Not null constraint
      * @param $conflictclause [in] Optional Conflict clause
      * @return Constraint definition
      */
    function notnull($conflictclause=''){
        return " NOT NULL $conflictclause ";
    }

    /** @brief Unique constraint
      * @param $conflictclause [in] Optional Conflict clause
      * @return Constraint definition
      */
    function unique($conflictclause=''){
        return " UNIQUE $conflictclause ";
    }

    /** @brief Check constraint
      * @param $expression [in] Expression
      * @return Constraint definition
      */
    function check($expression){
        return " CHECK ($expression) ";
    }

    /** @brief Default constraint
      * @param $value [in] Default vallue
      * @return Constraint definition
      */
    function default($value){
        return " DEFAULT $value ";
    }

    /** @brief Collate constraint
      * @param $value [in] Default vallue
      * @return Constraint definition
      */
    function collate($collationname){
        return " COLLATE $collationname ";
    }

    /** @brief Foreign key constraint
      * @param $ftablename [in] Name of foreign table
      * @param $columnnames [in] Name of column or columns
      * @param $onandmatch [in] List of on and match definitions
      * @param $defferable [in] defferable definition
      * @return Constraint definition
      */
    function foreignkey($ftablename, $columnnames, $onandmatch, $defferable){
        return " REFERENCES $ftablename $columnnames, $onandmatch, $defferable ";
    }

    /** @brief 'ON' definition
      * @param $mode [in] mode, for example: DELETE, UPDATE
      * @param $action [in] referential action: SET NULL, SET DEFAULT, CASCADE, RESTRICT, NO ACTION
      * @return Definition      
      */
    function on($mode, $action){
       return " ON $mode $action "; 
    }

    function setnull(){
       return " SET NULL "; 
    }

    function setdefault(){
       return " SET DEFAULT "; 
    }

    function cascade(){
       return " CASCADE "; 
    }

    function restrict(){
       return " RESTRICT "; 
    }

    function noaction(){
       return " NO ACTION "; 
    }
    
    function match($name){
       return " MATCH $name ";
    }

    /** @brief notdefferable definition
      * @param $mode [in] optional mode, for example: DEFFERED, IMMEDIATE
      * @return Definition
      */
    function deferrable($not='', $mode=''){
        $temp='';
        if($mode!==''){
            $temp=" INITIALLY $mode";    
        }
        return " $not DEFERRABE $temp ";
    }

    /** @brief convert parameters to string
     * @param ... [in] The first parameter is the separator string
     */
    function set(){
        $return = '';
        $count = func_num_args();        
        $args = func_get_args();
        $separator = '';
        for ($i = 0; $i < $count; $i++) {
            if($i===0){
                $separator=$args[$i];
            } else {
                if($return!==''){
                    $return.=$separator;    
                }
                $return.=$args[$i];
            }
        }
        return $return;
    }
}

class sqlitedb extends dbhelper implements databaseintf {
    protected $db;

    public function __construct(){
        parent::__construct();        
		// Dependency
		sqlitedb::setup_dependencies(
			sqlitedb::get_class_name(), sqlitedb::get_version(), 'extension',
			array(
				'dbhelper'=>'1.0.0.1'
			)
		);
        $this->extension_loaded('sqlite3', true);
    }

	public function get_class_name(){
		return 'sqlitedb';
	}
	
	public function get_version(){
		return '1.0.0.0';
	}

    public function open($dbname){
        $this->db=new SQLite3($dbname);        
    }

    public function close(){
        return $this->db->close ();
    }

    public function databaseisexist($dbname){
        return file_exists ($dbname);
    }

    public function createtableifnotexists($tname, $columndef){
        $command="CREATE TABLE IF NOT EXISTS $tname($columndef)";
        return $this->db->exec (
            $command
        );
    }

    public function droptable($tname){
        return $this->exec (
            "DROP TABLE $tname;"
        );    
    }

    public function exec($sql){
        return $this->db->exec($sql);    
    }    

    public function query($sql){
        $result=$this->db->query($sql);
        $return=array();
        while($row= $result->fetchArray(SQLITE3_ASSOC)){ 
            $return[]=$row;
        } 
        return $return;
    }    

    public function getlasterrormessage(){
        return $this->db->lastErrorMsg();
    }
}

