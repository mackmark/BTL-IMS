<section class="section position-relative" style="top:-40px;">
    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <a href="#" class="btn btn-md icon btn-primary float-end" onclick="loadContent('add_request.php')"><i class="bi bi-plus"></i> Add request</a>
            </div>
        </div>
        <div class="card-body">
            <!-- Table with no outer spacing -->
            <div class="divider divider-left">
                <div class="divider-text text-primary">Request table</div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="table1">
                    <thead style="font-size:14px;">
                        <tr>
                            <th class="text-center">Test ID</th>
                            <th class="text-center">Battery Code</th>
                            <th class="text-center">Project Name</th>
                            <!-- <th>Purpose</th> -->
                            <th class="text-center">Test Objective</th>
                            <!-- <th>Requirement</th> -->
                            <th>Status</th>
                            <th>Total Qty</th>
                            <!-- <th>Date Approved</th>
                            <th>Expected Date of Finish</th> -->
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:13px;">
                        <tr>
                            <td class="text-center">D-3006-001</td>
                            <td class="text-center">WD3xx-LI0001</td>
                            <td class="text-center">Motolite Excel v.2.1</td>
                            <td class="text-center">Access Performance of new addidtives</td>
                            <td>
                                <div class="badges">
                                    <span class="badge bg-light-secondary">Draft</span>
                                </div>
                            </td>
                            <td>3 pcs</td>
                            <td>
                                <!-- <div class="buttons">
                                    <a href="#" class="btn btn-sm icon btn-info"><i class="bi bi-eye"></i></a>
                                    <a href="#" class="btn btn-sm icon btn-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="#" class="btn btn-sm icon btn-danger"><i class="bi bi-trash"></i></a>
                                </div> -->
                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">T-3006-001</td>
                            <td class="text-center">WD3xx-LI0001</td>
                            <td class="text-center">Motolite Excel v.2.1</td>
                            <td class="text-center">Access Performance of new addidtives</td>
                            <td>
                                <div class="badges">
                                    <span class="badge bg-light-warning">For Approval</span>
                                </div>
                            </td>
                            <td>3 pcs</td>
                            <td>
                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <!-- <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button> -->
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">T-3006-002</td>
                            <td class="text-center">WD3xx-LI0001</td>
                            <td class="text-center">Motolite Excel v.2.1</td>
                            <td class="text-center">Access Performance of new addidtives</td>
                            <td>
                                <div class="badges">
                                    <span class="badge bg-light-primary">Approved</span>
                                </div>
                            </td>
                            <td>3 pcs</td>
                            <td>
                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <!-- <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button> -->
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">T-3006-004</td>
                            <td class="text-center">WD3xx-LI0001</td>
                            <td class="text-center">Motolite Excel v.2.1</td>
                            <td class="text-center">Access Performance of new addidtives</td>
                            <td>
                                <div class="badges">
                                    <span class="badge bg-light-danger">For Revision</span>
                                </div>
                            </td>
                            <td>3 pcs</td>
                            <td>
                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">T-3006-005</td>
                            <td class="text-center">WD3xx-LI0001</td>
                            <td class="text-center">Motolite Excel v.2.1</td>
                            <td class="text-center">Access Performance of new addidtives</td>
                            <td>
                                <div class="badges">
                                    <span class="badge bg-light-success">On-going</span>
                                </div>
                            </td>
                            <td>3 pcs</td>
                            <td>
                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <!-- <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button> -->
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">T-3006-006</td>
                            <td class="text-center">WD3xx-LI0001</td>
                            <td class="text-center">Motolite Excel v.2.1</td>
                            <td class="text-center">Access Performance of new addidtives</td>
                            <td>
                                <div class="badges">
                                    <span class="badge bg-light-info">Hold</span>
                                </div>
                            </td>
                            <td>3 pcs</td>
                            <td>
                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                    <!-- <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button> -->
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>



