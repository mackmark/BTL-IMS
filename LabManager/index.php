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
    <title>BTL LIMS | Lab Manager</title>

    <link rel="stylesheet" href="../assets/css/main/app.css">
    <link rel="stylesheet" href="../assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/png">

    <link rel="stylesheet" href="../assets/extensions/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="../assets_original/css/datatables.min.css"/>

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="../assets_original/fonts/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/shared/iconly.css">

    <style>
        .tooltip {
            background-color: #333;
            color: #fff;
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
            margin: 0.2px; /*the margin between cells as needed*/
        }

        .cell-bitrode{
            width: 75px; /* the width and height as needed */
            height: 25px;
            background-color: #FFFFFF; /* Change the background color as desired */
            border: 2px solid #445069; /* Change the border color as desired */
            margin: 0.2px; /*the margin between cells as needed*/
        }

        .cell-divider{
            width: 75px; /* the width and height as needed */
            height: 5px;
            background-color: #445069; /* Change the background color as desired */
            border: 2px solid #445069; /* Change the border color as desired */
            margin: 0.2px; /*the margin between cells as needed*/
        }

        .cell-allocate{
            width: 175px; /* the width and height as needed */
            height: 120px;
            background-color: #FFFFFF; /* Change the background color as desired */
            border: 4px solid #445069; /* Change the border color as desired */
            margin: 0.2px; /*the margin between cells as needed*/
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
                            <a href="" class='sidebar-link' onclick="loadContent(1)">
                                <i class="bi bi-grid-fill"></i>
                                <span>Manage BTR</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="" class='sidebar-link' onclick="loadContent(2)" >
                                <i class="bi bi-stack"></i>
                                <span>Circuit Map</span>
                            </a>
                        </li>
                        
                        <!-- <li class="sidebar-item  has-sub"> -->
                        <li class="sidebar-item">
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
                                        <h6 class="dropdown-header">Hello, <?php echo $_COOKIE['BTL_FirstName']; ?></h6>
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
                    <section class="row" id ="cardStat">
                        <div class="col-12 col-lg-8">
                            <div class="row">
                                <div class="col-4 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                    <div class="stats-icon purple mb-2">
                                                        <i class="iconly-boldScan"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">ON-GOING</h6>
                                                    <h3 class="font-extrabold mb-0">120</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                    <div class="stats-icon blue mb-2">
                                                        <i class="iconly-boldDanger"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">HOLD</h6>
                                                    <h3 class="font-extrabold mb-0">30</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                    <div class="stats-icon green mb-2">
                                                        <i class="iconly-boldSwap"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">CIRCUIT</h6>
                                                    <h3 class="font-extrabold mb-0">90 %</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start ">
                                            <div class="stats-icon red mb-2">
                                                <i class="iconly-boldBookmark"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9  ">
                                            <h6 class="text-muted font-semibold float-start">TIMELINESS</h6>
                                            <h6 class="font-extrabold mb-0 float-start">In terms of test report release</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                    <section class="section position-relative" id="BtrCard">
                        <div class="card">
                            <div class="card-header">
                                <div class="container-fluid">
                                    <h4 class="card-title float-start">Manage Battery Testing Request</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Table with no outer spacing -->
                                <div class="divider divider-left">
                                    <div class="divider-text text-primary">Request Summary</div>
                                </div>
                                <div class="container-fluid">
                                    <input type="radio" class="btn-check" name="options-outlined" value="2" id="primary-outlined"
                                        autocomplete="off" checked>
                                    <label class="btn btn-outline-primary" for="primary-outlined">Battery Test Request</label>
                                    
                                    <input type="radio" class="btn-check" name="options-outlined" value="1" id="primary-outlined2"
                                        autocomplete="off">
                                    <label class="btn btn-outline-primary" for="primary-outlined2">On-going Tests </label>
                                </div>
                                
                                <div id="ongoing">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-hover" id="Ongoing_tbl">
                                            <thead style="font-size:14px;">
                                                <tr>
                                                    <th class="text-center">Test ID</th>
                                                    <th class="text-center">Sample ID</th>
                                                    <th class="text-center">Test Parameters</th>
                                                    <th class="text-center">Requestor</th>
                                                    <th class="text-center">Circuit</th>
                                                    <th class="text-center">Battery Code</th>
                                                    <th>Status</th>
                                                    <th>Date Received</th>
                                                    <th class="text-left">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size:13px;">

                                                <tr>
                                                    <td class="text-center">T-3006-001</td>
                                                    <td class="text-center">PD2306-001</td>
                                                    <td class="text-center">M-series 1</td>
                                                    <td class="text-center">MCCruz</td>
                                                    <td class="text-center">ADR1</td>
                                                    <td class="text-center">WF12345</td>
                                                    <td>
                                                        <div class="badges">
                                                            <span class="badge bg-light-success">On-going</span>
                                                        </div>
                                                    </td>
                                                    <td>August 23, 2023</td>
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
                                <div id="btr">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-hover" id="TestRequest_tbl">
                                            <thead style="font-size:14px;">
                                                <tr>
                                                    <th class="text-center" style="white-space:nowrap;">Request ID</th>
                                                    <th class="text-center">Requestor</th>
                                                    <th class="text-center" style="white-space:nowrap;">Requested Date</th>
                                                    <th class="text-center" style="white-space:nowrap;">Project Name</th>
                                                    <th class="text-center">Purpose</th>
                                                    <th class="text-center">Objective</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th>Requirement</th>
                                                    <th>Status</th>
                                                    <th class="text-left">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size:13px;"></tbody>
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

                    <div class="modal fade text-left" id="ApprovalModal"  tabindex="-1" role="dialog" aria-labelledby="myModalapprovalModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalapprovalModal">Battery Testing Request Approval</h4>
                                    <button type="button" class="close" id="ApproveModalClose"
                                        aria-label="Close">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="setterApproval">
                                    <input type="hidden" id="RequestId_holder">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="benchmarkQty" class="text-primary">Set Prioritization </label>
                                        </div>
                                        <div class="col-md-12 form-group">
                                        <select class="form-select" id="prioritization">
                                        </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-md-12">
                                                <label for="ApprovedRemarks" class="text-primary">Remarks </label>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <textarea id="ApprovedRemarks" class="form-control" name="ApprovedRemarks"
                                                    placeholder=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <div class="modal-footer">
                                    <div class="container text-center">
                                        <button type="button" class="btn btn-sm btn-light-secondary"
                                            id="ApproveModalClose2">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="BtrApproveBtn">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Approve</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade text-left" id="RevisionModal"  tabindex="-1" role="dialog" aria-labelledby="myModalapprovalModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalapprovalModal">Battery Testing Request Revision</h4>
                                    <button type="button" class="close" id="close_revision" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="RequestId_Revise_holder">
                                    <div class="row">
                                            <div class="col-md-12">
                                                <label for="RevisionBTR" class="text-primary">Remarks </label>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <textarea id="RevisionBTR" class="form-control" name="RevisionBTR"
                                                    placeholder=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <div class="modal-footer">
                                    <div class="container text-center">
                                        <button type="button" class="btn btn-sm btn-light-secondary" id="close_revision2"
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning ml-1" id="BtrRevisionBtn">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Submit</span>
                                        </button>
                                    </div>
                                </div>
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
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/app.js"></script>

    <script src="../assets/extensions/sweetalert2/sweetalert2.min.js"></script>

    <script src="../assets_original/js/jquery.min.js"></script>

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

    <script src="LabManagerJS_repository/index_repo.js"></script>
    
</body>

</html>



