<?php

class DBConfig
{

    private $servername = "localhost";
    private $dbname = "dbweb";
    private $username = "cs3";
    private  $password = "123";


    public function dbConnect()
    {    
        $conn = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname        
        );
        return $conn;
    }

}//end of class

?>