<section class="section multiple-choices position-relative" style="top:-40px;">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <a href="#" class="btn icon btn-outline-primary float-start" onclick="loadContent('request.php')"><i class="bi bi-arrow-left"></i></a>
                    <label class="text-primary mt-2"style="font-weight:bold;" >New Battery Test Request</label>
                    <span class="text-primary mt-2"></span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="form form-horizontal">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="first-name">Requestor</label>
                        </div>
                        <div class="col-md-7 form-group">
                            <select class="choices form-select multiple-remove" multiple="multiple">
                                <optgroup label="Figures">
                                    <option value="romboid">Romboid</option>
                                    <option value="trapeze" selected>Trapeze</option>
                                    <option value="triangle">Triangle</option>
                                    <option value="polygon">Polygon</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label>Project Name</label>
                        </div>
                        <div class="col-md-7 form-group">
                            <input type="email" id="email-id" class="form-control" name="email-id"
                                placeholder="Project Namel">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label>Test Objective</label>
                        </div>
                        <div class="col-md-7 form-group">
                            <input type="number" id="contact-info" class="form-control" name="contact"
                                placeholder="Test Objective">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label>Classification</label>
                        </div>
                        <div class="col-md-7 form-group">
                            <input type="text" id="classification" class="form-control" name="classification"
                                placeholder="Classification">
                        </div>
                    </div>
                        
                        
                        
                        <!-- <div class="col-12 col-md-8 offset-md-4 form-group">
                            <div class='form-check'>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkbox1" class='form-check-input'
                                        checked>
                                    <label for="checkbox1">Remember Me</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                            <button type="reset"
                                class="btn btn-light-secondary me-1 mb-1">Reset</button>
                        </div> -->
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</section>


<div class="modal fade text-left" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
        role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title white" id="myModalLabel17">Test Plan</h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
            <div class="col-lg-12 col-md-12">
                <div class="card widget-todo">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="card-title d-flex">
                            <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>Test
                        </h4>
                        <ul class="list-inline d-flex mb-0">
                            <li class="d-flex align-items-center">
                                <i class="bx bx-check-circle font-medium-3 me-50"></i>
                                <div class="dropdown">
                                    <div class="dropdown-toggle me-1" role="button" id="dropdownMenuButton"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Category
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">M-series Test</a>
                                        <a class="dropdown-item" href="#">User Test Series</a>
                                        <a class="dropdown-item" href="#">Select Battery Test</a>
                                        <a class="dropdown-item" href="#">Bechmark</a>
                                    </div>
                                </div>
                            </li>
                            <!-- <li class="d-flex align-items-center">
                                <i class="bx bx-sort me-50 font-medium-3"></i>
                                <div class="dropdown">
                                    <div class="dropdown-toggle" role="button" id="dropdownMenuButton2"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Task
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                        <a class="dropdown-item" href="#">Option 1</a>
                                        <a class="dropdown-item" href="#">Option 2</a>
                                        <a class="dropdown-item" href="#">Option 3</a>
                                    </div>
                                </div>
                            </li> -->
                        </ul>
                    </div>
                    <div class="card-body px-0 py-1">
                        <ul class="widget-todo-list-wrapper" id="widget-todo-list">
                            <li class="widget-todo-item">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox1">
                                            <label for="checkbox1"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 1</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="widget-todo-item">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox2">
                                            <label for="checkbox2"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 2</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="widget-todo-item completed">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox3" checked>
                                            <label for="checkbox3"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 3</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="widget-todo-item">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox4">
                                            <label for="checkbox4"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 4</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="widget-todo-item">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox5">
                                            <label for="checkbox5"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 5</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="widget-todo-item">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox6">
                                            <label for="checkbox6"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 6</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="widget-todo-item">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox6">
                                            <label for="checkbox6"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 7</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="widget-todo-item">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox6">
                                            <label for="checkbox6"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 8</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <li class="widget-todo-item">
                                <div
                                    class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                    <div class="widget-todo-title-area d-flex align-items-center">
                                        <i data-feather="list" class="cursor-move"></i>
                                        <div class="checkbox checkbox-shadow">
                                            <input type="checkbox" class="form-check-input" id="checkbox6">
                                            <label for="checkbox6"></label>
                                        </div>
                                        <span class="widget-todo-title ml-50">M-Series No. 9</span>
                                    </div>
                                    <div class="widget-todo-item-action d-flex align-items-center">
                                        <!-- <div class="badge badge-pill bg-light-success me-1">frontend</div>
                                        <div class="avatar bg-warning">
                                            <img src="assets/images/faces/1.jpg" alt="" srcset="">
                                        </div>
                                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i> -->
                                        <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-md-2">
                                                        <label class="mt-2">Qty</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <input type="number" id="M1Qty" class="form-control" name="M1Qty"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                    data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1"
                    data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Accept</span>
                </button>
            </div> -->
        </div>
    </div>
