<?php

// UXM5qPUMHaaU5jN - register
// YOZ8AxBEUCgZEPG - login
// j0y2vm32GH6cfiP - add friend
// 6xP0R1sioF5knfv - send moba
// gJMauFlbAtmpOel - read moba
// hvD6trFjj5PF0sA - load mobas 
// KVY2ERbWMEGBgob
// BuD93I754ZVwUjv
// PU9BWUeCzxoNZ9L
// 8RKkRtU5QAARlgj
// hnoeIZlcuo4Pj5p
// f8NGvEKfg0hy1cj
// 1qIo9a4LzUQ00bh
// 59zgm4QEpUfiX2z
// INoAKCxzBS7mLEu
// dipXztkHl0tINIa
// nKyppmHiwYF6CQd
// 4nZcU5aH5xNm6Sl
// g673E8pVKvHTq2S
// m85qeBOTDpomJqX

function generateRandomString($length = 15) {
  $characters = '0123456789';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

// SET START TIME
$stime = time();

// CHECK IF IS POST REQUEST
if( isset($_POST) ){

  // GET TOKEN
  if( isset($_POST['q']) ){

    // ========================================================
    if(strcmp($_POST['q'],"UXM5qPUMHaaU5jN") == 0){ // REGISTER (username & email & password)
      if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])){

        $conn = mysqli_connect('db', $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        if ($conn -> connect_errno) {
          echo "Failed to connect to MySQL: ";
          exit();
        }

        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // CHECK USERNAME NOT REGISTERED ALREADY
        if (!($stmt = $conn->prepare("SELECT * FROM Users WHERE Username=?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->bind_param("s", $username)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        if(isset($row)){
          echo "Username already exists.";
          return 0;
        }

        // CHECK FOR TOKEN DUPLICATES
        $result = "garbage";
        while(strcmp($result,"") != 0){
          $token = generateRandomString(15);
          $query = "SELECT * FROM Users WHERE Token='".$token."'";
          $result = mysqli_query($conn, $query) or trigger_error("Query Failed! SQL: $sql - Error: ", E_USER_ERROR);
          $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }

        // ADD USER TO DB
        if (!($stmt = $conn->prepare("INSERT INTO `Users`(`Username`, `Email`, `Password`, `Token`) VALUES (?,?,?,?)"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->bind_param("ssss", $username, $email, $password_hash, $token)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        echo "Success";
        return 1;

      }else{
        echo "Invalid Request.";
        return 0;
      }
    }

    // =====================================================
    if(strcmp($_POST["q"],"YOZ8AxBEUCgZEPG") == 0){ // LOGIN (username & password)
      
      if(isset($_POST["username"]) && isset($_POST["password"])){

        $username = $_POST["username"];
        $password = $_POST["password"];

        $conn = mysqli_connect('db', $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        if ($conn -> connect_errno) {
          echo "Failed to connect to MySQL: ";
          exit();
        }
      
        // PULL CREDENTIALS FROM DB
        if (!($stmt = $conn->prepare("SELECT * FROM Users WHERE Username= ?"))) {
            echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stime = time();
        if (!$stmt->bind_param("s", $username)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);

        if(password_verify($password, $result[2])){
          /* The password is correct. */
          $login = TRUE;
          echo "Login Success";
        }
        else{
          echo "Login Failed";
        }
      }else{
        echo "Invalid Credentials.";
        return 0;
      }
    }

    // ==========================================================
    if(strcmp($_POST["q"],"j0y2vm32GH6cfiP") == 0){ // ADD FRIEND (user's token & friend's username)
      if(isset($_POST["token"]) && isset($_POST["username"])){

        $user_token = $_POST["token"];
        $friend_username = $_POST["username"];

        $conn = mysqli_connect('db', $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        if ($conn -> connect_errno) {
          echo "Failed to connect to MySQL: ";
          exit();
        }

        // CHECK IF TOKEN IS VALID
        if (!($stmt = $conn->prepare("SELECT * FROM Users WHERE Token= ?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->bind_param("s", $user_token)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);
        if($result == ""){
          echo "Invalid Token.";
          return 0;
        }
        else{
          $user_adding_friend = $result[0];
        }

        // CHECK IF USERNAME EXISTS
        if (!($stmt = $conn->prepare("SELECT * FROM Users WHERE Username= ?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->bind_param("s", $friend_username)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);

        if($result == ""){
          echo "Invalid Username.";
          return 0;
        }

        // CHECK IF FRIENSHIP ALREADY EXISTS
        if (!($stmt = $conn->prepare("SELECT * FROM Friends WHERE user=? AND friend=?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->bind_param("ss", $user_adding_friend, $friend_username)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);
        
        // NO FRIENDS YET, MUSC CREATE COLUMN
        if($result ==  ""){
          $safe_column_name = mysqli_real_escape_string($conn,$friend_username);
          if (!($stmt = $conn->prepare("INSERT INTO Friends(user, friend) VALUES (?,?)"))) {
            echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
            die;
          }
          if (!$stmt->bind_param("ss", $user_adding_friend, $friend_username)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            die;
          }
          if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            die;
          }
        }
        else{
          echo "Friend Already Exists";
        }
      }else{
        echo "Invalid Request.";
        return 0;
      }
    }
    
    
    // ==========================================================
    if(strcmp($_POST["q"],"6xP0R1sioF5knfv") == 0){ // SEND MOBA (user's token & friend's username & data to send)
      if(isset($_POST["token"]) && isset($_POST["username"]) && isset($_POST["data"])){

        $user_token = $_POST["token"];
        $friend_username = $_POST["username"];
        $data = $_POST["data"];

        $conn = mysqli_connect('db', $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        if ($conn -> connect_errno) {
          echo "Failed to connect to MySQL: ";
          exit();
        }

        // CHECK IF TOKEN IS VALID
        if (!($stmt = $conn->prepare("SELECT * FROM Users WHERE Token= ?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->bind_param("s", $user_token)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);
        if($result == ""){
          echo "Invalid Token";
          die;
        }
        else{
          $user_username = $result[0];
        }

        // CHECK IF USERNAME IS VALID
        if (!($stmt = $conn->prepare("SELECT * FROM Users WHERE Username=?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->bind_param("s", $friend_username)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);
        if($result == ""){
          echo "Invalid Token";
          die;
        }

        // INSERT MOBA IN DB
        if (!($stmt = $conn->prepare("INSERT INTO Mobs(Mob_From, Mob_To, Mob_Data, Mob_Status) VALUES (?,?,?,0)"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->bind_param("sss", $user_username, $friend_username, $data)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        $result = $stmt->get_result();

      }else{
        echo "Invalid Request.";
        return 0;
      }
    }

    // ==========================================================
    if(strcmp($_POST["q"],"gJMauFlbAtmpOel") == 0){ // READ MOBA (user's token & moba ID)
      if(isset($_POST["token"]) && isset($_POST["ID"])){

        $user_token = $_POST["token"];
        $moba_unique_id = $_POST["ID"];

        $conn = mysqli_connect('db', $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        if ($conn -> connect_errno) {
          echo "Failed to connect to MySQL: ";
          exit();
        }

        // CHECK IF TOKEN IS VALID
        if (!($stmt = $conn->prepare("SELECT * FROM Users WHERE Token= ?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->bind_param("s", $user_token)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);
        if($result == ""){
          echo "Invalid Token";
          die;
        }
        else{
          $user_username = $result[0];
        }

        // CHECK IF ID BELONGS TO USER
        if (!($stmt = $conn->prepare("SELECT * FROM Mobs WHERE Mob_To= ? AND Moba_ID= ?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->bind_param("si", $user_username, $moba_unique_id)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);
        if($result == ""){
          echo "Invalid Moba";
          die;
        }
        else{
          var_dump($result);
          $moba_content = $result[2];
        }

      }
    }

    // ==========================================================
    if(strcmp($_POST["q"],"hvD6trFjj5PF0sA") == 0){ // LIST MOBAS (user's token)
      if(isset($_POST["token"])){

        $user_token = $_POST["token"];

        $conn = mysqli_connect('db', $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        if ($conn -> connect_errno) {
          echo "Failed to connect to MySQL: ";
          exit();
        }

        // CHECK IF TOKEN IS VALID
        if (!($stmt = $conn->prepare("SELECT * FROM Users WHERE Token= ?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->bind_param("s", $user_token)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);
        if($result == ""){
          echo "Invalid Token";
          die;
        }
        else{
          $user_username = $result[0];
        }

        // LOAD MOBAS
        if (!($stmt = $conn->prepare("SELECT * FROM Mobs WHERE Mob_To= ?"))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->bind_param("s", $user_username)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          die;
        }
        $result = $stmt->get_result();
        $result = $result->fetch_array(MYSQLI_NUM);
        if($result == ""){
          echo "Invalid Moba";
          die;
        }
        else{
          var_dump($result);
          // $moba_content = $result[2];
        }

      }
    }

  }else{
    var_dump($_POST);
    echo 'Unknown Request.';
    return 0;
  }
}else{
  echo "Unknown Request.";
}

?>