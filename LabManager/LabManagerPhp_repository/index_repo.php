<?php
include "../../Database/db_connection.php";
include "../../Php_function/functions.php";
require 'class/WaterBathController.php';

date_default_timezone_set("Asia/Manila");
$date = date('Y-m-d');//date uploaded
$dateNav = date('M d, Y');
$time =  date("H:i:s");
$time2 = date("h:i a");
$day = date("l");
$year = date("Y"); //year
$timestamp = $date.' '.$time;

if(isset($_POST['action'])){
    if($_POST['action']=='logout'){
        $result = 0;
        if(Logout()){
            $result = Logout();
        }

        echo json_encode($result);
    }

    else if($_POST['action']=='TestRequest_tbl'){

        $search = "%".$_POST["search"]["value"]."%";

        $column = array("RequestSysID", "", "RequestDate", "ProjectName", "ClassificationTxt", "TestObjective", "Overalltotal", "", "Overalltotal", "StatusTxt", "" );

        $query = "select request.RequestID, request.RequestSysID, requisition.ProjectName, requisition.TestObjective, classification.ClassificationTxt, request.DateCreated as RequestDate, Total.Overalltotal, Status.StatusTxt, Status.StatusID, request.PrioritizationID, reqSpecialInstruct.SpecialInstruction ";
        $query .="from Request_tbl request ";
        $query .="join Requisition_tbl requisition ON request.RequisitionID = requisition.RequisitionID ";
        $query .="left join BatteryDetails_tbl batdetails ON request.RequisitionID = batdetails.RequisitionID ";
        $query .="join Classification_tbl classification ON requisition.ClassificationID = classification.ClassificationID ";
        $query .="left join RequestSpecialInstruction_tbl reqSpecialInstruct ON request.RequisitionID = reqSpecialInstruct.RequisitionID ";
        $query .="cross apply (
            select top 1 reqstat.StatusID, stat.StatusTxt  from RequestStatus_tbl reqstat join Status_tbl stat ON reqstat.StatusID = stat.StatusID where reqstat.RequestID = request.RequestID order by reqstat.DateCreated DESC
        ) as Status ";
        $query .="cross apply (
            select COALESCE(sum(TotalQty), 0) as Overalltotal from TestPlan_tbl where RequestID = request.RequestID and IsActive = 1 and IsDeleted = 0
        ) as Total ";
        $query .="WHERE Status.StatusID = 2 and request.IsActive = 1 and request.IsDeleted = 0 ";
        

        if(isset($_POST["search"]["value"])){											
            if(!empty($_POST["search"]["value"])){
                $query .='AND (request.RequestSysID LIKE ? ';
                $query .='OR classification.ClassificationTxt LIKE ? ';
                $query .='OR Status.StatusTxt LIKE ? ';
                $query .='OR CAST(Total.Overalltotal AS VARCHAR) LIKE ?) ';
            }
        }

        if(isset($_POST["order"])){

            $query .='ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir']. ' ';
        } 

        else{

            $query .='ORDER BY request.RequestID ASC ';
        }

        $query1 ='';

        if($_POST["length"] != -1){
            $query1 = 'OFFSET '. $_POST['start'].' ROWS
            FETCH NEXT '.$_POST['length'].' ROWS ONLY ';
        }

        $result = "";
        $count  = 0;

        if(!empty($_POST["search"]["value"])){
            $result = odbc_prepare($connServer, $query.$query1);
            odbc_execute($result, array($search, $search, $search, $search));
            $count = odbc_num_rows($result);
            // $result = odbc_fetch_array($stmt);
        }
        else{
            $table = odbc_exec($connServer, $query);
            $count = odbc_num_rows($table);
            $result = odbc_exec($connServer, $query.$query1);
        }

        // confirmQuery($result);

        $data = array();
        
        $n = 1;

        while($row = odbc_fetch_array($result)){

            $RequestID  = $row['RequestID'];
            $RequestSysID  = $row['RequestSysID'];
            $Purpose    = $row['ClassificationTxt'];
            $ProjectName    = $row['ProjectName'];
            $TestObjective      = $row['TestObjective'];
            $StatusID     = $row['StatusID'];
            $StatusTxt     = $row['StatusTxt'];
            $TotalQty      = $row['Overalltotal'];
            $dateAdded = $row['RequestDate'];
            $priorityID = $row['PrioritizationID'];
            $instruction = $row['SpecialInstruction'];

            $pcs = "";
            if($TotalQty > 1){
                $pcs = "pcs";
            }
            else{
                $pcs = "pc";
            }

            $priorityIcon = "";
            if($priorityID==2){
                $priorityIcon = '<i class="text-info bi bi-exclamation-triangle-fill position-relative" data-toggle="tooltip" data-placement="top" title="Priority: Low" id="my-tooltip-priority" style="cursor:pointer;top:-2px;"></i>';
            }
            else if($priorityID==3){
                $priorityIcon = '<i class="text-warning bi bi-exclamation-triangle-fill position-relative" data-toggle="tooltip" data-placement="top" title="Priority: Mid" id="my-tooltip-priority" style="cursor:pointer;top:-2px;"></i>';
            }
            else if($priorityID==4){
                $priorityIcon = '<i class="text-danger bi bi-exclamation-triangle-fill position-relative" data-toggle="tooltip" data-placement="top" title="Priority: High" id="my-tooltip-priority" style="cursor:pointer;top:-2px;"></i>';
            }

            $instructionContainer = "";
            if($instruction != null){
                $instructionContainer = '<i class="text-primary bi bi-info-circle-fill position-relative" data-toggle="tooltip" data-placement="top" title="'.$instruction.'" id="my-tooltip-priority" style="cursor:pointer;top:-2px;"></i>';
            }

            $requirementQuery = "select tbl.Test
            from TestPlan_tbl tp
            join TestPlanDetails_tbl tpd ON tp.TestPlanID = tpd.TestPlanID
            join TestTable_tbl tbl ON tpd.TestTableID = tbl.TestTableID
            where tp.RequestID = ".$RequestID." and tp.IsActive = 1 and tp.IsDeleted = 0 ";
            $requirementResult = odbc_exec($connServer, $requirementQuery);

            $RequirementsDiv = '<div>';
            while($requirementRow = odbc_fetch_array($requirementResult)){
                $RequirementsDiv .= '<div class="badges">
                                        <span class="badge bg-light-secondary">'.$requirementRow['Test'].'</span>
                                    </div>';
            }
            $RequirementsDiv .= '</div>';

            $statusContainer = '';

            if($StatusID==1){
                $statusContainer = '<div class="badges">
                                        <span class="badge bg-light-secondary">'.$StatusTxt.'</span>
                                    </div>';
            }
            else if($StatusID==2){
                $statusContainer = '<div class="badges">
                                        <span class="badge bg-light-info">'.$StatusTxt.'</span>
                                    </div>';
            }
            else if($StatusID==6){
                $statusContainer = '<div class="badges">
                                        <span class="badge bg-light-success">'.$StatusTxt.'</span>
                                    </div>';
            }
            else if($StatusID==7){
                $statusContainer = '<div class="badges">
                                        <span class="badge bg-light-warning">'.$StatusTxt.'</span>
                                    </div>';
            }

            $purposeConainer = '<div class="badges" data-toggle="tooltip" data-placement="top" title="'.$Purpose.'" id="my-tooltip-button" style="cursor:pointer;">
                                    <span class="badge bg-light-primary">'.GetInitialsPurpose($Purpose).'</span>
                                </div>';

            $requestorQuery = "select emp.Fname, emp.Lname
            from Request_tbl request
            join Requestor_tbl requestor ON request.RequisitionID = requestor.RequisitionID
            join Employee_tbl emp ON requestor.EmployeeID = emp.EmployeeID
            where request.RequestID = ".$RequestID." and requestor.IsActive = 1 and requestor.IsDeleted = 0 ";
            $requestorReault = odbc_exec($connServer, $requestorQuery);

            $requestorOutput = "";
            if($requestorReault){
                while($requestorRow = odbc_fetch_array($requestorReault)){
                   
                    $fname = GetNameInitials($requestorRow['Fname']);
                    $Lname = GetNameInitials($requestorRow['Lname']);
                    $nameInitials = $fname.$Lname;
                    $concatName = $requestorRow['Fname']." ".$requestorRow['Lname'];
                    
                    $requestorOutput .= '<div class="avatar avatar-sm  me-1" data-toggle="tooltip" data-placement="top" title="'.$concatName.'" id="my-tooltip-button" style="cursor:pointer;background:#babdbf;" >
                                            <span class="avatar-content">'.$nameInitials.'</span>
                                        </div>';
                }
            }

            $sub_array = array();
            
            $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".$RequestSysID." ".$priorityIcon." ".$instructionContainer."</span>";
            $sub_array[] = $requestorOutput;
            $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".date('M d, Y',strtotime($dateAdded))."</span>";
            $sub_array[] =  $ProjectName;
            $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".$purposeConainer."</span>";
            $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".$TestObjective."</span>";
            
            $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".number_format($TotalQty)." ".$pcs."</span>";
            $sub_array[] =  $RequirementsDiv;
            $sub_array[] = $statusContainer;
            if($StatusID==6){
                $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                <button type="button" onclick="ViewBtr('.$RequestID.')" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                            </div>';
            }
            else{
                $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                <button type="button" onclick="ViewBtr('.$RequestID.')" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                <button type="button" onclick="ApprovedBtr('.$RequestID.')" class="btn btn-sm btn-primary"><i class="bi bi-check"></i></button>
                            </div>';
            }
            
            

            // if($StatusID==1){
            //     $sub_array[] = '
            //     <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-pencil" onclick="EditRequest('.$RequestID.')"></i></button>
            //     <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>';
            // }
            // else if($StatusID==2){
            //     $sub_array[] = '<button type="button" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>';
            // }
            
            $data[] = $sub_array;
        }

        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsFiltered' => $count,
            'data' => $data,
            "recordsTotal"=> $count
        );
        
        // file_put_contents("tableData.json", json_encode($output));a

        echo json_encode($output);
    }

    else if($_POST['action']=='BTR_ViewRequest'){
        $requestID = 0;
        $output = '';

        if(isset($_POST['requestID'])){
            $requestID = $_POST['requestID'];
        }

        $sql = "select *, negative.PlateType as negativePlate, positive.PlateType as positivePlate  from Request_tbl rt ";
        $sql .= "left join Requisition_tbl rqt ON rt.RequisitionID = rqt.RequisitionID ";
        $sql .= "left join Disposal_tbl dt ON rqt.DisposalID = dt.DisposalID ";
        $sql .= "left join Classification_tbl ct ON rqt.ClassificationID = ct.ClassificationID ";
        $sql .= "left join BatteryDetails_tbl bdt ON rqt.RequisitionID = bdt.RequisitionID ";
        $sql .= "left join BatteryType_tbl btt ON bdt.BatteryTypeID = btt.BatteryTypeID ";
        $sql .= "left join BatterySizes_tbl sizes ON bdt.BatterySizeID = sizes.BatterySizeID ";
        $sql .= "left join PlateType_tbl positive ON bdt.PositivePlateID = positive.PlateTypeId ";
        $sql .= "left join PlateType_tbl negative ON bdt.NegativePlateID = negative.PlateTypeId ";
        $sql .= "cross apply (
            select top 1 res.StatusID, stat.StatusTxt  from RequestStatus_tbl res join Status_tbl stat ON res.StatusID = stat.StatusID where res.RequestID = rt.RequestID order by res.DateCreated DESC
        ) as Status ";
        $sql .= "where rt.RequestID = ".$requestID." ";

        $result = odbc_exec($connServer, $sql);

        if($result){
            while($row = odbc_fetch_array($result)){
                $output .= '
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" value="'.$requestID.'" id="RequestID_ref">
                        <div class="container shadow-sm rounded p-2 mb-2 ">
                        <div class="row">
                                <div class="col-md-12">
                                    <p><label class="text-primary" style="font-weight:bold;">DISPOSAL OF BATTERIES: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['ProcedureTxt'].'</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="container shadow-sm rounded p-2 mb-2">
                            <div class="divider divider-center">
                                <div class="divider-text text-primary">REQUISITION</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><label class="text-primary" style="font-weight:bold;">REQUESTOR: </label>&nbsp;&nbsp;';
                                    $requestorQuery = "select emp.Fname, emp.Lname
                                    from Request_tbl request
                                    join Requestor_tbl requestor ON request.RequisitionID = requestor.RequisitionID
                                    join Employee_tbl emp ON requestor.EmployeeID = emp.EmployeeID
                                    where request.RequestID = ".$requestID." ";
                                    $requestorReault = odbc_exec($connServer, $requestorQuery);
                                    if($requestorReault){
                                        while($rquestorRow = odbc_fetch_array($requestorReault)){
                                            $name = $rquestorRow['Fname'].' '.$rquestorRow['Lname'];
                                            $output .= '<span class="badge bg-light-primary">'.$name.'</span>&nbsp;&nbsp;';
                                        }
                                    }
                                    $output .= '</p>
                                </div>

                                <div class="col-md-12">
                                    <p><label class="text-primary" style="font-weight:bold;">PROJECT NAME: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['ProjectName'].'</span></p>
                                </div>

                                <div class="col-md-12">
                                    <p><label class="text-primary" style="font-weight:bold;">TEST OBJECTIVE: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['TestObjective'].'</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="container shadow-sm rounded p-2 mb-2">
                            <div class="divider divider-center">
                                <div class="divider-text text-primary">BATTERY SAMPLE CLASSIFICATION</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><label class="text-primary" style="font-weight:bold;">Classification: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['ClassificationTxt'].'</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="container shadow-sm rounded p-2 mb-2">
                            <div class="divider divider-center">
                                <div class="divider-text text-primary">BATTERY SAMPLE DETAILS</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><label class="text-primary" style="font-weight:bold;">Brand: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['BatteryBrand'].'</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p><label class="text-primary" style="font-weight:bold;">Battery Type: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['BatteryType'].'</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><label class="text-primary" style="font-weight:bold;">Battery Size: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['BatterySize'].'</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p><label class="text-primary" style="font-weight:bold;">Battery Code: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['BatteryCode'].'</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><label class="text-primary" style="font-weight:bold;">Production Code: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['ProductionCode'].'</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p><label class="text-primary" style="font-weight:bold;">Positive Plate : </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['positivePlate'].'</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><label class="text-primary" style="font-weight:bold;">Quantity: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['PositivePlateQty'].' PCS</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p><label class="text-primary" style="font-weight:bold;">Negative Plate : </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['negativePlate'].'</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><label class="text-primary" style="font-weight:bold;">Quantity: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['NegativePlateQty'].' PCS</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <p><label class="text-primary" style="font-weight:bold;">RC Rating : </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['RC'].'</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p><label class="text-primary" style="font-weight:bold;">AH Rating: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['AH'].'</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p><label class="text-primary" style="font-weight:bold;">C5 Rating: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['C5'].'</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <p><label class="text-primary" style="font-weight:bold;">CCA Rating : </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['CCA'].'</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p><label class="text-primary" style="font-weight:bold;">CA Rating: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['CA'].'</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p><label class="text-primary" style="font-weight:bold;">SG: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['SG'].'</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <p><label class="text-primary" style="font-weight:bold;">Others : </label>&nbsp;&nbsp;<span class="text-secondary">if available</span></p>
                                </div>
                            </div>

                        </div>

                        <div class="container shadow-sm rounded p-2 mb-2">
                            <div class="divider divider-center">
                                <div class="divider-text text-primary">REQUEST TEST PLAN</div>
                            </div>';

                            $sql = "select * from TestPlan_tbl where RequestID = ".$requestID." ";
                            $sql .= "and IsActive = 1 and IsDeleted = 0 ";
                            $result = odbc_exec($connServer, $sql);
                            $count = odbc_num_rows($result);

                            if($count==0){
                                $output.='
                                            <div class="container shadow-sm rounded p-2 bg-white mb-2">
                                                <div class="text-center">
                                                    <h6 class="text-primary">No data to be shown</h6>
                                                </aiv>
                                            </div>
                                            ';
                            }
                            else{
                                if($result){
                                    while($row = odbc_fetch_array($result)){
                                        $UserTest = "";
                                        $pcs = "";
                                        if($row['TotalQty']>1){
                                            $pcs = "PCS";
                                        }
                                        else{
                                            $pcs = "PC";
                                        }

                                        if($row['UserTestCategory']==1){
                                            $UserTest = "M-Series Test";

                                            $output.='

                                            <div class="row">
                                                <div class="col-md-12">
                                                <p><label class="text-primary" style="font-weight:bold;">'.$row['TestPlanNo'].': </label>&nbsp;&nbsp;<span class="badge bg-light-primary">'.$UserTest.'</span></p>';
                                                    $sql1 = "select tt.Test, tpd.TestQty from TestPlanDetails_tbl tpd ";
                                                    $sql1.="join TestTable_tbl tt ON tpd.TestTableID = tt.TestTableID ";
                                                    $sql1.="join TestStandard_tbl ts ON tpd.TestStandardID = ts.TestStandardID ";
                                                    $sql1.="where tpd.TestPlanID =  ".$row['TestPlanID']." ";
                                                    $result1 = odbc_exec($connServer, $sql1);

                                                    if($result){
                                                        while($row1 = odbc_fetch_array($result1)){
                                                            $pcs2 = "";
                                                            if($row1['TestQty']>1){
                                                                $pcs2 = "PCS";
                                                            }
                                                            else{
                                                                $pcs2 = "PC";
                                                            }
                                                            $output.='
                                                            <p style="line-height:7px;"><label class="text-primary" style="font-weight:bold;">'.$row1['Test'].'</span> :  </label>&nbsp;&nbsp;<span class="text-secondary">'.$row1['TestQty'].' '.$pcs2.'</span></p>
                                                            ';
                                                        }
                                                    }

                                                    if($row['Remarks'] != null || $row['Remarks'] != ''){
                                                        $output.='
                                                        <p style="line-height:9px;"><label class="text-primary" style="font-weight:bold;">Remarks : </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['Remarks'].'</span></p>
                                                        ';
                                                    }
                                        $output.='</div>
                                            </div>

                                            <hr class="bg-light">';
                                                
                                            
                                        }

                                        else if($row['UserTestCategory']==2){
                                            $UserTest = "User Test";

                                            $output.='

                                            <div class="row">
                                                <div class="col-md-12">
                                                <p><label class="text-primary" style="font-weight:bold;">'.$row['TestPlanNo'].': </label>&nbsp;&nbsp;<span class="badge bg-light-primary">USER TEST | '.$row['TotalQty'].' '.$pcs.'</span></p>';
                                                $sql1 = "select tt.Test, tpd.TestQty from TestPlanDetails_tbl tpd ";
                                                $sql1.="join TestSpecialTest_tbl tst ON tpd.TestPlanDetailsID = tst.TestPlanDetailID ";
                                                $sql1.="join TestTable_tbl tt ON tst.TestTableID = tt.TestTableID ";
                                                $sql1.="where tpd.TestPlanID =  ".$row['TestPlanID']." ";
                                                $result1 = odbc_exec($connServer, $sql1);
                                                $counter_result = odbc_num_rows($result1);
                                                $actual_count = 1;

                                                $data = "";
                                                if($result){
                                                    $row_count = 1;
                                                    while($row1 = odbc_fetch_array($result1)){
                                                        $data .= $row1['Test'];
                                                        if ($row_count < $counter_result) {
                                                            $data .= ' > ';
                                                        }
                                                        $row_count++;
                                                    }
                                                }

                                                $output.='
                                                            <p style="line-height:7px;"><label class="text-primary" style="font-weight:bold;">
                                                                '.rtrim($data).'
                                                            </p>
                                                        ';

                                                if($row['Remarks'] != null || $row['Remarks'] != ''){
                                                    $output.='
                                                        <p style="line-height:9px;"><label class="text-primary" style="font-weight:bold;">Remarks : </label>&nbsp;&nbsp;<span class="text-secondary"> '.$row['Remarks'].'</p>
                                                    ';
                                                }

                                    $output.='</div>
                                            </div>

                                            <hr class="bg-light">';

                                        }

                                        else if($row['UserTestCategory']==3){
                                            $UserTest = "Selected Test";

                                            $output.='
                                            <div class="row">
                                                <div class="col-md-12">
                                                <p><label class="text-primary" style="font-weight:bold;">'.$row['TestPlanNo'].': </label>&nbsp;&nbsp;<span class="badge bg-light-primary">SELECTED TEST | '.$row['TotalQty'].' '.$pcs.'</span></p>';
                                                $sql1 = "select tt.Test, tpd.TestQty from TestPlanDetails_tbl tpd ";
                                                $sql1.="join TestSpecialTest_tbl tst ON tpd.TestPlanDetailsID = tst.TestPlanDetailID ";
                                                $sql1.="join TestTable_tbl tt ON tst.TestTableID = tt.TestTableID ";
                                                $sql1.="where tpd.TestPlanID =  ".$row['TestPlanID']." ";
                                                $result1 = odbc_exec($connServer, $sql1);
                                                $counter_result = odbc_num_rows($result1);
                                                $actual_count = 1;

                                                $data = "";
                                                if($result){
                                                    $row_count = 1;
                                                    while($row1 = odbc_fetch_array($result1)){
                                                        $data .= $row1['Test'];
                                                        if ($row_count < $counter_result) {
                                                            $data .= ' • ';
                                                        }
                                                        $row_count++;
                                                    }
                                                }

                                                $output.='
                                                        <p style="line-height:7px;"><label class="text-primary" style="font-weight:bold;">
                                                                '.rtrim($data).'
                                                            </p>
                                                        ';

                                                if($row['Remarks'] != null || $row['Remarks'] != ''){
                                                    $output.='
                                                    <p style="line-height:9px;"><label class="text-primary" style="font-weight:bold;">Remarks : </label>&nbsp;&nbsp;<span class="text-secondary"> '.$row['Remarks'].'</p>
                                                    ';
                                                }

                                        $output.='</div>
                                            </div>

                                            <hr class="bg-light">';

                                        }

                                        else if($row['UserTestCategory']==4){
                                            $UserTest = "Benchmarking";

                                            $output.='
                                            <div class="row">
                                                <div class="col-md-12">
                                                <p><label class="text-primary" style="font-weight:bold;">'.$row['TestPlanNo'].': </label>&nbsp;&nbsp;<span class="badge bg-light-primary">BENCHMARKING | '.$row['TotalQty'].' '.$pcs.'</span></p>
                                                    <p style="line-height:7px;"><label class="text-primary" style="font-weight:bold;">Teardown </label></p>';

                                                    if($row['Remarks'] != null || $row['Remarks'] != ''){
                                                        $output.='
                                                        <p style="line-height:9px;"><label class="text-primary" style="font-weight:bold;">Remarks : </label>&nbsp;&nbsp;<span class="text-secondary"> '.$row['Remarks'].'</span></p>';
                                                    }
                                        $output.='</div>
                                            </div>

                                            <hr class="bg-light">';
                                        }
                                        
                                    }
                                }

                            }

                            
                        $output.='</div>
                            ';
                        }
                $output .= '
                    </div>
                </div>
                ';
        }

        echo json_encode($output);

    }

    else if($_POST['action']=='FetchPrio'){
        $prioQuery = "select * from Prioritization_tbl where PrioritizationID != 1 and IsDeleted = 0 and IsActive = 1 ";
        $prioResult = odbc_exec($connServer, $prioQuery);

        $output = '<option value="0" selected disabled>Choose...</option>';
        if($prioResult){
            while($prioRow = odbc_fetch_array($prioResult)){
                $output .= '<option value="'.$prioRow['PrioritizationID'].'">'.$prioRow['Prioritization'].'</option>';
            }
        }

        echo json_encode($output);
    }

    else if($_POST['action']=='ApproveBtr'){
        $RequestID = isset($_POST["RequestID"]) ? $_POST["RequestID"] : null;
        $PriorityID = isset($_POST["PriorityID"]) ? $_POST["PriorityID"] : null;
        $Remarks = isset($_POST["Remarks"]) ? $_POST["Remarks"] : null;
        $EmployeeID = isset($_COOKIE["BTL_employeeID"]) ? $_COOKIE["BTL_employeeID"] : null;

        $output = 0;

        $data = array($RequestID, 6, $Remarks, $EmployeeID);
        $Query = "insert into RequestStatus_tbl (RequestID, StatusID, Remarks, EmployeeID) values (?, ?, ?, ?) ";
        $stmt = odbc_prepare($connServer, $Query);
        $result = odbc_execute($stmt, $data);

        if($result){
            $output = 1;

            $dataUpdate = array($PriorityID, $RequestID);
            $updateQuery = "update Request_tbl set PrioritizationID = ? where RequestID = ? ";
            $stmtupdate = odbc_prepare($connServer, $updateQuery);
            $updateResult = odbc_execute($stmtupdate, $dataUpdate);

            if($updateResult){
                $output = 1;
            }
            else{
                $output = 0;
            }
        }
        else{
            $output = 0;
        }

        echo json_encode($output);
    }

    else if($_POST['action']=='RevisedBtr'){
        $RequestID = isset($_POST["RequestID"]) ? $_POST["RequestID"] : null;
        $Remarks = isset($_POST["Remarks"]) ? $_POST["Remarks"] : null;
        $EmployeeID = isset($_COOKIE["BTL_employeeID"]) ? $_COOKIE["BTL_employeeID"] : null;

        $output = 0;

        $data = array($RequestID, 7, $Remarks, $EmployeeID);
        $Query = "insert into RequestStatus_tbl (RequestID, StatusID, Remarks, EmployeeID) values (?, ?, ?, ?) ";
        $stmt = odbc_prepare($connServer, $Query);
        $result = odbc_execute($stmt, $data);

        if($result){
            $output = 1;
        }
        else{
            $output = 0;
        }

        echo json_encode($output);
    }

    else if($_POST['action']=='ViewBtrFooterBtnContent'){
        $RequestID = isset($_POST["ID"]) ? $_POST["ID"] : null;
        $StatusID = 0;
        $Sql = "select Top 1 StatusID as Status from RequestStatus_tbl where RequestID = ".$RequestID." and IsActive = 1 and IsDeleted = 0 order by DateCreated DESC ";
        $result = odbc_exec($connServer, $Sql);
        $StatusIDRow = odbc_fetch_array($result);
        $StatusID = $StatusIDRow['Status'];
        
        $output = "";
        if($StatusID==2 ||  $StatusID==7){
            $output .= '<div class="container text-center">
                            <button type="button" class="btn btn-light-warning" onclick="Revision()">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">For Revision</span>
                            </button>
                            <button type="button" class="btn btn-primary ml-1" onclick="Approve()">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Approve</span>
                            </button>
                        </div>';
        }
        else if($StatusID==6){
            $output .= '<div class="container text-center">
                            <button type="button" class="btn btn-sm btn-light-secondary"
                                data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                        </div>';
        }

        echo json_encode($output);
    }

    else if($_POST['action']=='waterBathsMapping'){
        //Water Bath 1
        $waterBathController = new WaterBathController($connServer);
       $waterBath1 = $waterBathController->generateWaterBathOutputMapping(1, 6);
       $waterBath2 = $waterBathController->generateWaterBathOutputMapping(2, 6);
       $waterBath4 = $waterBathController->generateWaterBathOutputMapping(3, 6);
       $waterBath5 = $waterBathController->generateWaterBathOutputMapping(13, 6);
       $waterBath6 = $waterBathController->generateWaterBathOutputMapping(14, 6);
       $waterBath7 = $waterBathController->generateWaterBathOutputMapping(10, 5);
       $waterBath8 = $waterBathController->generateWaterBathOutputMapping(11, 5);
       $waterBath10 = $waterBathController->generateWaterBathOutputMapping(15, 3);
       $waterBath11 = $waterBathController->generateWaterBathOutputMapping(9, 6);
       $waterBath12 = $waterBathController->generateWaterBathOutputMapping(8, 6);
       $waterBath13 = $waterBathController->generateWaterBathOutputMapping(7, 6);
       $waterBath14 = $waterBathController->generateWaterBathOutputMapping(5, 6);
       $waterBath15 = $waterBathController->generateWaterBathOutputMapping(6, 6);
       $waterBath16 = $waterBathController->generateWaterBathOutputMapping(4, 6);
       $waterBath17 = $waterBathController->generateWaterBathOutputMapping(18, 2);
       $waterBath18 = $waterBathController->generateWaterBathOutputMapping(19, 2);
       $waterBath19 = $waterBathController->generateWaterBathOutputMapping(20, 2);
       $waterBath20 = $waterBathController->generateWaterBathOutputMapping(21, 2);
       $waterBath9 = $waterBathController->generateWaterBathOutputWithBlankMapping(12,  3);
       $waterBathRCT_RCN = $waterBathController->generateWaterBathOutputRCTandRCNMapping(16, 17, 8);

       $arr = array(
            'WaterBath1' => $waterBath1,
            'WaterBath2' => $waterBath2,
            'WaterBath4' => $waterBath4,
            'WaterBath5' => $waterBath5,
            'WaterBath6' => $waterBath6,
            'WaterBath7' => $waterBath7,
            'WaterBath8' => $waterBath8,
            'WaterBath10' => $waterBath10,
            'WaterBath11' => $waterBath11,
            'WaterBath12' => $waterBath12,
            'WaterBath13' => $waterBath13,
            'WaterBath14' => $waterBath14,
            'WaterBath15' => $waterBath15,
            'WaterBath16' => $waterBath16,
            'WaterBath17' => $waterBath17,
            'WaterBath18' => $waterBath18,
            'WaterBath19' => $waterBath19,
            'WaterBath20' => $waterBath20,
            'WaterBath9' => $waterBath9,
            'WaterBathRcnRct' => $waterBathRCT_RCN
       );

       echo json_encode($arr);

    }

    else if($_POST['action'] == 'WBCardCounter'){
        $vacant = 0;
        $inUSe = 0;
        $defective = 0;

        $vacantSql = "select count(*) as vacant from WaterBathCellNo_tbl cellNo
        cross apply(
            select top 1 WaterBathCellStatusCategoryID from WaterBathCellStatus_tbl where WaterBathCellNoID = cellNo.WaterBathCellNoID order by DateCreated 
            DESC
        ) as status
        where status.WaterBathCellStatusCategoryID = 1 ";
        $vacantResult = odbc_exec($connServer, $vacantSql);
        $vacantRow = odbc_fetch_array($vacantResult);
        $vacant = $vacantRow['vacant'];

        $InUSeSql = "select count(*) as InUSe from WaterBathCellNo_tbl cellNo
        cross apply(
            select top 1 WaterBathCellStatusCategoryID from WaterBathCellStatus_tbl where WaterBathCellNoID = cellNo.WaterBathCellNoID order by DateCreated 
            DESC
        ) as status
        where status.WaterBathCellStatusCategoryID = 2 ";
        $InUSeResult = odbc_exec($connServer, $InUSeSql);
        $InUSeRow = odbc_fetch_array($InUSeResult);
        $InUSe = $InUSeRow['InUSe'];

        $defectiveSql = "select count(*) as defective from WaterBathCellNo_tbl cellNo
        cross apply(
            select top 1 WaterBathCellStatusCategoryID from WaterBathCellStatus_tbl where WaterBathCellNoID = cellNo.WaterBathCellNoID order by DateCreated 
            DESC
        ) as status
        where status.WaterBathCellStatusCategoryID = 3 ";
        $defectiveResult = odbc_exec($connServer, $defectiveSql);
        $defectiveRow = odbc_fetch_array($defectiveResult);
        $defective = $defectiveRow['defective'];

        $arr = array(
            'vacant' => $vacant,
            'use' => $InUSe,
            'defective' => $defective
        );

        echo json_encode($arr);
    }
}
?>