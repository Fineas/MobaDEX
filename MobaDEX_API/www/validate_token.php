<?php

session_start();

function validate_tok($token){

    // CONNECT TO DB
    $conn = mysqli_connect('db', $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
    if ($conn -> connect_errno) {
      echo "Failed to connect to MySQL: ";
      exit();
    }
    else{

        // RETRIEVE ALL USERS
        $safe_token = mysqli_real_escape_string($conn, $token);
        $query = "SELECT * FROM Person WHERE Token='".$safe_token."'";
        $result = mysqli_query($conn, $query) or trigger_error("Query Failed! SQL: $sql - Error: ", E_USER_ERROR); // add mysqli_error($conn) to display the error
    
        // NO MATCHING TOKEN WAS FOUND
        if($result == false){ return 0; }

        // MATCHING TOKEN IS FOUND, RETURN 1
        else{ return 1; }
    }

}

?>