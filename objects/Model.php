<?php

class Model {

    protected $connectDB = null;

    public function __construct () {

         $servername = "localhost";
         $username = "root";
         $password = "";

        try {

            $this -> connectDB = new \PDO("mysql:host=$servername;dbname=blog", $username, $password);
            $this -> connectDB -> exec( 'SET CHARACTER SET utf8mb4' );
            $this -> connectDB -> exec( 'SET NAMES utf8mb4' );
        } catch ( \PDOException $e ) {

            die( $e -> getMessage() );
        }
    }
}

?>
