<?php

class Model {    
    
	public $connection;
	private $connection_type = 0;
	
	function Model(){
        
		$this->connection_type = DB_CONNECTION_USE_PDO_MYSQL;
        $this->Connect();
	}
	
	function Connect(){
        
        try{
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
            $this->connection = new PDO("mysql:host=".MYSQL_DATABASE_SERVER.";dbname=".MYSQL_DATABASE_NAME."",
                                        MYSQL_DATABASE_USER, MYSQL_DATABASE_PASSWORD, $options);

            $this->connection->exec("SET NAMES UTF8");

        } catch (PDOException $e){
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
	}
	
	function Disconnect(){
	
        $this->connection = null;
	}
}
?>