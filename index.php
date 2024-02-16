<?php
    // $password = $_POST['password'];
    // $username = 'BTLAdmin';
    // $password = 'BTLAdmin12345!';
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // echo $hashedPassword.'</br>';

    // $password = 'BTLAdmin12345!';
    // $storedHashedPassword = '$2y$10$zM6fLYwH4MEZ41S26k5h5ezA3g33y8IHadx6I0008R6.NCH8BrzFu'; // Retrieve the stored hashed password from the database

    // if (password_verify($password, $storedHashedPassword)) {
    //     echo 'correct';
    // } else {
    //     echo 'Incorrect';
    // }

    // $password = 'M0tol!te123!';
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // echo $hashedPassword.'</br>';

    // include "Database/db_connection.php";

    // $username = 'BTLAnalyst';
    // $password = 'M0tol!t3Tech01';
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // echo $hashedPassword.'</br>';
    // $hashedPassword = '$2y$10$UroRttoi1RRIiy1e2VLS9.cJoonPUVVlc6/PdWLtFyG7PcjQ61GKm';

    session_name('sessionBTL');
    session_start();
    $ulevelID = 0;
    if(isset($_COOKIE['BTL_UlevelID'])){
        $ulevelID = $_COOKIE['BTL_UlevelID'];
        if($ulevelID == 1){
            echo "<script type='text/javascript'>location.href='Customer/index.php';</script>";
        }
        else if($ulevelID == 2){
            echo "<script type='text/javascript'>location.href='LabAnalyst/index.php';</script>";
        }
        else if($ulevelID == 3){
            echo "<script type='text/javascript'>location.href='LabManager/index.php';</script>";
        }
        else{
            echo "<script type='text/javascript'>location.href='index.php';</script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTL-LIMS | Login</title>
    <link rel="stylesheet" href="assets/css/main/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <link rel="shortcut icon" href="assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/png">

    <link rel="stylesheet" href="assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <!-- <div class="auth-logo">
                        <a href="index.html"><img src="assets/images/logo/logo.svg" alt="Logo"></a>
                    </div> -->
                    <h1 class="auth-title">Login</h1>
                    <p class="auth-subtitle mb-5">Battery Testing Laboratory</p>

                    <form>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="username" id="uname">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" placeholder="Password" id="password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>

                        
                        <!-- <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div> -->
                        
                        <button type="button" class="btn btn-primary btn-block btn-lg shadow-lg mt-5" id="Login">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Philippine Batteries, Inc.</p>
                    </div>
                    <!-- <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Don't have an account? <a href="auth-register.html" class="font-bold">Sign
                                up</a>.</p>
                        <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>

    </div>
</body>

<script src="assets_original/js/jquery.min.js"></script>
<script src="assets/extensions/sweetalert2/sweetalert2.min.js"></script>
<script src="LoginJS_repository/index_repo.js"></script>

</html>
