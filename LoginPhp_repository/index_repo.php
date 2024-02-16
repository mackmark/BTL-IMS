<?php
    include "../Database/db_connection.php";
    include "../Php_function/functions.php";


    $username = "";
    $password = "";
    $encryptPassword = "";
    $result = 0;

    if(isset($_POST['username'])){
        $username = $_POST['username'];
    }

    if(isset($_POST['password'])){
        $password = $_POST['password'];
    }

    // $encryptPassword = password_hash($password, PASSWORD_DEFAULT);

    $result = Login($username, $password, $connServer);
    $UserLevelID = 0;
    if(isset($_COOKIE['BTL_UlevelID'])){
        $UserLevelID = $_COOKIE['BTL_UlevelID'];
    }

    $arr = array(
        'result' => $result,
        'UserLevelID' => $UserLevelID
    );
    
    echo json_encode($arr);

?>