</div>



<!-- CAPACITY TEST-->
<div class="col-lg-4 col-md-12">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <div class="container">
                                                                                    <label for="" class="text-primary">CAPACITY TEST</label>
                                                                                </div>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-hover" id="table1">
                                                                                        <!-- <thead style="font-size:14px;">
                                                                                        <tr>
                                                                                            <th class="text-center">Test</th>
                                                                                        </tr>
                                                                                        </thead> -->
                                                                                        <tbody style="font-size:15px;">
                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">Activation Test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">Filled Discharge Test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">High Rate Discharge Test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">AH test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">C5 test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">Vibration Test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- CAPACITY TEST-->

                                                                     <!--LIFE CYCLE TEST-->
                                                                    <div class="col-lg-4 col-md-12">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <div class="container">
                                                                                    <label for="" class="text-primary">LIFE CYCLE TEST</label>
                                                                                </div>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-hover" id="table1">
                                                                                        <!-- <thead style="font-size:14px;">
                                                                                        <tr>
                                                                                            <th class="text-center">Test</th>
                                                                                        </tr>
                                                                                        </thead> -->
                                                                                        <tbody style="font-size:15px;">
                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">Light Load Endurance Test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">Heavy Load Endurance Test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">Water Consumption Test</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--LIFE CYCLE TEST END-->

                                                                    <!--OTHER TEST-->
                                                                    <div class="col-lg-4 col-md-12">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <div class="container">
                                                                                    <label for="" class="text-primary">OTHERS</label>
                                                                                </div>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-hover" id="table1">
                                                                                        <!-- <thead style="font-size:14px;">
                                                                                        <tr>
                                                                                            <th class="text-center">Test</th>
                                                                                        </tr>
                                                                                        </thead> -->
                                                                                        <tbody style="font-size:15px;">
                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">REST</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label" for="customColorCheck1">BOOSTCHARGE</label>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <div class="form-check">
                                                                                                        <div class="custom-control custom-checkbox">
                                                                                                            <input type="checkbox"
                                                                                                                class="form-check-input form-check-primary form-check-glow"
                                                                                                                name="customCheck" id="customColorCheck1">
                                                                                                                <div class="badges">
                                                                                                                    <span class="badge bg-light-primary">
                                                                                                                        <label class="form-check-label d-flex" for="customColorCheck1">OTHERS</label>
                                                                                                                        <input type="text" id="UtestOther" class="form-control d-flex" name="UtestOther" placeholder="">
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>

                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--OTHER TEST END-->

                        <div class="modal fade text-left" id="large" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel17" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel17">Test Plan</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <!-- <div class="card-header">
                                                <h4 class="card-title">Javascript Behavior</h4>
                                            </div> -->
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                                            aria-orientation="vertical">
                                                            <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                                                                href="#v-pills-home" role="tab" aria-controls="v-pills-home"
                                                                aria-selected="true">Test M-Series</a>
                                                            <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                                                href="#v-pills-profile" role="tab" aria-controls="v-pills-profile"
                                                                aria-selected="false">User Test Series</a>
                                                            <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                                                                href="#v-pills-messages" role="tab" aria-controls="v-pills-messages"
                                                                aria-selected="false">Select Battery Test</a>
                                                            <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                                                href="#v-pills-settings" role="tab" aria-controls="v-pills-settings"
                                                                aria-selected="false">Bechmark</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="tab-content" id="v-pills-tabContent">
                                                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                                                aria-labelledby="v-pills-home-tab">
                                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ut nulla neque.
                                                                Ut hendrerit nulla a euismod pretium.
                                                                Fusce venenatis sagittis ex efficitur suscipit. In tempor mattis fringilla. Sed
                                                                id tincidunt orci, et volutpat ligula.
                                                                Aliquam sollicitudin sagittis ex, a rhoncus nisl feugiat quis. Lorem ipsum dolor
                                                                sit amet, consectetur adipiscing elit.
                                                                Nunc ultricies ligula a tempor vulputate. Suspendisse pretium mollis ultrices
                                                            </div>
                                                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                                                aria-labelledby="v-pills-profile-tab">
                                                                Integer interdum diam eleifend metus lacinia, quis gravida eros mollis. Fusce
                                                                non sapien sit amet magna dapibus
                                                                ultrices. Morbi tincidunt magna ex, eget faucibus sapien bibendum non. Duis a
                                                                mauris ex. Ut finibus risus sed massa
                                                                mattis porta. Aliquam sagittis massa et purus efficitur ultricies.
                                                            </div>
                                                            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                                                aria-labelledby="v-pills-messages-tab">
                                                                Integer pretium dolor at sapien laoreet ultricies. Fusce congue et lorem id
                                                                convallis. Nulla volutpat tellus nec
                                                                molestie finibus. In nec odio tincidunt eros finibus ullamcorper. Ut sodales,
                                                                dui nec posuere finibus, nisl sem aliquam
                                                                metus, eu accumsan lacus felis at odio.
                                                            </div>
                                                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                                                                aria-labelledby="v-pills-settings-tab">
                                                                Sed lacus quam, convallis quis condimentum ut, accumsan congue massa.
                                                                Pellentesque et quam vel massa pretium ullamcorper
                                                                vitae eu tortor.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-primary ml-1"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Submit</span>
                                        </button>
                                    </div> -->
                                </div>
                            </div>
                        </div>

















                        <!-- Test Plan -->
                        <div class="card">
                                                <!-- <div class="card-header">
                                                    <h5 class="card-title">Horizontal Navs</h5>
                                                </div> -->
                                                <div class="card-body">
                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link active" id="test1-tab" data-bs-toggle="tab" href="#test1" role="tab"
                                                                aria-controls="test1" aria-selected="true" style="font-size:14px;">Test M-Series</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" id="test2-tab" data-bs-toggle="tab" href="#test2" role="tab"
                                                                aria-controls="test2" aria-selected="false" style="font-size:14px;">User Test Series</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" id="test3-tab" data-bs-toggle="tab" href="#test3" role="tab"
                                                                aria-controls="test3" aria-selected="false" style="font-size:14px;">Battery Test</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" id="test4-tab" data-bs-toggle="tab" href="#test4" role="tab"
                                                                aria-controls="test4" aria-selected="false" style="font-size:14px;">Benchmark</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="test1" role="tabpanel" aria-labelledby="test1-tab">
                                                            <div class="row mt-3">
                                                                <div class="col-lg-7 col-md-12">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="table1">
                                                                                    <thead style="font-size:14px;">
                                                                                        <tr>
                                                                                            <th class="text-center">Test Series</th>
                                                                                            <th class="text-center">Standard</th>
                                                                                            <th class="text-center">Qty</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody style="font-size:15px;">
                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 1</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 2</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 3</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 4</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 5</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 6</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 7</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 8</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <div class="form-check">
                                                                                                    <div class="custom-control custom-checkbox">
                                                                                                        <input type="checkbox"
                                                                                                            class="form-check-input form-check-primary form-check-glow"
                                                                                                            name="customCheck" id="customColorCheck1">
                                                                                                            <div class="badges">
                                                                                                                <span class="badge bg-light-primary">
                                                                                                                    <label class="form-check-label" for="customColorCheck1">M-Series No. 9</label>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <select class="form-select" id="MStd" style="font-size:13px;">
                                                                                                    <option value="JIS 2006" selected>JIS 2006</option>
                                                                                                    <option value="JIS 2019">JIS 2019</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <input type="number" id="MQty" class="form-control" name="MQty" style="font-size:13px;" placeholder="">
                                                                                            </td>
                                                                                        </tr>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="tab-pane fade" id="test2" role="tabpanel" aria-labelledby="test2-tab">
                                                            <div class="row mt-3">
                                                                <div class="col-lg-12 col-md-12">
                                                                    <div class="card">
                                                                        <div class="card-header">
                                                                            <div class="container-fluid">
                                                                                <button type="button" class="btn btn-sm icon btn-primary float-end"><i class="bi bi-plus"></i> Add Step</button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="table1">
                                                                                    <thead style="font-size:14px;">
                                                                                        <tr>
                                                                                            <th class="text-center">Step</th>
                                                                                            <th class="text-center">Test</th>
                                                                                            <th class="text-center">Standard</th>
                                                                                            <th class="text-center">Qty</th>
                                                                                            <th class="text-center">Temperature</th>
                                                                                            <th class="text-center">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody style="font-size:15px;">
                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                Step 1
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-light-primary">
                                                                                                    <label class="form-check-label" for="customColorCheck1">Filled Discharge Test</label>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                JIS
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                2
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                N/A
                                                                                            </td>
                                                                                            
                                                                                            <td class="text-center">
                                                                                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                                                                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                                                                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                Step 2
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-light-primary">
                                                                                                    <label class="form-check-label" for="customColorCheck1">C5 Test</label>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                JIS
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                1
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                N/A
                                                                                            </td>
                                                                                            
                                                                                            <td class="text-center">
                                                                                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                                                                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                                                                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                Step 3
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-light-primary">
                                                                                                    <label class="form-check-label" for="customColorCheck1">AH Test</label>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                JIS
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                2
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                N/A
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                                                                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                                                                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="test3" role="tabpanel" aria-labelledby="test3-tab">
                                                            <div class="row mt-3">
                                                                <div class="col-lg-12 col-md-12 ">
                                                                    <div class="card">
                                                                        <div class="card-header">
                                                                            <div class="container-fluid">
                                                                                <button type="button" class="btn btn-sm icon btn-primary float-end"><i class="bi bi-plus"></i> Add Test</button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover" id="table1">
                                                                                    <thead style="font-size:14px;">
                                                                                        <tr>
                                                                                            <th class="text-center">Test</th>
                                                                                            <th class="text-center">Standard</th>
                                                                                            <th class="text-center">Qty</th>
                                                                                            <th class="text-center">Attachment</th>
                                                                                            <th class="text-center">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody style="font-size:15px;">
                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-light-primary">
                                                                                                    <label class="form-check-label" for="customColorCheck1">Filled Discharge Test</label>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                JIS
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                2
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                otherStd.png
                                                                                            </td>
                                                                                            
                                                                                            <td class="text-center">
                                                                                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                                                                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                                                                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-light-primary">
                                                                                                    <label class="form-check-label" for="customColorCheck1">C5 Test</label>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                JIS
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                1
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                otherStd.pdf
                                                                                            </td>
                                                                                            
                                                                                            <td class="text-center">
                                                                                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                                                                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                                                                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-center">
                                                                                                <span class="badge bg-light-primary">
                                                                                                    <label class="form-check-label" for="customColorCheck1">AH Test</label>
                                                                                                </span>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                JIS
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                2
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                otherStd.docx
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                <div class="btn-group mb-0" role="group" aria-label="Basic example">
                                                                                                    <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></button>
                                                                                                    <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                            
                                                            </div>

                                                        </div>
                                                        <div class="tab-pane fade" id="test4" role="tabpanel" aria-labelledby="test4-tab">
                                                            <div class="row mt-3">
                                                                <div class="col-lg-12 col-md-12">
                                                                    <div class="col-lg-6 col-md-3">
                                                                        <div class="container">
                                                                            <label for="bechmarkQty" class="text-primary">Quantity</label>
                                                                            <input type="number" id="bechmarkQty" class="form-control">
                                                                        </div> 

                                                                        <div class="container mt-2">
                                                                            <label for="bechmarRemarks" class="text-primary">Remarks</label>
                                                                            <textarea id="bechmarRemarks" class="form-control"></textarea>
                                                                        </div> 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>