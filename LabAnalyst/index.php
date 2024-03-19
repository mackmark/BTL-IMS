<?php
    session_name('sessionBTL');
    session_start();
    if(!isset($_COOKIE['BTL_employeeID'])){
        echo "<script type='text/javascript'>location.href='../index.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTL LIMS | Lab Analyst</title>
    <link rel="stylesheet" href="../assets/extensions/choices.js/public/assets/styles/choices.css">
    <link rel="stylesheet" href="../assets/css/main/app.css">
    <link rel="stylesheet" href="../assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="../assets/extensions/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="../assets_original/css/datatables.min.css"/>
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="../assets_original/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets_original/css/draganddrop.css">
    <link rel="stylesheet" href="../node_modules/frappe-gantt/dist/frappe-gantt.css">

    <link rel="stylesheet" href="../assets/css/shared/iconly.css">

    <style>
        .scanned-border {
            border: 7px solid yellow;
            border-radius: 5px; /* You can customize the border style here */
        }

        #camera {
            width: 100%; /* Set the width to 100% to make it responsive */
            max-width: 700px; /* Optionally, set a maximum width */
            height: auto; /* Maintain aspect ratio */
            border: 2px solid grey; /* Add a border around the video */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Add a box shadow for a visual effect */
        }

        /* Scanning line styles */
        #scanner-line {
            width: 100%;
            height: 2px;
            background-color: transparent; /* Transparent background */
            position: absolute;
            top: 0;
            left: 0;
            box-shadow: 0 0 10px #00FF00; /* Green shadow effect */
            animation: scan 2s linear infinite; /* Animation duration and type */
        }

        @keyframes scan {
            0% {
                top: 0;
            }
            100% {
                top: 100%; /* Move the line to the bottom */
            }
        }

        .cube {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .row {
            display: flex;
        }

        .cell {
            width: 45px; /* Adjust the width and height as needed */
            height: 25px;
            background-color: #FFFFFF; /* Change the background color as desired */
            border: 2px solid #445069; /* Change the border color as desired */
            margin: 0.2px; /*  Adjust the margin between cells as needed*/
        }

        .cell-bitrode{
            width: 75px; /* Adjust the width and height as needed */
            height: 25px;
            background-color: #FFFFFF; /* Change the background color as desired */
            border: 2px solid #445069; /* Change the border color as desired */
            margin: 0.2px;  /* Adjust the margin between cells as needed*/
        }

        .cell-divider{
            width: 75px; /* Adjust the width and height as needed */
            height: 5px;
            background-color: #445069; /* Change the background color as desired */
            border: 2px solid #445069; /* Change the border color as desired */
            margin: 0.2px;  /* Adjust the margin between cells as needed*/
        }

        .cell-allocate{
            width: 175px; /* Adjust the width and height as needed */
            height: 120px;
            background-color: #FFFFFF; /* Change the background color as desired */
            border: 4px solid #445069; /* Change the border color as desired */
            margin: 0.2px;  /*Adjust the margin between cells as needed*/
        }

        #gantt-chart {
            height: 200px;
            border: 1px solid #ccc;
            overflow-x: auto; /* Enable horizontal scrollbar when content overflows */
            white-space: nowrap;
            position: relative;
        }

        .task {
            width: 100px;
            height: 30px;
            background-color: #3498db;
            color: #fff;
            text-align: center;
            line-height: 30px;
            position: absolute;
        }
        /* Style the Gantt chart and calendar container */

        #add-task-form {
            display: none;
        }

        .bold-task .gantt_task_content {
            font-weight: bold;
        }

        .gantt-chart {
            position: relative;
            width: 100%;
            height: 400px;
            border: 1px solid #ccc;
        }
        .task {
            position: absolute;
            height: 40px;
            background-color: #3498db;
            color: #fff;
            text-align: center;
            line-height: 40px;
            overflow: hidden;
        }

        .gantt-container {
            width: 90vw; 
            margin: 0 auto;
            height: 400px; /* Set the desired height */
            overflow-y: auto;
        }
        .chart-controls {
            text-align: center;
        }
        .chart-controls > p {
            font-size: 1.2rem;
            font-weight: 500;
        }

        .chart-label {
            font-size: 1.5rem;
            text-align: center;
            font-weight: 500;
        }

        .hidden-row {
            display: none;
        }

        .rounded-rectangle {
            width: 80%; /* Set width in percentage */
            max-width: 400px; /* Set a maximum width if needed */
            height: 120px;
            /* background-color: #3498db; */
            border: 2px solid #000000;
            border-radius: 10px;
            /* display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px; */
            margin: 0 auto; /* Center the rectangle horizontally */
            white-space: nowrap; /* Prevent text from wrapping onto a new line */
            overflow: hidden; /* Hide any content that overflows the container */
            text-overflow: ellipsis;
        }

        @media (max-width: 767px) {
            .rounded-rectangle {
                width: 90%; /* Adjust width for smaller screens */
            }
        }

        .superscript {
            position: relative;
            display: inline-block;
        }

        .superscript span {
            position: absolute;
            top: -0.1em;
            right: -5;
            font-size: 0.8em; /* Adjust the font size as needed */
        }

    </style>
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                    <div class="sidebar-header position-relative">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="logo">
                                    <!-- <a href="index.php"><img src="assets/images/logo/logo.svg" alt="Logo" srcset=""></a> -->
                                    <a href="index.php"><span>BTL</span></a>
                                </div>
                                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path><g transform="translate(-210 -1)"><path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path><circle cx="220.5" cy="11.5" r="4"></circle><path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path></g></g></svg>
                                    <div class="form-check form-switch fs-6">
                                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" >
                                        <label class="form-check-label" ></label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path></svg>
                                </div>
                                <div class="sidebar-toggler  x">
                                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                                </div>
                            </div>
                    </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>
                        
                        <li
                            class="sidebar-item  active">
                            <a href="" class='sidebar-link' onclick="loadContent(1)" >
                                <i class="bi bi-grid-fill"></i>
                                <span>Battery Request</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item">
                            <a href="" class='sidebar-link' onclick="loadContent(2)" >
                                <i class="bi bi-stack"></i>
                                <span>Circuit Map</span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>

        <div id="main" class='layout-navbar'>
            <header>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <div class="mt-3">
                                <h5 class="d-none d-sm-block">&nbsp;&nbsp;&nbsp;Information Management System</h5>
                            </div>
                            <ul class="navbar-nav ms-auto mb-lg-0">
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3 d-none d-sm-block">
                                            <h6 class="mb-0 text-gray-600"><?php echo $_COOKIE['BTL_FirstName'].' '.$_COOKIE['BTL_LastName']; ?></h6>
                                            <p class="mb-0 text-sm text-gray-600"><?php echo $_COOKIE['BTL_Ulevel']; ?></p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="../assets/images/faces/2.jpg">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                                    <li>
                                        <h6 class="dropdown-header">Hello, <?php echo $_COOKIE['BTL_FirstName'].'!'; ?></h6>
                                    </li>
                                    <!-- <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-person me-2"></i> My
                                            Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                            Settings</a></li> -->
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" onclick='logout()'><i
                                                class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="main-content"> 
                    <div id="content">
                        <section class="section position-relative" id="BtrCard">
                            <div class="card">
                                <div class="card-header">
                                    <div class="container-fluid">
                                        <h4 class="card-title float-start">Battery Request</h4>
                                        <button class="btn btn-md icon btn-primary float-end" id="scan_btn"><i class="bi bi-upc-scan"></i> &nbsp;&nbsp;Scan</button>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <!-- Table with no outer spacing -->
                                    <div class="divider divider-left">
                                        <div class="divider-text text-primary">Data Summary</div>
                                    </div>
                                    <div class="container-fluid">
                                        <input type="radio" class="btn-check btn_summary" name="options-outlined" value="1" id="primary-outlined3"
                                                autocomplete="off" checked>
                                        <label class="btn btn-outline-secondary" for="primary-outlined3">View All</label>

                                        <input type="radio" class="btn-check btn_summary" name="options-outlined" value="2" id="primary-outlined2"
                                            autocomplete="off">
                                        <label class="btn btn-outline-primary" for="primary-outlined2">Sample Receiving</label>

                                        <input type="radio" class="btn-check btn_summary" name="options-outlined" value="3" id="primary-outlined"
                                            autocomplete="off">
                                        <label class="btn btn-outline-primary" for="primary-outlined">Test Planning</label>

                                        <!-- <input type="radio" class="btn-check btn_summary" name="options-outlined" value="3" id="primary-outlined4"
                                            autocomplete="off">
                                        <label class="btn btn-outline-primary" for="primary-outlined4">Initial Measurement</label> -->

                                        <input type="radio" class="btn-check btn_summary" name="options-outlined" value="4" id="primary-outlined5"
                                            autocomplete="off">
                                        <label class="btn btn-outline-primary" for="primary-outlined5">Battery Testing</label>

                                        <input type="radio" class="btn-check btn_summary" name="options-outlined" value="5" id="primary-outlined6"
                                            autocomplete="off">
                                        <label class="btn btn-outline-primary" for="primary-outlined6">Completed</label>

                                    </div>
                                    <div id="btr">
                                        <div class="container-fluid mt-2">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <label>Legend:</label>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <span class="text-primary" id="prioritizationLegend" style="cursor:pointer;">
                                                                <i class="bi bi-exclamation-triangle-fill text-secondary"></i>
                                                                Prioritization
                                                            </span>
                                                            
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <span class="text-primary">
                                                                <i class="bi bi-info-circle-fill text-primary"></i>
                                                                Special Instructions
                                                            </span>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-hover" id="btr_tbl">
                                                <thead style="font-size:14px;">
                                                    <tr>
                                                        <th class="text-center">Request ID</th>
                                                        <th class="text-center">Requestor</th>
                                                        <th class="text-center">Requested Date</th>
                                                        <th class="text-center">Project Name</th>
                                                        <th class="text-center">Purpose</th>
                                                        <th class="text-center">Objective</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Requirement</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-left">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size:13px;">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="received">
                                        <div class="container-fluid mt-2">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <label>Legend:</label>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <span class="text-primary" id="prioritizationLegend" style="cursor:pointer;">
                                                                <i class="bi bi-exclamation-triangle-fill text-secondary"></i>
                                                                Prioritization
                                                            </span>
                                                            
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <span class="text-primary">
                                                                <i class="bi bi-info-circle-fill text-primary"></i>
                                                                Special Instructions
                                                            </span>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-hover" id="Ongoing_tbl">
                                                <thead style="font-size:14px;">
                                                    <tr>
                                                        <th class="text-center">Test ID</th>
                                                        <th class="text-center">Sample ID</th>
                                                        <th class="text-center">Battery Code</th>
                                                        <th class="text-center">Requestor</th>
                                                        <th class="text-center">Test Parameters</th>
                                                        <th>Date Received</th>
                                                        <th class="text-center">Circuit</th>
                                                        <th class="text-center">Test Plan</th>
                                                        <th class="text-left">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size:13px;">

                                                    <tr>
                                                        <td class="text-center">T-3006-001</td>
                                                        <td class="text-center">PD2306-001</td>
                                                        <td class="text-center">WF12345</td>
                                                        <td class="text-center">MCCruz</td>
                                                        <td class="text-center">M-series 1</td>
                                                        <td class="text-center">ADR1</td>
                                                        <td>August 23, 2023</td>
                                                        <td>
                                                            <div class="badges">
                                                                <span class="badge bg-light-success">On-going</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                                                <button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                                                <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="testTable">
                                        <div class="container-fluid mt-2">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <label>Legend:</label>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <span class="text-primary" id="prioritizationLegend" style="cursor:pointer;">
                                                                <i class="bi bi-exclamation-triangle-fill text-secondary"></i>
                                                                Prioritization
                                                            </span>
                                                            
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <span class="text-primary">
                                                                <i class="bi bi-info-circle-fill text-primary"></i>
                                                                Special Instructions
                                                            </span>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-hover" id="Testing_tbl">
                                                <thead style="font-size:14px;">
                                                    <tr>
                                                        <th class="text-center">Test ID</th>
                                                        <th class="text-center">Sample ID</th>
                                                        <th class="text-center">Battery Code</th>
                                                        <th class="text-center">Requestor</th>
                                                        <th class="text-center">Test Parameters</th>
                                                        <th>Date Received</th>
                                                        <th class="text-center">Circuit</th>
                                                        <th class="text-center">Test Forms</th>
                                                        <th class="text-left">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size:13px;">

                                                    <tr>
                                                        <td class="text-center">T-3006-001</td>
                                                        <td class="text-center">PD2306-001</td>
                                                        <td class="text-center">WF12345</td>
                                                        <td class="text-center">MCCruz</td>
                                                        <td class="text-center">M-series 1</td>
                                                        <td class="text-center">ADR1</td>
                                                        <td>August 23, 2023</td>
                                                        <td>
                                                            <div class="badges">
                                                                <span class="badge bg-light-success">On-going</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                                                <button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                                                <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="section position-relative" id="CirecuitMapCard">
                            <div class="card">
                                <div class="card-header">
                                    <div class="container-fluid">
                                        <h4 class="card-title float-start">Circuit Mapping</h4>
                                        <div class="btn-group mb-3 float-end" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-light">
                                                Vacant <span id="MapVacant" class="badge bg-secondary">122</span>
                                            </button>
                                            <button type="button" class="btn btn-primary">
                                                In Use <span id="MapInUse" class="badge bg-transparent">4</span>
                                            </button>
                                            <button type="button" class="btn btn-danger">
                                                Defect <span id="MapDefect" class="badge bg-transparent">0</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0" style="overflow-y:scroll;height:500px;overflow-x:hidden;">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb1_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb2_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb4_map">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb5_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb6_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb7_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb8_map">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb16_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb15_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb14_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb13_map">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb12_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb11_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb10_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb9_map">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                </div>

                                                <div class="col-lg-3">
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb20_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb19_map">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="row">

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb18_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="cube" id="wb17_map">
                                                    </div>
                                                </div>

                                                <div class="col-lg-1">
                                                </div>

                                                <div class="col-lg-5">
                                                    <div class="cube position-relative" style="top:-65px; left:23px;" id="wbRcnRct_map">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Report table managing modal [ADD, EDIT, DELETE] button block-->
                        <div class="modal fade text-left" id="scanQR" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelReview">Scan QR Code
                                        </h5>
                                        <button type="button" class="close" id="closeScannerModal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card p-0">
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="border p-4">
                                                            <div class="embed-responsive embed-responsive-16by9" id="scanner">
                                                                <video id="camera" autoplay class="embed-responsive-item"></video>
                                                                <div id="scanner-line"></div> <!-- Scanning line -->
                                                            </div>
                                                            <canvas id="canvas" style="display: none;"></canvas>
                                                        </div>
                                                        <div class="container text-center">
                                                            <h5 id="decoded-data" class="mt-4 "></h5>
                                                            <input type="hidden" id="Qrval">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- <div id="result"></div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-sm btn-light-secondary" id="closeScannerModal2">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <!-- <button type="button" class="btn btn-sm btn-primary ml-1" id="submitMTest">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Update</span>
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="ViewBTR" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelViewBTR" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelViewBTR">Battery Test Request
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="BTRViewBody">
                                        
                                    </div>
                                    <div class="modal-footer" id="viewBtrFooter"> 
                                        <!-- <div class="container text-center">
                                            <button type="button" class="btn btn-light-warning" id="BTR_RevisioBtn">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">For Revision</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="ApproveBTRBtn">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Approve</span>
                                            </button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="RecievingModal" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelReview">Receiving
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="BTRReceiveViewBody">
                                        
                                    </div>
                                    <div class="modal-footer" id="viewReceiveBtrFooter"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="ReceivedBtn">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Receive</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="SampleLabellingModal" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelReview">Battery Samples
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="SamplesViewBody" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="text-center">
                                                    <h6>PLEASE CHECK THE SAMPLES QUANTITY​</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive container-fluid" style="overflow-y:scroll;height:400px;">
                                                    <h6 id="samplesLabelQty"class="text-center">TOTAL QUANTITY: 10 PCS​</h6>
                                                    <table class="table">
                                                        <thead class="thead-light">
                                                            <th class="text-center">BATTERY SAMPLE ID</th>
                                                            <th class="text-center">BATTERY TESTS</th>
                                                        </thead>
                                                        <tbody id="sample_div">
                                                            <!-- <tr>
                                                                <td>
                                                                    <div class="container mt-3 text-center position-relative">
                                                                        <img src="../SamplesQRuploads/PD2310-0013.png" width="50" class="img-fluid" alt="">
                                                                        <p style="font-size:14px;font-weight:bold;">PD2310-0001</p>
                                                                    </div>
                                                                    
                                                                </td>
                                                                <td>
                                                                    <div class="container text-center mt-4">
                                                                        <p style="line-height:2px;">M-Series 1</p>
                                                                        <p style="font-size:12px;font-weight:bold;">T2310-0001</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="container mt-3 text-center position-relative">
                                                                        <img src="../SamplesQRuploads/PD2310-0013.png" width="50" class="img-fluid" alt="">
                                                                        <p style="font-size:14px;font-weight:bold;">PD2310-0001</p>
                                                                    </div>
                                                                    
                                                                </td>
                                                                <td>
                                                                    <div class="container text-center mt-4">
                                                                        <p style="line-height:2px;">M-Series 1</p>
                                                                        <p style="font-size:12px;font-weight:bold;">T2310-0001</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="container mt-3 text-center position-relative">
                                                                        <img src="../SamplesQRuploads/PD2310-0013.png" width="50" class="img-fluid" alt="">
                                                                        <p style="font-size:14px;font-weight:bold;">PD2310-0001</p>
                                                                    </div>
                                                                    
                                                                </td>
                                                                <td>
                                                                    <div class="container text-center mt-4">
                                                                        <p style="line-height:2px;">M-Series 1</p>
                                                                        <p style="font-size:12px;font-weight:bold;">T2310-0001</p>
                                                                    </div>
                                                                </td>
                                                            </tr> -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-primary ml-1" id="PrintInterface">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Print Labels</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Report table managing modal [ADD, EDIT, DELETE] button block end-->

                        <div class="modal fade text-left" id="CircuitAllocation" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-fullscreen"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelReview">Circuit Mappping
                                        </h5>
                                        <div class="btn-group mb-3" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-light" >
                                                Vacant <span class="badge bg-secondary" id="circuitVacant">122</span>
                                            </button>
                                            <button type="button" class="btn btn-primary">
                                                In Use <span class="badge bg-transparent" id="circuitUSe">4</span>
                                            </button>
                                            <button type="button" class="btn btn-danger">
                                                Defect <span class="badge bg-transparent" id="circuitDefect">0</span>
                                            </button>
                                            
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i data-feather="x"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="SamplesViewBody" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-body p-0" style="overflow-y:scroll;height:500px;overflow-x:hidden;">
                                                <input type="hidden" id="TestSampleID">
                                                <input type="hidden" id="TestSampleNo">
                                                <input type="hidden" id="transferSampleID">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb1">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb2">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <!-- <div class="cube">
                                                                    <div class="row">
                                                                        <div class="cell"></div>
                                                                        <div class="cell"></div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="cell"></div>
                                                                        <div class="cell"></div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="cell"></div>
                                                                        <div class="cell"></div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="cell"></div>
                                                                        <div class="cell"></div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="cell"></div>
                                                                        <div class="cell"></div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="cell"></div>
                                                                        <div class="cell"></div>
                                                                    </div>
                                                                </div> -->
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb4">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb5">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb6">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb7">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb8">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-lg-6">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb16">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb15">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb14">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb13">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb12">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb11">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb10">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb9">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-lg-6">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            </div>

                                                            <div class="col-lg-3">
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb20">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb19">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="row">

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb18">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <div class="cube" id="wb17">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-1">
                                                            </div>

                                                            <div class="col-lg-5">
                                                                <div class="cube position-relative" style="top:-65px; left:23px;" id="wbRcnRct">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="AllocationModal" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelReview">Circuit Allocation
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="text-center">
                                                    <h6 id="testSamplesText"></h6>
                                                    <input type="hidden" id="CellID">
                                                </div>
                                            </div>
                                            <div class="card-body" id="AllocationDiv">
                                               
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-primary ml-1" id="AllocateBtn">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Allocate</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TransferAllocationModal" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelReview">Circuit Allocation
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="text-center">
                                                    <h6 id="testSamplesText"></h6>
                                                    <input type="hidden" id="cellBathID">
                                                    <input type="hidden" id="transferCellID">
                                                    <input type="hidden" id="transferSampleID2">
                                                </div>
                                            </div>
                                            <div class="card-body" id="TransferCellDiv">
                                               
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-success ml-1" id="TransferBtn">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Transfer</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TestPlanTesting" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-xl"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Test Plan Setting
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                                <button class="btn btn-md btn-outline-primary float-end" id="btnTestChange">Edit <i class="bi bi-arrow-left-right"></i> </button>
                                            </div>
                                            <div class="card-body">
                                            <input type="hidden" id="TestSampleIDTestPlan">
                                            <input type="hidden" id="BatteryTypeLMMF">
                                            <input type="hidden" id="RequestID_holder">
                                            <input type="hidden" id="userStat">
                                            <input type="hidden" id="testTableID_holder">
                                            <p class="chart-label d-none">
                                                    Timescale: <span id="current-timescale">Day</span>
                                            </p> 
                                            <div class="chart-controls">
                                                <p>Sample Test Planning</p>
                                                <div class="button-cont">
                                                    <button id="day-btn" class="btn btn-light-primary">
                                                        Day
                                                    </button>

                                                    <button id="week-btn" class="btn btn-light-primary">
                                                        Week
                                                    </button>

                                                    <button id="month-btn" class="btn btn-light-primary">
                                                        Month
                                                    </button>
                                                </div>
                                            </div>
                                            <svg id="gantt"></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="StartTest">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Start Testing</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="EditSelectedTest" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Test Sequence
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="TestSampleIDTestPlanEdit">
                                                <input type="hidden" id="BatteryTypeLMMFEdit">
                                                <input type="hidden" id="RequestID_holderEdit">
                                                <input type="hidden" id="userStatEdit">
                                                <input type="hidden" id="testTableID_holderEdit">

                                                <ul class="inline" id="SelectedTestDiv"  style="list-style-type: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 1px;">

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="updateTest">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Update</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="InitialMeasurement" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTitle">Initial Measurement
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="SampleID">
                                                <input type="hidden" id="BatteryTypeID">
                                                <div class="container" id="LM_Div">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <h6 class="text-center">Pre - Activation Measurement</h6>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Weight, kg</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="LM_weight" class="form-control" name="LM_weight"
                                                                        placeholder="">
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Filling Acid SG</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="LM_SG" class="form-control" name="LM_SG"
                                                                        placeholder="">
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <h6 class="text-center">Activation Performance</h6>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>OCV 5s after filling, V</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="LM_OCV5" class="form-control" name="LM_OCV5"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>OCV 30s after filling, V</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="LM_OCV30" class="form-control" name="LM_OCV30"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Time to reach 12 V, s</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" class="form-control" id="TimeTo12V" pattern="[0-5][0-9]:[0-5][0-9]" title="Enter a valid time in the format MM:SS" placeholder="MM:SS">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Post-activation Monitoring</h6></div>
                                                    </div>
                                                    <div class="container">
                                                        <div class="form-check form-switch">
                                                            <label class="form-check-label float-end" for="flexSwitchCheckDefault">&nbsp;&nbsp;With Inert Gas</label>
                                                            <input class="form-check-input float-end" type="checkbox" id="flexSwitchCheckDefault">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered mb-0" id="initialMeasureTbl">
                                                            <thead>
                                                                <tr>
                                                                    <th>TIME</th>
                                                                    <th style='white-space:nowrap;'>OCV, V</th>
                                                                    <th style='white-space:nowrap;'>Midtronics CCA, A</th>
                                                                    <th style='white-space:nowrap;'>Temp., °C</th>
                                                                    <th style='white-space:nowrap;'>ACID SG</th>
                                                                    <th style='white-space:nowrap;'>IR, mΩ</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <h6>5 mins</h6>
                                                                        <input type="hidden" id="time"  name="time"
                                                                        value="5">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="OCVTbl" class="form-control" name="OCVTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="CCATbl" class="form-control" name="CCATbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="TempTbl" class="form-control" name="TempTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="SGTbl" class="form-control" name="SGTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="IRTbl" class="form-control" name="IRTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'><h6>10 mins</h6>
                                                                    <input type="hidden" id="time"  name="time"
                                                                        value="10">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="OCVTbl" class="form-control" name="OCVTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="CCATbl" class="form-control" name="CCATbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="TempTbl" class="form-control" name="TempTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="SGTbl" class="form-control" name="SGTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="IRTbl" class="form-control" name="IRTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'><h6>15 mins</h6>
                                                                    <input type="hidden" id="time"  name="time"
                                                                        value="15">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="OCVTbl" class="form-control" name="OCVTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="CCATbl" class="form-control" name="CCATbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="TempTbl" class="form-control" name="TempTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="SGTbl" class="form-control" name="SGTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="IRTbl" class="form-control" name="IRTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'><h6>20 mins</h6>
                                                                    <input type="hidden" id="time"  name="time"
                                                                        value="20">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="OCVTbl" class="form-control" name="OCVTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="CCATbl" class="form-control" name="CCATbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="TempTbl" class="form-control" name="TempTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="SGTbl" class="form-control" name="SGTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="IRTbl" class="form-control" name="IRTbl"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Weight after activation, kg</h6></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4"></div>
                                                        <div class="col-md-4">
                                                            <input type="number" class="form-control" id="WeightAfterActivation">
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                </div>

                                                <div class="container" id="MF_Div">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Weight, kg</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="number" id="MF_weight" class="form-control" name="MF_weight"
                                                                        placeholder="">
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>OCV, V</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="number" id="MF_OCV" class="form-control" name="MF_OCV"
                                                                        placeholder="">
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>IR, mΩ</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="number" id="MF_IR" class="form-control" name="MF_IR"
                                                                        placeholder="">
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Midtronics CCA, A</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="number" id="MF_CCA" class="form-control" name="MF_CCA"
                                                                        placeholder="">
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="divider divider-center">
                                                        <div class="divider-text text-primary mt-3"><h6>ACID / Temp., °C</h6></div>
                                                        </div>
                                                        <div class="row row-cell">
                                                            <div class="col-md-2"></div>
                                                            <div class="col-md-2">
                                                                <label>Cell 1</label>
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="hidden" class="CellId" value="1">
                                                                <input type="number" id="Cell_SG" class="form-control Cell_SG" name="Cell_SG"
                                                                    placeholder="SG">
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="number" id="Cell_Temp" class="form-control Cell_Temp" name="Cell_Temp"
                                                                    placeholder="°C">
                                                            </div>
                                                            <div class="col-md-2"></div>
                                                        </div>

                                                        <div class="row row-cell">
                                                            <div class="col-md-2"></div>
                                                            <div class="col-md-2">
                                                                <label>Cell 2</label>
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                            <input type="hidden" class="CellId" value="2">
                                                                <input type="number" id="Cell2_SG" class="form-control Cell_SG" name="Cell2_SG"
                                                                    placeholder="SG">
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="number" id="Cell2_Temp" class="form-control Cell_Temp" name="Cell2_Temp"
                                                                    placeholder="°C">
                                                            </div>
                                                            <div class="col-md-2"></div>
                                                        </div>

                                                        <div class="row row-cell">
                                                            <div class="col-md-2"></div>
                                                            <div class="col-md-2">
                                                                <label>Cell 3</label>
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="hidden" class="CellId" value="3">
                                                                <input type="number" id="Cell3_SG" class="form-control Cell_SG" name="Cell3_SG"
                                                                    placeholder="SG">
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="number" id="Cell3_Temp" class="form-control Cell_Temp" name="Cell3_Temp"
                                                                    placeholder="°C">
                                                            </div>
                                                            <div class="col-md-2"></div>
                                                        </div>

                                                        <div class="row row-cell">
                                                            <div class="col-md-2"></div>
                                                            <div class="col-md-2">
                                                                <label>Cell 4</label>
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="hidden" class="CellId" value="4">
                                                                <input type="number" id="Cell4_SG" class="form-control Cell_SG" name="Cell4_SG"
                                                                    placeholder="SG">
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="number" id="Cell4_Temp" class="form-control Cell_Temp" name="Cell4_Temp"
                                                                    placeholder="°C">
                                                            </div>
                                                            <div class="col-md-2"></div>
                                                        </div>

                                                        <div class="row row-cell">
                                                            <div class="col-md-2"></div>
                                                            <div class="col-md-2">
                                                                <label>Cell 5</label>
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="hidden" class="CellId" value="5">
                                                                <input type="number" id="Cell5_SG" class="form-control Cell_SG" name="Cell5_SG"
                                                                    placeholder="SG">
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="number" id="Cell5_Temp" class="form-control Cell_Temp" name="Cell5_Temp"
                                                                    placeholder="°C">
                                                            </div>
                                                            <div class="col-md-2"></div>
                                                        </div>

                                                        <div class="row row-cell">
                                                            <div class="col-md-2"></div>
                                                            <div class="col-md-2">
                                                                <label>Cell 6</label>
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="hidden" class="CellId" value="6">
                                                                <input type="number" id="Cell6_SG" class="form-control Cell_SG" name="Cell6_SG"
                                                                    placeholder="SG">
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <input type="number" id="Cell6_Temp" class="form-control Cell_Temp" name="Cell6_Temp"
                                                                    placeholder="°C">
                                                            </div>
                                                            <div class="col-md-2"></div>
                                                        </div>
        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container text-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-primary ml-1" id="SubmitInitialData">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TestDataInputFormCapacity" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTestTitle">---
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container testFormDivCapacity">
                                                    
                                                </div>
                                                
                                            </div>
                                            <div class="container text-center" id="CapacityActionBtn">
                                                <div id="CapacitySubmitDiv">
                                                    
                                                </div>
                                                
                                                <div id="CapacityReviewDiv">
                                                    
                                                </div>
                                                
                                                <div id="CapacityApprovalDiv">
                                                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary ml-1" id="ReviewCapacityTestForm">
                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Approve</span>
                                                    </button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TestDataInputFormHRDT" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTestTitleHRDT">---
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container testFormDivHRDT">
                                                    
                                                </div>
                                                
                                            </div>
                                            <div class="container text-center" id="HRDTActionBtn">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <!--VT, WCT, HLET, LLET, DOD 17.5% and DOD 50% -->

                        <div class="modal fade text-left" id="TestDataInputFormVT" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTestTitleVT">---
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container testFormDivVT">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Battery No.</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataBatNoVT" class="form-control font-bold" name="TestDataBatNo"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Testing Date</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataTestDateVT" class="form-control font-bold" name="TestDataTestDate"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Test Parameters and Equipment</h6></div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Test Type</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Acceleration, g</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTAcceleration" class="form-control" name="TestDataVTAcceleration"
                                                                        >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Duration, hrs</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="DurationHrs" class="form-control" name="DurationHrs"
                                                                    >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Discharge current, A</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTDischargeA" class="form-control" name="TestDataVTDischargeA"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Post-vibration test</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataPostVT" class="form-control" name="TestDataPostVT"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Observation during Test*</h6></div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <div class="p-2">
                                                            <h6 class="text-center float-start">Add</h6>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn btn-outline-primary btn-sm float-end" id="ShowAddTestObservationBtn"><i class="fa fa-plus-circle"></i></button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive mt-4">
                                                        <table class="table table-bordered mb-0" id="initialMeasureTbl">
                                                            <thead>
                                                                <tr>
                                                                    <th style='white-space:nowrap;'>Acceleration</th>
                                                                    <th style='white-space:nowrap;'>Hours/Mins</th>
                                                                    <th style='white-space:nowrap;'>DCH Current</th>
                                                                    <th style='white-space:nowrap;'>Hertz</th>
                                                                    <th style='white-space:nowrap;'>VT Meter</th>
                                                                    <th style='white-space:nowrap;'>Voltage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr> -->
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="mt-4">
                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">*</span> During vibration test, check the battery for spillage of electrolyte. For JIS vibration test, check also for voltage fluctuations.</p>
                                                    </div>
                                                    
                                                    <div class="row mt-5">
                                                        <div class="col-md-12">
                                                            <label>Prepared by: </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row text-center" style="position:relative; margin-left:0.5px;">
                                                        <div class="col-md-4" style="border-bottom: solid 1px black">
                                                            <label >Leomar Unica  </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="container text-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-primary ml-1" id="SubmitTestForm">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TestDataInputFormWCT" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTestTitleWCT">Testing Form: WATER CONSUMPTION / ELECTROLYTE REDUCTION
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container testFormDivVT">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Battery No.</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataBatNoVT" class="form-control font-bold" name="TestDataBatNo"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Testing Date</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataTestDateVT" class="form-control font-bold" name="TestDataTestDate"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Test Parameters and Equipment</h6></div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Test Standard</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Charging Voltage, V</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="number" id="TestDataVTAcceleration" class="form-control" name="TestDataVTAcceleration"
                                                                        >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Water Bath Temp, ºC</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="DurationHrs" class="form-control" name="DurationHrs"
                                                                    >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Duration, days</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="DurationHrs" class="form-control" name="DurationHrs"
                                                                    >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Circuit No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTDischargeA" class="form-control" name="TestDataVTDischargeA"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Water Bath No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataPostVT" class="form-control" name="TestDataPostVT"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Test Program</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataPostVT" class="form-control" name="TestDataPostVT"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Test Results</h6></div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <label class="superscript">Battery weight before test, kg<span>1</span></label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <label class="superscript">Battery weight intermediate, kg<span>1,2</span></label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <label class="superscript">Battery weight after test, kg<span>1</span></label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <label>Data file name/s</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="mt-4">
                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">1</span> Before weighing the battery, make sure that all its surfaces are totally clean and dry. The weighing scale to be used should have an accuracy of at least ±0.001 kg if the battery weight is < 30 kg and at least ±0.005 kg if the battery weight is ≥ 30 kg.</p>

                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">2</span> For EN standard requirement level 4 (14.40 V, 60ºC, 42 days), disconnect, clean, and weigh the battery halfway through the test (after 21 days).</p>
                                                    </div>
                                                    
                                                    <div class="row mt-5">
                                                        <div class="col-md-12">
                                                            <label>Prepared by: </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row text-center" style="position:relative; margin-left:0.5px;">
                                                        <div class="col-md-4" style="border-bottom: solid 1px black">
                                                            <label >Leomar Unica  </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="container text-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-primary ml-1" id="SubmitTestForm">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TestDataInputFormVT_HLET" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTestTitleHLET">Testing Form: JIS HEAVY LOAD ENDURANCE TEST
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container testFormDivVT">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Battery No.</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataBatNoVT" class="form-control font-bold" name="TestDataBatNo"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Testing Date</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataTestDateVT" class="form-control font-bold" name="TestDataTestDate"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Test Parameters and Equipment</h6></div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Circuit No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Water Bath No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTAcceleration" class="form-control" name="TestDataVTAcceleration"
                                                                        >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Water Bath Temp, ºC</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="DurationHrs" class="form-control" name="DurationHrs"
                                                                    >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>HRD current, A</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTDischargeA" class="form-control" name="TestDataVTDischargeA"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Data File Name</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="TestDataPostVT" class="form-control" name="TestDataPostVT"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>State of Health Check (10.2V Test)</h6></div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <div class="p-2">
                                                            <h6 class="text-center float-start">Add</h6>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn btn-outline-primary btn-sm float-end" id="ShowAddHLETBtn"><i class="fa fa-plus-circle"></i></button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive mt-4">
                                                        <table class="table table-bordered mb-0" id="HLET_Tbl">
                                                            <thead>
                                                                <tr>
                                                                    <th style='white-space:nowrap;'>Week No.</th>
                                                                    <th style='white-space:nowrap;'>Checking Date</th>
                                                                    <th style='white-space:nowrap;'>DCH Time</th>
                                                                    <th style='white-space:nowrap;'>Computed AH</th>
                                                                    <th style='white-space:nowrap;'>Top-up Demi (ml)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr> -->
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="mt-4">
                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">1</span> Testing should be stopped when the 10.2 voltage during state-of-health checks falls below 40% of Ah.</p>

                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">2</span> For low-maintenance / conventional batteries, the electrolyte level of each cell should be checked after the high rate discharge test and the cells that have low electrolyte level should be topped up with purified water. The amount of water used for top-up should be recorded. <u>For maintenance-free batteries, the electrolyte should NOT be topped up.</u></p>
                                                    </div>
                                                    
                                                    <div class="row mt-5">
                                                        <div class="col-md-12">
                                                            <label>Prepared by: </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row text-center" style="position:relative; margin-left:0.5px;">
                                                        <div class="col-md-4" style="border-bottom: solid 1px black">
                                                            <label >Leomar Unica  </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="container text-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-primary ml-1" id="SubmitTestForm">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TestDataInputFormVT_LLET" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTestTitleHLET">Testing Form: JIS LIGHT LOAD ENDURANCE TEST
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container testFormDivVT">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Battery No.</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataBatNoVT" class="form-control font-bold" name="TestDataBatNo"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Testing Date</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataTestDateVT" class="form-control font-bold" name="TestDataTestDate"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Test Parameters and Equipment</h6></div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Circuit No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Water Bath No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTAcceleration" class="form-control" name="TestDataVTAcceleration"
                                                                        >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Water Bath Temp, ºC</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="DurationHrs" class="form-control" name="DurationHrs"
                                                                    >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>HRD current, A</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTDischargeA" class="form-control" name="TestDataVTDischargeA"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Data File Name</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="TestDataPostVT" class="form-control" name="TestDataPostVT"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>State of Health Check (10.2V Test)</h6></div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <div class="p-2">
                                                            <h6 class="text-center float-start">Add</h6>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn btn-outline-primary btn-sm float-end" id="ShowAddLLETBtn"><i class="fa fa-plus-circle"></i></button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive mt-4">
                                                        <table class="table table-bordered mb-0" id="HLET_Tbl">
                                                            <thead>
                                                                <tr>
                                                                    <th style='white-space:nowrap;'>Week No.</th>
                                                                    <th style='white-space:nowrap;'>Checking Date</th>
                                                                    <th style='white-space:nowrap;'>V, 30s</th>
                                                                    <th style='white-space:nowrap;'>Top-up Water, mL</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr> -->
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="mt-4">
                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">1</span> Testing should be stopped when the 30-s voltage during state-of-health checks falls below 7.2 V.</p>

                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">2</span> For low-maintenance / conventional batteries, the electrolyte level of each cell should be checked after the high rate discharge test and the cells that have low electrolyte level should be topped up with purified water. The amount of water used for top-up should be recorded. <u>For maintenance-free batteries, the electrolyte should NOT be topped up.</u></p>
                                                    </div>
                                                    
                                                    <div class="row mt-5">
                                                        <div class="col-md-12">
                                                            <label>Prepared by: </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row text-center" style="position:relative; margin-left:0.5px;">
                                                        <div class="col-md-4" style="border-bottom: solid 1px black">
                                                            <label >Leomar Unica  </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="container text-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-primary ml-1" id="SubmitTestForm">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TestDataInputFormVTDOD17p" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTestTitleHLET">Testing Form: EN DEPTH OF DISCHARGE 17.5% TEST
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container testFormDivVT">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Battery No.</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataBatNoVT" class="form-control font-bold" name="TestDataBatNo"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Testing Date</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataTestDateVT" class="form-control font-bold" name="TestDataTestDate"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Test Parameters and Equipment</h6></div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Circuit No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Water Bath No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTAcceleration" class="form-control" name="TestDataVTAcceleration"
                                                                        >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Water Bath Temp, ºC</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="DurationHrs" class="form-control" name="DurationHrs"
                                                                    >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>HRD current, A</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTDischargeA" class="form-control" name="TestDataVTDischargeA"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Data File Name</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="TestDataPostVT" class="form-control" name="TestDataPostVT"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>State of Health Check (HRD Test)</h6></div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <div class="p-2">
                                                            <h6 class="text-center float-start">Add</h6>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn btn-outline-primary btn-sm float-end" id="ShowAddDOD17p5Btn"><i class="fa fa-plus-circle"></i></button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive mt-4">
                                                        <table class="table table-bordered mb-0" id="HLET_Tbl">
                                                            <thead>
                                                                <tr>
                                                                    <th style='white-space:nowrap;'>Week No.</th>
                                                                    <th style='white-space:nowrap;'>Checking Date</th>
                                                                    <th style='white-space:nowrap;'>V, 30s</th>
                                                                    <th style='white-space:nowrap;'>Top-up Water, mL</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr> -->
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="mt-4">
                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">1</span> Testing should be stopped when the voltage during state-of-health checks falls below 10 V.</p>

                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">2</span> For low-maintenance / conventional batteries, the electrolyte level of each cell should be checked after the high rate discharge test and the cells that have low electrolyte level should be topped up with purified water. The amount of water used for top-up should be recorded. <u>For maintenance-free batteries, the electrolyte should NOT be topped up.</u></p>
                                                    </div>
                                                    
                                                    <div class="row mt-5">
                                                        <div class="col-md-12">
                                                            <label>Prepared by: </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row text-center" style="position:relative; margin-left:0.5px;">
                                                        <div class="col-md-4" style="border-bottom: solid 1px black">
                                                            <label >Leomar Unica  </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="container text-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-primary ml-1" id="SubmitTestForm">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="TestDataInputFormVT_DOD50" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTestTitleHLET">Testing Form: EN DEPTH OF DISCHARGE 50 % TEST
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container testFormDivVT">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Battery No.</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataBatNoVT" class="form-control font-bold" name="TestDataBatNo"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Testing Date</label>
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <input type="text" id="TestDataTestDateVT" class="form-control font-bold" name="TestDataTestDate"
                                                                        disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>Test Parameters and Equipment</h6></div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Circuit No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="TestDataTestTypeVT" class="form-control" name="TestDataTestType"
                                                                        disabled>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label>Water Bath No.</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTAcceleration" class="form-control" name="TestDataVTAcceleration"
                                                                        >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Water Bath Temp, ºC</label>
                                                                </div>
                                                                <div class="col-md-4 form-group">
                                                                    <input type="text" id="DurationHrs" class="form-control" name="DurationHrs"
                                                                    >
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>HRD current, A</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="number" id="TestDataVTDischargeA" class="form-control" name="TestDataVTDischargeA"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label>Data File Name</label>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <input type="text" id="TestDataPostVT" class="form-control" name="TestDataPostVT"
                                                                        placeholder="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider divider-center">
                                                        <div class="divider-text text-primary"><h6>State of Health Check (HRD Test)</h6></div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <div class="p-2">
                                                            <h6 class="text-center float-start">Add</h6>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn btn-outline-primary btn-sm float-end" id="ShowAddDOD50Btn"><i class="fa fa-plus-circle"></i></button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive mt-4">
                                                        <table class="table table-bordered mb-0" id="HLET_Tbl">
                                                            <thead>
                                                                <tr>
                                                                    <th style='white-space:nowrap;'>Cycle No. (120D)</th>
                                                                    <th style='white-space:nowrap;'>Checking Date</th>
                                                                    <th style='white-space:nowrap;'>V, 30s</th>
                                                                    <th style='white-space:nowrap;'>Computed Ah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                    <td class="text-bold-500" style='white-space:nowrap;'>
                                                                        <input type="text" id="TestDataPreCapacityOCV" class="form-control" name="TestDataPreCapacityOCV"
                                                                        placeholder="">
                                                                    </td>
                                                                </tr> -->
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="mt-4">
                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">1</span> Testing should be stopped when the 30-s voltage during state-of-health checks falls below 7.2 V.</p>

                                                        <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">2</span> For low-maintenance / conventional batteries, the electrolyte level of each cell should be checked after the high rate discharge test and the cells that have low electrolyte level should be topped up with purified water. The amount of water used for top-up should be recorded. <u>For maintenance-free batteries, the electrolyte should NOT be topped up.</u></p>
                                                    </div>
                                                    
                                                    <div class="row mt-5">
                                                        <div class="col-md-12">
                                                            <label>Prepared by: </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row text-center" style="position:relative; margin-left:0.5px;">
                                                        <div class="col-md-4" style="border-bottom: solid 1px black">
                                                            <label >Leomar Unica  </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="container text-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-primary ml-1" id="SubmitTestForm">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <!--VT, WCT, HLET, LLET, DOD 17.5% and DOD 50% End-->

                        <div class="modal fade text-left" id="TestForms" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="textTitle">Test Forms
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label>Battery Number: </label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <label class="font-bold">PD2401-0351 </label>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label>Battery Type: </label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <label class="font-bold">WUBD31FM-CPN02-E </label>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label>Project Name: </label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <label class="font-bold">Product Audit </label>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label>Requisitioner: </label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <label class="font-bold">MC / JC </label>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label>Testing: </label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <label class="font-bold">M Series 9 </label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="card-body">
                                                <div class="divider divider-center">
                                                    <div class="divider-text text-primary mt-3">
                                                        <h6>Test Data</h6>
                                                    </div>
                                                </div>

                                                <div class="container-fluid">
                                                    <input type="radio" class="btn-check btn_test" name="options-outlined2" value="1" id="primary-outlined_test1"
                                                            autocomplete="off" checked>
                                                    <label class="btn btn-outline-secondary" for="primary-outlined_test1">Initial Measurement</label>

                                                    <input type="radio" class="btn-check btn_test" name="options-outlined2" value="2" id="primary-outlined_test2"
                                                        autocomplete="off">
                                                    <label class="btn btn-outline-primary" for="primary-outlined_test2">C20</label>

                                                    <input type="radio" class="btn-check btn_test" name="options-outlined2" value="3" id="primary-outlined_test3"
                                                        autocomplete="off">
                                                    <label class="btn btn-outline-primary" for="primary-outlined_test3">C10</label>

                                                    <!-- <input type="radio" class="btn-check btn_test" value="3" id="primary-outlined4"
                                                        autocomplete="off">
                                                    <label class="btn btn-outline-primary" for="primary-outlined4">Initial Measurement</label> -->

                                                    <input type="radio" class="btn-check btn_test" name="options-outlined2" value="4" id="primary-outlined_test4"
                                                        autocomplete="off">
                                                    <label class="btn btn-outline-primary" for="primary-outlined_test4">Send to TDL for Teardown</label>

                                                    <input type="radio" class="btn-check btn_test" name="options-outlined2" value="5" id="primary-outlined_test5"
                                                        autocomplete="off">
                                                    <label class="btn btn-outline-primary" for="primary-outlined_test5">Generate Report</label>

                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <!-- <button type="button" class="btn btn-primary ml-1" id="SubmitInitialData">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button> -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="ViewSamples" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">View Sample Label
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <div class="container rounded-rectangle">
                                                    <!-- <p class="text-center" style="line-height: 1.2;">Battery Testing Laboratory Section</p> -->
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="printSamples">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Print</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="CircuitMappingModal" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelReview">Circuit WaterBath
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="text-center">
                                                    <h6 id="testSamplesText"></h6>
                                                    <input type="hidden" id="MapCellID">
                                                    <input type="hidden" id="MapStatusCellID">
                                                </div>
                                            </div>
                                            <div class="card-body" id="mappingDiv">
                                               
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-danger ml-1" id="MapBtn">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Defective</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="legendPrioritization" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-1">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Prioritization</h4>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="container text-center mb-3 mt-3">
                                                    <h5 id="selectedFormTitle"></h5>
                                                </div>

                                                <div class="container">
                                                    <span>
                                                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                                        High
                                                    </span><br>
                                                    <span>
                                                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                                                        Mid
                                                    </span><br>
                                                    <span>
                                                        <i class="bi bi-exclamation-triangle-fill text-info"></i>
                                                        Low
                                                    </span>
                                                </div>

                                                <div class="container text-center mt-3">
                                                    <button type="button" class="btn btn-sm btn-light-secondary"  data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <!--VT Modal Forms -->
                        <div class="modal fade text-left" id="VTAddObservationTestForm" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Test Observation
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="acceleration-icon">Acceleration</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control" placeholder="Acceleration"
                                                                            id="acceleration-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-speedometer2"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="hoursMin-icon">Hours/Mins</label>
                                                                    <div class="position-relative">
                                                                        <input type="time" class="form-control"
                                                                            placeholder="Hours/Mins" id="hoursMin-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-alarm"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="dch-icon">DCH Current</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="DCH Current" id="dch-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning-charge-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="hertz-icon">Hertz</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Hertz" id="hertz-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="VTMeter-icon">VT Meter</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="VT Meter" id="VTMeter-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-speedometer"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="voltage10-icon">Voltage</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Voltage" id="voltage10-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-battery-charging"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="DischargeProfileSave">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--VT Modal Forms end-->

                        <!--HLET Modal Forms -->
                        <div class="modal fade text-left" id="HLETTestForm" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Test State of Health Check
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="acceleration-icon">Week No.</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control" placeholder="Week No."
                                                                            id="acceleration-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-calendar2-week"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="hoursMin-icon">Checking Date</label>
                                                                    <div class="position-relative">
                                                                        <input type="date" class="form-control"
                                                                            placeholder="Hours/Mins" id="hoursMin-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-calendar-check"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="dch-icon">DCH Time</label>
                                                                    <div class="position-relative">
                                                                        <input type="time" class="form-control"
                                                                            placeholder="DCH Current" id="dch-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-alarm"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="hertz-icon">Computed AH</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="AH" id="hertz-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="VTMeter-icon">Top-Up Demi (ml)</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Top-up Demi (ml)" id="VTMeter-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-droplet-half"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="DischargeProfileSave">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--HLET Modal Forms end-->

                        <!--LLET Modal Forms -->
                        <div class="modal fade text-left" id="LLETTestForm" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Test State of Health Check (HRD Test)
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="acceleration-icon">Week No.</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control" placeholder="Week No."
                                                                            id="acceleration-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-calendar2-week"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="hoursMin-icon">Checking Date</label>
                                                                    <div class="position-relative">
                                                                        <input type="date" class="form-control"
                                                                            placeholder="Hours/Mins" id="hoursMin-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-calendar-check"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="dch-icon">V 30s</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control"
                                                                            placeholder="V 30s" id="dch-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="VTMeter-icon">Top-Up Water, mL</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Top-Up Water, mL" id="VTMeter-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-droplet-half"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="DischargeProfileSave">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--LLET Modal Forms end-->

                        <!--DOD17p5 Modal Forms -->
                        <div class="modal fade text-left" id="DOD17p5TestForm" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Test State of Health Check (HRD Test)
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="acceleration-icon">Week No.</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control" placeholder="Week No."
                                                                            id="acceleration-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-calendar2-week"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="hoursMin-icon">Checking Date</label>
                                                                    <div class="position-relative">
                                                                        <input type="date" class="form-control"
                                                                            placeholder="Hours/Mins" id="hoursMin-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-calendar-check"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="dch-icon">V 30s</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control"
                                                                            placeholder="V 30s" id="dch-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="VTMeter-icon">Top-Up Water, mL</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Top-Up Water, mL" id="VTMeter-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-droplet-half"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="DischargeProfileSave">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--DOD17p5 Modal Forms end-->

                        <!--DOD50 Modal Forms -->
                        <div class="modal fade text-left" id="DOD50TestForm" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Test State of Health Check (HRD Test)
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="acceleration-icon">Cycle No.</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control" placeholder="Cycle No."
                                                                            id="acceleration-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-arrow-repeat"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="hoursMin-icon">Checking Date</label>
                                                                    <div class="position-relative">
                                                                        <input type="date" class="form-control"
                                                                            placeholder="Hours/Mins" id="hoursMin-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-calendar-check"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="dch-icon">V 30s</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control"
                                                                            placeholder="V 30s" id="dch-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="VTMeter-icon">Computed Ah</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control"
                                                                            placeholder="Computed Ah" id="VTMeter-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="DischargeProfileSave">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--DOD50 Modal Forms end-->

                        <!--HRDT Modal forms -->
                        <div class="modal fade text-left" id="HRDTDischargeProfile" tabindex="-1" role="dialog"
                            aria-labelledby="HRDTDischargeProfile" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Discharge Profile
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="HRDTTestPTPScheduleIDShowProfile">
                                                <input type="hidden" id="HRDTStatusIDDischargeProfile">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="HRDTCurrentShowProfiles">Discharge Current, A</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control"
                                                                            placeholder="Discharge Current" id="HRDTCurrentShowProfiles">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning-charge-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="seconds-icon">Seconds</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control" placeholder="ss"
                                                                                id="seconds-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-clock"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="form-group has-icon-left">
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <label for="hour-icon">Hour</label>
                                                                            <div class="position-relative">
                                                                                <input type="number" class="form-control" placeholder="hh"
                                                                                        id="hour-icon">
                                                                                <div class="form-control-icon">
                                                                                    <i class="bi bi-clock"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <label for="minutes-icon">Minutes</label>
                                                                            <div class="position-relative">
                                                                                <input type="number" class="form-control" placeholder="mm"
                                                                                        id="minutes-icon">
                                                                                <div class="form-control-icon">
                                                                                    <i class="bi bi-clock"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <label for="seconds-icon">Seconds</label>
                                                                            <div class="position-relative">
                                                                                <input type="number" class="form-control" placeholder="ss"
                                                                                        id="seconds-icon">
                                                                                <div class="form-control-icon">
                                                                                    <i class="bi bi-clock"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div> -->
                                                            </div>
                                                            <!-- <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="ComputedHRDTMinutes">Computed Minutes</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control"
                                                                            placeholder="Calculated Minutes" id="ComputedHRDTMinutes" style="border:2px solid grey; font-weight:bold;" disabled>
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-calculator"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="HRDTDischargeProfileBtnSave">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="HRDTTestResultForm" tabindex="-1" role="dialog"
                            aria-labelledby="HRDTTestResultForm" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Test Results
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="HRDTTestPTPScheduleIDTestResult">
                                                <input type="hidden" id="HRDTStatusIDTestResult">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="VoltageSeconds-icon">Time, s</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control" placeholder="ss"
                                                                            id="time-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-clock"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="voltage-icon">Battery Voltage, V</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Battery Voltage" id="voltage-icon">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-battery-charging"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="TestResultBtnSave">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--HRDT Modal forms end-->
                        <!--HRDT Modal Edit forms -->
                        <div class="modal fade text-left" id="HRDTDischargeProfileEdit" tabindex="-1" role="dialog"
                            aria-labelledby="HRDTDischargeProfile" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Discharge Profile
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="HRDTTestPTPScheduleIDShowProfile">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="HRDTCurrentShowProfiles">Discharge Current, A</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control"
                                                                            placeholder="Discharge Current" id="HRDTCurrentShowProfilesEdit">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-lightning-charge-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="seconds-icon">Seconds</label>
                                                                    <div class="position-relative">
                                                                        <input type="number" class="form-control" placeholder="ss"
                                                                                id="seconds-iconEdit">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-clock"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="HRDTDischargeProfileBtnUpdate">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Update</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="HRDTTestResultFormEdit" tabindex="-1" role="dialog"
                            aria-labelledby="HRDTTestResultForm" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Test Results
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="HRDTTestPTPScheduleIDTestResult">
                                                <form class="form form-vertical">
                                                    <div class="form-body">
                                                        <div class="row">

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="VoltageSeconds-icon">Time, s</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control" placeholder="ss"
                                                                            id="time-iconEdit">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-clock"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="voltage-icon">Battery Voltage, V</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Battery Voltage" id="voltage-iconEdit">
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-battery-charging"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="TestResultBtnUpdate">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Update</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--HRDT Modal forms end-->

                        <div class="modal fade text-left" id="RejectModal" tabindex="-1" role="dialog"
                            aria-labelledby="HRDTTestResultForm" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body" style="overflow:hidden;">
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="RejectTestDataInputID">
                                                <input type="hidden" id="RejectPTPTestDataInputID">
                                                <input type="hidden" id="RejectFormCategoryID">
                                                <div class="form-group mb-3 ">
                                                    <label for="RejectRemarks" class="form-label">Remarks</label>
                                                    <textarea class="form-control" id="RejectRemarks" placeholder="Reject Remarks" rows="2"></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button class="btn btn-light btn-block btn-md" id="Select_Reject_ChangeData">
                                                        <i class="bi bi-arrow-left-right"></i> Change Data
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button class="btn btn-outline-warning btn-block btn-md" id="Select_Reject_Retest">
                                                        <i class="bi bi-bootstrap-reboot"></i> Retest
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer"> 
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="TestResultBtnSave">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Proceed</span>
                                            </button>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <footer>
                            <div class="footer clearfix mb-0 text-muted">
                                <div class="float-start">
                                    <p>2023 &copy; Philippine Batteries, Inc.</p>
                                </div>
                                <div class="float-end">
                                    <p>MOTOLITE</p>
                                </div>
                            </div>
                        </footer>
                        
                    </div>
            </div>
        </div>
    </div>
    <div id="qr" class="d-none"></div>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/app.js"></script>

    <script src="../assets/extensions/sweetalert2/sweetalert2.min.js"></script>

    <script src="../assets_original/js/jquery.min.js"></script>
    <script src="../node_modules/frappe-gantt/dist/frappe-gantt.min.js"></script>
    
    <!--DataTable -->
    <script src="../assets_original/js/datatables.min.js"></script>
    <script src="../assets_original/js/dataTables.responsive.min.js"></script>
    <script src="../assets_original/js/dataTables.buttons.min.js"></script>
    <script src="../assets_original/js/jszip.min.js"></script>
    <script src="../assets_original/js/pdfmake.min.js"></script>
    <script src="../assets_original/js/vfs_fonts.js"></script>
    <script src="../assets_original/js/buttons.html5.min.js"></script>
    <script src="../assets_original/js/jquery.tabledit.min.js"></script>
    <!--DataTable END-->
    <script src="../assets_original/QR_generator/jquery.qrcode.min.js"></script>
    <script src="../assets_original/js/print.min.js"></script>

    <script src="../assets/extensions/choices.js/public/assets/scripts/choices.js"></script>

    <script src="../assets/js/pages/form-element-select.js"></script>
    <script src="../assets_original/js/draganddrop.js"></script>
    <script src="../node_modules/jsqr/dist/jsQR.js"></script>
    
    <script src="LabAnalystJs_repository/index_repo.js"></script>
</body>

</html>
