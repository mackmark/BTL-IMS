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
    <title>BTL LIMS | Customer</title>
    <link rel="stylesheet" href="../assets/extensions/choices.js/public/assets/styles/choices.css">
    <link rel="stylesheet" href="../assets/css/main/app.css">
    <link rel="stylesheet" href="../assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/png">

    
    <link rel="stylesheet" href="../assets/extensions/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="../assets_original/css/datatables.min.css"/>
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="../assets_original/fonts/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/shared/iconly.css">

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
                            <a href="#" class='sidebar-link' onclick="loadContent('request.php')">
                                <i class="bi bi-grid-fill"></i>
                                <span>Battery Request</span>
                            </a>
                        </li>
                        
                        <!-- <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'  onclick="loadContent('request.php')">
                                <i class="bi bi-stack"></i>
                                <span>Test Result</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="#" onclick="loadContent('request.php')">Test Result Request</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="#" onclick="loadContent('request.php')">Battery Test Result</a>
                                </li>

                            </ul>
                        </li>

                        <li
                            class="sidebar-item  ">
                            <a href="#" class='sidebar-link'  onclick="loadContent('request.php')">
                                <i class="bi bi-collection-fill"></i>
                                <span>Services</span>
                            </a>
                        </li> -->

                        
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
                                <!-- <li class="nav-item dropdown me-1">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class='bi bi-envelope bi-sub fs-4'></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <h6 class="dropdown-header">Mail</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="#">No new mail</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class='bi bi-bell bi-sub fs-4'></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header">
                                            <h6>Notifications</h6>
                                        </li>
   
                                        <li>
                                            <p class="text-center py-2 mb-0"><a href="#">See all notification</a></p>
                                        </li>
                                    </ul>
                                </li> -->
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3 d-none d-sm-block">
                                            <h6 class="mb-0 text-gray-600"><?php echo $_COOKIE['BTL_AccountName']; ?></h6>
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
                                    <!-- <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-wallet me-2"></i>
                                            Wallet</a></li> -->
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
                        <section class="section position-relative">
                            <div class="card">
                                <div class="card-header">
                                    <div class="container-fluid">
                                        <h4 class="card-title float-start">Battery Request</h4>
                                        <a href="add_request.php" class="btn btn-md icon btn-primary float-end"><i class="bi bi-plus"></i> Add request</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Table with no outer spacing -->
                                    <div class="divider divider-left">
                                        <div class="divider-text text-primary">Data Summary</div>
                                    </div>
                                    <div class="table-responsive p-2">
                                        <table class="table" id="Request_tbl" style="width:100%;">
                                            <thead style="font-size:14px;">
                                                <tr>
                                                    <th class="text-center">Request ID</th>
                                                    <th class="text-center">Battery Code</th>
                                                    <th class="text-center">Project Name</th>
                                                    <th class="text-center">Test Objective</th>
                                                    <th>Status</th>
                                                    <th>Total Qty</th>
                                                    <th>Date Created</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center" style="font-size:13px;">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Report table managing modal [ADD, EDIT, DELETE] button block-->
                        <div class="modal fade text-left" id="EditRequestModal" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-xl"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModallabelReview">Edit Request
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <input type="hidden" id="RequestID_Holder">
                                                <form class="form form-horizontal" id="requestorDataForm">
                                                    <div class="form-body">
                                                        <div class="divider divider-center">
                                                            <div class="divider-text text-primary">DISPOSAL OF BATTERIES</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Disposition</label>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <div class='form-check'>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox" id="checkbox1" class='form-check-input disposal' value = "1"
                                                                        >
                                                                        <label for="checkbox2">For BTL junk after testing</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <div class='form-check'>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox" id="checkbox2" class='form-check-input disposal' value="2"
                                                                            >
                                                                        <label for="checkbox2">For Pick-Up after testing</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="divider divider-center">
                                                            <div class="divider-text text-primary">REQUISITION</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label for="requestor" class="mt-2">Requestor</label>
                                                            </div>
                                                            <div class="col-md-7 form-group">
                                                                <div class="badges" id="requestorBadges">
                                                                </div>

                                                                </select>
                                                                <span class="error text-danger" id="requestorerror"></span>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Project Name</label>
                                                            </div>
                                                            <div class="col-md-7 form-group">
                                                                <div class="badges" id="ProjectNameBadges">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Test Objective</label>
                                                            </div>
                                                            <div class="col-md-7 form-group">
                                                                <div class="badges" id="ObjectiveBadges">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="divider divider-center">
                                                            <div class="divider-text text-primary">BATTERY SAMPLE CLASSIFICATION</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Classification</label>
                                                            </div>
                                                            <div class="col-md-7 form-group">
                                                                <div class="badges" id="ClassificationBadges">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="divider divider-center">
                                                            <div class="divider-text text-primary">BATTERY SAMPLE DETAILS</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Production Code</label>
                                                            </div>
                                                            <div class="col-md-2 form-group">
                                                                <div class="badges" id="ProdCodeBadges">
                                                                </div>
                                                            </div>
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Brand</label>
                                                            </div>
                                                            <div class="col-md-2 form-group">
                                                                <div class="badges" id="BrandBadges">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Battery Type</label>
                                                            </div>
                                                            <div class="col-md-2 form-group">
                                                            <select class="form-select" id="EditBType">
                                                            </select>
                                                            </div>
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Application</label>
                                                            </div>
                                                            <div class="col-md-2 form-group">
                                                                <select class="form-select" id="EditApplication">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Battery Code</label>
                                                            </div>
                                                            <div class="col-md-2 form-group">
                                                                <div class="badges" id="BCodeBadges">
                                                                </div>
                                                            </div>
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Battery Size</label>
                                                            </div>
                                                            <div class="col-md-2 form-group">
                                                                <select class="form-select" id="EditBsize">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Plate Code <i class="bi bi-plus-circle text-danger"></i></label>
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <div class="badges" id="PositivePlateCodeBadges">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2 form-group">
                                                                <div class="badges" id="positivePlateQtyBadges">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Plate Code <i class="bi bi-dash-circle text-secondary"></i></label>
                                                            </div>
                                                            <div class="col-md-3 form-group">
                                                                <div class="badges" id="NegativePlateCodeBadges">
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-2">
                                                                <input type="text" id="negativePlateCode" class="form-control" name="negativePlateCode"
                                                                    placeholder="Plate Code">
                                                            </div> -->
                                                            <div class="col-md-2 form-group">
                                                                <div class="badges" id="negativePlateQtyBadges">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>RC Rating</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="badges" id="RCBadges">
                                                                </div>
                                                            </div>
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>AH Rating</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="badges" id="AHBadges">
                                                                </div>
                                                            </div>
                                                            <div class="col-3"></div>
                                                        </div>

                                                        <div class="row mt-2">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>CCA Rating</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="badges" id="CCABadges">
                                                                </div>
                                                            </div>
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>C5 Rating</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="badges" id="C5Badges">
                                                                </div>
                                                            </div>
                                                            <div class="col-3"></div>
                                                        </div>

                                                        <div class="row mt-2">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>CA Rating</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="badges" id="CABadges">
                                                                </div>
                                                            </div>
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>SG</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="badges" id="SGBadges">
                                                                </div>
                                                            </div>
                                                            <div class="col-3"></div>
                                                        </div>

                                                        <div class="row mt-2">
                                                            <div class="col-1"></div>
                                                            <div class="col-md-2">
                                                                <label>Others</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="badges" id="OthersBadges">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </form>

                                                <form class="form form-horizontal mt-5">
                                                    <div class="form-body">
                                                        <div class="divider divider-center">
                                                            <div class="divider-text text-primary">TEST PLANS</div>
                                                        </div>
                                                        <div class="container shadow-sm rounded p-2 bg-white mb-2">
                                                            <div class="row">
                                                                <div class="col-12 d-flex justify-content-between">
                                                                    <label class="mt-2"><span style="font-weight:bold;">Test Plan</label></br>
                                                                    <div class="btn-group mb-1">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle me-1" type="button"
                                                                                id="dropdownMenuButtonIcon" data-bs-toggle="dropdown" aria-haspopup="true"
                                                                                aria-expanded="false">
                                                                                <i class="bi bi-plus-circle me-50"></i> Add Test Plan
                                                                            </button>
                                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonIcon">
                                                                                <a class="dropdown-item" style="cursor:pointer;" onclick="modalPop('test1')"><i class="bi bi-plus-circle me-50"></i> M-Series</a>
                                                                                <a class="dropdown-item" style="cursor:pointer;" onclick="modalPop('test2')"><i class="bi bi-plus-circle me-50"></i> User test sequence</a>
                                                                                <a class="dropdown-item" style="cursor:pointer;" onclick="modalPop('test3')"><i class="bi bi-plus-circle me-50"></i> Select test option</a>
                                                                                <a class="dropdown-item" style="cursor:pointer;" onclick="modalPop('test4')"><i class="bi bi-plus-circle me-50"></i> Teardown</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="testPlanDisplay">
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-sm btn-light-secondary"
                                                data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary ml-1" id="reviewEditTest">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Review</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Report table managing modal [ADD, EDIT, DELETE] button block end-->
                        <!--Modal Section-->
                        <div class="modal fade text-left" id="Mseries" tabindex="-1" role="dialog" aria-labelledby="myModalLabelMtest" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabelMtest">M-Series Test</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="table1">
                                                <thead style="font-size:14px;">
                                                    <tr>
                                                        <th class="text-center">Test Series</th>
                                                        <th class="text-center">Standard</th>
                                                        <th class="text-center" style="width:130px;">Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size:15px;" id="MTestLayOut">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="testMtestRemarks" class="text-primary">Remarks </label>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <textarea id="testMtestRemarks" class="form-control" name="testMtestRemarks"
                                                    placeholder=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light-secondary"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="submitMTest2">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Submit</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="UserTest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel17">User Test Series</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between">
                                                <p>
                                                    <a class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                        <i class="bi bi-plus-circle"></i>  <spam class="mt-2">Add Step</span>
                                                    </a>
                                                </p>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="test1Remarks" class="text-primary">Quantity </label>
                                                    </div>
                                                    <div class="col-md-7 form-group">
                                                        <input type="number" id="test2Qty" class="form-control" name="test2Qty"
                                                            placeholder=""/>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        
                                        <div class="collapse" id="collapseExample">
                                            <div class="row">
                                                <div class="col-md-12 mb-4">
                                                    
                                                    <div class="row">
                                                        <div class="col-12 d-flex justify-content-between">
                                                            <h6>Select Test</h6>
                                                            <div class="button mb-2">
                                                                <button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-view-list"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" multiple="multiple" style="font-size:13px;" id="UserTestSelect">
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            
                                            <hr>
                                        </div>
                                        <div class="container text-center d-none" id="userTestAlert">
                                            <h6 class="text-primary">Add Test Step</h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="table2">
                                                <thead style="font-size:14px;">
                                                    <tr>
                                                        <th class="text-center">Step</th>
                                                        <th class="text-center">Test</th>
                                                        <th class="text-center" style="width:200px;">Standard</th>
                                                        <th class="text-center" style="width:130px;">Notes</th>
                                                        <th class="text-center" style="width:120px;">Attachment</th>
                                                        <th class="text-center" style="width:90px;">Temperature</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size:15px;" id="UserTestSelectLayout">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="test1Remarks" class="text-primary">Remarks </label>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <textarea id="test2Remarks" class="form-control" name="test2Remarks"
                                                    placeholder=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light-secondary"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="submitUserTestEdit">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Submit</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal fade text-left" id="SelectTest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel17">Select Battery Test</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="row">
                                            <div class="col-12 d-flex justify-content-between">
                                                <p>
                                                    <a class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample2">
                                                        <i class="bi bi-plus-circle"></i>  <spam class="mt-2">Add Step</span>
                                                    </a>
                                                </p>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="test3Qty" class="text-primary">Quantity </label>
                                                    </div>
                                                    <div class="col-md-7 form-group">
                                                        <input type="number" id="test3Qty" class="form-control" name="test3Qty"
                                                            placeholder=""/>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="collapse" id="collapseExample2">
                                            <div class="row">
                                                <div class="col-md-12 mb-4">
                                                    
                                                    <div class="row">
                                                        <div class="col-12 d-flex justify-content-between">
                                                            <h6>Select Test</h6>
                                                            <div class="button mb-2">
                                                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="bi bi-view-list"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" multiple="multiple" style="font-size:13px;" id="UserTestSelect2">
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-2">
                                                    <div class="buttons position-relative"">
                                                        <button class="btn btn-sm btn-primary btn-block">Add</button>
                                                    </div>
                                                </div> -->
                                            </div>
                                            
                                            <hr>
                                        </div>
                                        <div class="container text-center d-none" id="userTestAlert2">
                                            <h6 class="text-primary">Add Test Step</h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="table3">
                                                <thead style="font-size:14px;">
                                                    <tr>
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">Test</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size:15px;" id="UserTestSelectLayout3">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="test3Remarks" class="text-primary">Remarks </label>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <textarea id="test3Remarks" class="form-control" name="test3Remarks"
                                                    placeholder=""></textarea>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light-secondary"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="submitUserTest2Edit">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Submit</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal fade text-left" id="benchMark"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel20" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel20">Teardown</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="benchmarkQty" class="text-primary">Quantity </label>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <input type="number" id="benchmarkQty" class="form-control" name="benchmarkQty"
                                                    placeholder="">
                                            </div>
                                        </div>
                                        <div class="row">
                                                <div class="col-md-12">
                                                    <label for="test4Remarks" class="text-primary">Remarks </label>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <textarea id="test4Remarks" class="form-control" name="test4Remarks"
                                                        placeholder=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light-secondary"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary ml-1" id="submitUserTest3Edit">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Submit</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="ReviewModal" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title white" id="myModallabelReview">Review request
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="ReviewBody">
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <div class="container text-center">
                                            <button type="button" class="btn btn-light-secondary"
                                                 id="SaveDraftEdit">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="SaveRequestEdit">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Submit Request</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Modal Section End-->
                        <!-- View BTR Modal Block -->
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

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- View BTR Modal Block end-->

                        <div class="modal fade text-left" id="QrViewer" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-1">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="container text-center mb-3 mt-3">
                                                    <h5>Battery Testing Laboratory</h5>
                                                </div>
                                                <div class="container text-center">
                                                    <input type="hidden" id="requestID">
                                                    <image id="my_qr" class="img-fluid p-3" style="border: solid #EEEEEE 3px; border-radius:7px;" />

                                                    <p id="RequestSysID"></p>
                                                </div>

                                                <div class="container text-center mt-3">
                                                    <p class="font-bold">PHILIPPINE BATTERIES INCORPORATED</p>
                                                </div>

                                                <div class="container text-center mt-3">
                                                    <button type="button" class="btn btn-sm btn-light-secondary"
                                                        id="QrModalclose">
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

                        <div class="modal fade text-left" id="EditAddRequestor" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-1">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="container text-center mb-3 mt-3">
                                                    <h5>Add Requestor</h5>
                                                </div>
                                                
                                                <div class="container">
                                                    <input type="hidden" id="requiID">
                                                    <div class="form-check" id="formCheckEdit">
                                                    </div>
                                                </div>

                                                <div class="container text-center mt-3">
                                                    <button type="button" class="btn btn-sm btn-light-secondary"
                                                        id="QrModalclose" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        id="AddEditedRequestor">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Add</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="EditChangeClass" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-1">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="container text-center mb-3 mt-3">
                                                    <h5>Select Classification</h5>
                                                </div>
                                                
                                                <div class="container">
                                                    <input type="hidden" id="RequestID_holderForClassification">
                                                    <div class="form-check" id="formCheckEditClass">

                                                    </div>
                                                </div>

                                                <div class="container text-center mt-3">
                                                    <button type="button" class="btn btn-sm btn-light-secondary"
                                                        id="QrModalclose" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        id="EditClassification">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Change</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="EditChangePlateType" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-1">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="container text-center mb-3 mt-3">
                                                    <h5 id="selectTitle"></h5>
                                                </div>
                                                
                                                <div class="container">
                                                    <input type="hidden" id="PolarityID_holder">
                                                    <input type="hidden" id="RequestID_holderForPlateType">
                                                    <div class="form-check" id="formCheckEditPlateType">

                                                    </div>
                                                </div>

                                                <div class="container text-center mt-3">
                                                    <button type="button" class="btn btn-sm btn-light-secondary"
                                                        id="QrModalclose" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        id="EditPlateTypeBtn">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Change</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="modal fade text-left" id="EditChangeField" tabindex="-1" role="dialog"
                            aria-labelledby="myModallabelReview" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-1">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="container text-center mb-3 mt-3">
                                                    <h5 id="selectedFormTitle"></h5>
                                                </div>

                                                <div class="container">
                                                    <input type="hidden" id="editFieldRequestID_holder">
                                                    <input type="hidden" id="fieldTxt">
                                                    <input type="hidden" id="IsInt">
                                                    <input type="text" class="form-control" id="FieldValueString">
                                                    <input type="number" class="form-control" id="FieldValueInt">
                                                </div>

                                                <div class="container text-center mt-3">
                                                    <button type="button" class="btn btn-sm btn-light-secondary"  data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Close</span>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        id="EditFieldValueBtn">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Update</span>
                                                    </button>
                                                </div>
                                            </div>
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
    <div id="qr" class="d-none"></div>
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

    <script src="../assets_original/QR_generator/jquery.qrcode.min.js"></script>
    <script src="../assets_original/js/print.min.js"></script>

    <script src="../assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
    <script src="../assets/js/pages/form-element-select.js"></script>
    <script src="CustomerJS_repository/index_repo.js"></script>
    
</body>

</html>
