<?php
include "../../Database/db_connection.php";
include "../../Php_function/functions.php";
require 'class/WaterBathController.php';

date_default_timezone_set("Asia/Manila");
$date = date('Y-m-d');//date uploaded
$dateNav = date('M d, Y');
$time =  date("H:i:s");
$time2 = date("h:i A");
$day = date("l");
$month = date("m");
$year = date("Y"); //year
$timestamp = $date.' '.$time;
$timestamp2 = $date.' '.$time2;
$date2 = date('Y-m-d H:i:s');

if(isset($_POST['action'])){
    if($_POST['action']=='logout'){
        $result = 0;
        if(Logout()){
            $result = Logout();
        }

        echo json_encode($result);
    }
    else if($_POST['action']=='QrReader'){
        $output = '';
        $statusTxt = '';
        $RequestID = 0;
        $QRVal = "";
        $result = 0;
        $statusID = 0;

        if(isset($_POST['QRValue'])){
            $QRVal = $_POST['QRValue'];
        }

        $sql = "
        select top 1 r.RequestID, r.RequestSysID, s.StatusTxt, s.StatusID
        from Request_tbl r
        join RequestStatus_tbl rs ON r.RequestID = rs.RequestID
        join Status_tbl s ON rs.StatusID = s.StatusID
        where r.RequestSysID = CAST(? AS varchar(max))
        order by rs.DateCreated desc ";
        $params = array($QRVal);

        $execute = odbc_prepare($connServer, $sql);

        if (!$execute) {
            die("Error preparing query: " . odbc_error($connServer));
        }

        $success = odbc_execute($execute, $params);

        if (!$success) {
            die("Error executing query: " . odbc_error($connServer));
        }

        $count = odbc_num_rows($execute);

        if ($count > 0) {
            $result = 1;
            while ($resultRow = odbc_fetch_array($execute)){
                $output = $resultRow['RequestSysID'];
                $RequestID = $resultRow['RequestID'];
                $statusTxt = $resultRow['StatusTxt'];
                $statusID = $resultRow['StatusID'];
            }
            
        }

        $arr = array(
            'QrValue' => $output,
            'Result' => $result,
            'RequestID' => $RequestID,
            'Status' => $statusTxt,
            'StatusID' => $statusID
        );

        echo json_encode($arr);
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
                $statusID = $row['StatusID'];
                $RequstSystemID = $row['RequestSysID'];
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
                                <div class="divider-text text-primary">REQUISITION</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><label class="text-primary" style="font-weight:bold;">REQUESTOR: </label>&nbsp;&nbsp;';
                                    $requestorQuery = "select emp.Fname, emp.Lname
                                    from Request_tbl request
                                    join Requestor_tbl requestor ON request.RequisitionID = requestor.RequisitionID
                                    join Employee_tbl emp ON requestor.EmployeeID = emp.EmployeeID
                                    where request.RequestID = ".$requestID." and requestor.IsActive = 1 and requestor.IsDeleted = 0 ";
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
                                    <p><label class="text-primary" style="font-weight:bold;">Battery Size: </label>&nbsp;&nbsp;<span class="text-secondary">B20</span></p>
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
    else if($_POST['action']=='Receiving'){
        $employeeID = $_COOKIE['BTL_employeeID'];
        $dataHolder = array();
        $ID = '';
        $output = 0;
        $RequestID = isset($_POST['RequestID']) ? $_POST['RequestID'] : null;

        $checkSQL = "select top 1 s.StatusID
        from Request_tbl r
        join RequestStatus_tbl rs ON r.RequestID = rs.RequestID
        join Status_tbl s ON rs.StatusID = s.StatusID
        where r.RequestID = ".$RequestID."
        order by rs.DateCreated desc ";
        $checkResult = odbc_exec($connServer, $checkSQL);

        $hasRecivedStat = 0;
        if($checkResult){
            while($checkRow = odbc_fetch_array($checkResult)){
                $statusID = $checkRow['StatusID'];
                if($statusID==8){
                    $hasRecivedStat = 1;
                }
            }

            if($hasRecivedStat==0){
                $params = array($RequestID, 1, 0);
                $sql = "select TestPlanID, TotalQty, UserTestCategory from TestPlan_tbl where RequestID = ? and IsActive = ? and IsDeleted = ? ";
                $result = odbc_prepare($connServer, $sql);
                $execute = odbc_execute($result, $params);

                if($execute){
                    while ($ResultRow = odbc_fetch_array($result)){
                        $TestPlanID = $ResultRow['TestPlanID'];
                        $UserTestCategory = $ResultRow['UserTestCategory'];
                        $TestPlanQty = $ResultRow['TotalQty'];

                        if($UserTestCategory==1){
                            $params2 = array($TestPlanID, 1, 0);
                            $sql2 = "select TestPlanDetailsID, TestQty from TestPlanDetails_tbl where TestPlanID = ? and IsActive = ? and IsDeleted = ? ";
                            $result2 = odbc_prepare($connServer, $sql2);
                            $execute2 = odbc_execute($result2, $params2);

                            if($execute2){
                                while($row = odbc_fetch_array($result2)){
                                    $TestPlanDetailsID = $row['TestPlanDetailsID'];
                                    $TestQty = $row['TestQty'];

                                    $trimYear = substr($year,2);
                                    $montNo = sprintf("%02d", $month);
                                    $ID =  'T'.$trimYear.$montNo.'-'.sprintf('%04d', $TestPlanDetailsID);

                                    $params3 = array($ID, $TestPlanDetailsID);
                                    $query = "update TestPlanDetails_tbl set TestSysID = ?, DateModified = getdate() where TestPlanDetailsID = ? ";
                                    $result3 = odbc_prepare($connServer, $query);
                                    $execute3 = odbc_execute($result3, $params3);

                                    if($execute3){
                                        for($i=0; $i < $TestQty; $i++){
                                            $SampleID = "";
                                            $sql3 = "select * from TestSamples_tbl ";
                                            $execute4 = odbc_exec($connServer, $sql3);
                                            $counter = odbc_num_rows($execute4);
                                            $incrementID = 1;
                                            if($counter==0){
                                                $trimYear = substr($year,2);
                                                $montNo = sprintf("%02d", $month);
                                                $SampleID =  'PD'.$trimYear.$montNo.'-'.sprintf('%04d', $incrementID);

                                                $params4 = array($TestPlanDetailsID, $SampleID, 1);
                                                $query2 = "Insert into TestSamples_tbl(TestPlanDetailsID, TestSamplesNo, IsPrint) values(?, ?, ?) ";
                                                $result4 = odbc_prepare($connServer, $query2);
                                                $execute5 = odbc_execute($result4, $params4);

                                                if($execute5){
                                                    $output = 1;
                                                }
                
                                            }
                                            else{
                                                $sql4 = "select max(TestSamplesID) as MaxID from TestSamples_tbl ";
                                                $execute6 = odbc_exec($connServer, $sql4);

                                                if($execute6){
                                                    $resultRow = odbc_fetch_array($execute6);
                                                    $maxID = $resultRow['MaxID'];
                                                    $incrementID = $maxID + 1;

                                                    $trimYear = substr($year,2);
                                                    $montNo = sprintf("%02d", $month);
                                                    $SampleID =  'PD'.$trimYear.$montNo.'-'.sprintf('%04d', $incrementID);

                                                    $params4 = array($TestPlanDetailsID, $SampleID, 1);
                                                    $query2 = "Insert into TestSamples_tbl(TestPlanDetailsID, TestSamplesNo, IsPrint) values(?, ?, ?) ";
                                                    $result4 = odbc_prepare($connServer, $query2);
                                                    $execute5 = odbc_execute($result4, $params4);

                                                    if($execute5){
                                                        $output = 1;
                                                    }
                                                }
                                            }
                                            
                                            $LastID = GetLast_Id($connServer);

                                            $datalog = array(
                                                'TestSamplesID' => $LastID,
                                                'TestSamplesNo' => $SampleID,
                                                'UserCategoryID' => $UserTestCategory
                                            );

                                            array_push($dataHolder, $datalog);
                                            
                                        }
                                    }

                                }
                            }
                        }
                        else if($UserTestCategory > 1){
                            $params2 = array($TestPlanID, 1, 0);
                            $sql2 = "select TestPlanDetailsID, TestQty from TestPlanDetails_tbl where TestPlanID = ? and IsActive = ? and IsDeleted = ? ";
                            $result2 = odbc_prepare($connServer, $sql2);
                            $execute2 = odbc_execute($result2, $params2);

                            if($execute2){
                                while($row = odbc_fetch_array($result2)){
                                    $TestPlanDetailsID = $row['TestPlanDetailsID'];
                                    $TestQty = $row['TestQty'];

                                    $trimYear = substr($year,2);
                                    $montNo = sprintf("%02d", $month);
                                    $ID =  'T'.$trimYear.$montNo.'-'.sprintf('%04d', $TestPlanDetailsID);

                                    $params3 = array($ID, $TestPlanDetailsID);
                                    $query = "update TestPlanDetails_tbl set TestSysID = ?, DateModified = getdate() where TestPlanDetailsID = ? ";
                                    $result3 = odbc_prepare($connServer, $query);
                                    $execute3 = odbc_execute($result3, $params3);

                                    if($execute3){
                                        for($i=0; $i < $TestPlanQty; $i++){
                                            $SampleID = "";
                                            $sql3 = "select * from TestSamples_tbl ";
                                            $execute4 = odbc_exec($connServer, $sql3);
                                            $counter = odbc_num_rows($execute4);
                                            $incrementID = 1;
                                            if($counter==0){
                                                $trimYear = substr($year,2);
                                                $montNo = sprintf("%02d", $month);
                                                $SampleID =  'PD'.$trimYear.$montNo.'-'.sprintf('%04d', $incrementID);

                                                $params4 = array($TestPlanDetailsID, $SampleID, 1);
                                                $query2 = "Insert into TestSamples_tbl(TestPlanDetailsID, TestSamplesNo, IsPrint) values(?, ?, ?) ";
                                                $result4 = odbc_prepare($connServer, $query2);
                                                $execute5 = odbc_execute($result4, $params4);

                                                if($execute5){
                                                    $output = 1;
                                                }
                
                                            }
                                            else{
                                                $sql4 = "select max(TestSamplesID) as MaxID from TestSamples_tbl ";
                                                $execute6 = odbc_exec($connServer, $sql4);

                                                if($execute6){
                                                    $resultRow = odbc_fetch_array($execute6);
                                                    $maxID = $resultRow['MaxID'];
                                                    $incrementID = $maxID + 1;

                                                    $trimYear = substr($year,2);
                                                    $montNo = sprintf("%02d", $month);
                                                    $SampleID =  'PD'.$trimYear.$montNo.'-'.sprintf('%04d', $incrementID);

                                                    $params4 = array($TestPlanDetailsID, $SampleID, 1);
                                                    $query2 = "Insert into TestSamples_tbl(TestPlanDetailsID, TestSamplesNo, IsPrint) values(?, ?, ?) ";
                                                    $result4 = odbc_prepare($connServer, $query2);
                                                    $execute5 = odbc_execute($result4, $params4);

                                                    if($execute5){
                                                        $output = 1;
                                                    }
                                                }
                                            }
                                            
                                            $LastID = GetLast_Id($connServer);

                                            $datalog = array(
                                                'TestSamplesID' => $LastID,
                                                'TestSamplesNo' => $SampleID,
                                                'UserCategoryID' => $UserTestCategory
                                            );

                                            array_push($dataHolder, $datalog);
                                            
                                        }
                                    }

                                }
                            }  
                        }
                    }
                }

                if($output == 1){
                    $params5 = array($RequestID, 8, 'Received', $employeeID);
                    $query3 = "Insert into RequestStatus_tbl(RequestID, StatusID, Remarks, EmployeeID) values(?, ?, ?, ?) ";
                    $result5 = odbc_prepare($connServer, $query3);
                    $execute7 = odbc_execute($result5, $params5);
                }

                $arr = array(
                    'output' => $output,
                    'container' => $dataHolder,
                    'RequestID' => $RequestID,
                    'HasReceived' => $hasRecivedStat
                );
                
                echo json_encode($arr);
            }
            else{
                $arr = array(
                    'output' => $output,
                    'container' => $dataHolder,
                    'RequestID' => $RequestID,
                    'HasReceived' => $hasRecivedStat
                );
                
                echo json_encode($arr);
            }
        }

    }
    else if($_POST['action']=="qr"){
        $TestSampleID = 0;
        $TestSampleNo = "";
        $dataQRUrl = "";
        $result_result = 0;

        if(isset($_POST['TestSampleID'])){
            $TestSampleID = $_POST['TestSampleID'];
        }

        if(isset($_POST['TestSampleNo'])){
            $TestSampleNo = $_POST['TestSampleNo'];
        }

        if(isset($_POST['qrURL'])){
            $dataQRUrl = $_POST['qrURL'];
        }

        $QRname = $TestSampleNo.'.png';
        $subfolder = '../../SamplesQRuploads/';

        $sql = "SELECT * from SampleQr_tbl where TestSamplesID = ".$TestSampleID."  and IsActive = 1 and IsDeleted = 0 ";
        $resultSql = odbc_exec($connServer, $sql);
        $count = odbc_num_rows($resultSql);

        if($count==0){
            $query = "INSERT INTO SampleQr_tbl(TestSamplesID, SampleQrName) ";
            $query .="values(".$TestSampleID.", '".$QRname."') ";

            $result = odbc_exec($connServer, $query);

            if($result){
                $result_result = 1;
                file_put_contents($subfolder.$QRname,file_get_contents($dataQRUrl));
            }
            else{
                $result_result = 2;
            }
        }

        // $requestID_holder = 0;
        // $requestSql = "select * from Request_tbl where RequestSysID = '".$SystemBtrID."' ";
        // $requestSqlResult = odbc_exec($connServer, $requestSql);
        // $rowRequest = odbc_fetch_array($requestSqlResult);
        // $requestID_holder = $rowRequest['RequestID'];

        $arr = array(
            'result' => $result_result,
            'imageData' =>$dataQRUrl
            // 'requestID' => $requestID_holder
        );
        echo json_encode($arr);
    }
    else if($_POST['action']=="displaySample"){
        $data = isset($_POST['data']) ? $_POST['data'] : null;
        $dataHolder = array();
        $count = count($data);
        $output = '';
        $SampleQty = '';
        if($count > 1){
            $SampleQty = $count.' PCS';
        }
        else{
            $SampleQty = $count.' PC';
        }

        for($x = 0; $x < $count; $x++){
            $SampleID = $data[$x]['TestSamplesID'];
            $userCategoryID = $data[$x]['UserCategoryID'];
            $params = array($SampleID);
            $function_query = "select sample.TestSamplesNo, details.TestSysID, test.Test, qr.SampleQrName, sample.TestPlanDetailsID
            from TestSamples_tbl sample
            join TestPlanDetails_tbl details ON sample.TestPlanDetailsID = details.TestPlanDetailsID
            join TestTable_tbl test ON details.TestTableID = test.TestTableID
            join SampleQr_tbl qr ON sample.TestSamplesID = qr.TestSamplesID where sample.TestSamplesID = ? ";
            $result = odbc_prepare($connServer, $function_query);
            if ($result) {
                // Execute the SQL statement
                $execute = odbc_execute($result, $params);
    
                if ($execute) {
                    // Fetch the results
                    $row = odbc_fetch_array($result);
    
                    $SampleNo = $row['TestSamplesNo'];
                    $TestID = $row['TestSysID'];
                    $Test = $row['Test'];
                    $TestQrDirectory = '../SamplesQRuploads/'.$row['SampleQrName'];

                    $output .= '
                        <tr>
                            <td>
                                <div class="container mt-3 text-center position-relative">
                                    <img src="'.$TestQrDirectory.'" width="50" class="img-fluid" alt="">
                                    <p style="font-size:14px;font-weight:bold;">'.$SampleNo.'</p>
                                </div>
                                
                            </td>
                            <td>';
                            if($userCategoryID==1){
                                $output .= '
                                <div class="container text-center mt-4">
                                    <p style="line-height:2px;">'.$Test.'</p>
                                    <p style="font-size:12px;font-weight:bold;">'.$TestID.'</p>
                                </div> ';
                            }
                            else if($userCategoryID > 1){
                                $output .= '
                                <div class="container text-center mt-4">
                                    <p style="line-height:2px;">Special Testing</p>
                                </div> ';
                            }
                            
                    $output .= '        </td>
                        </tr>
                    ';

                    $arr = array(
                        'SampleNo' => $SampleNo,
                        'TestID' => $TestID,
                        'Test' => $Test,
                        'Directory' => $TestQrDirectory
                    );

                    array_push($dataHolder, $arr);

                } else {
                    // Handle execution error
                    echo "Error executing SQL query.";
                }
            } else {
                // Handle preparation error
                echo "Error preparing SQL query.";
            }

        }

        $arrData = array(
            'sample' => $output,
            'container' => $dataHolder,
            'sampleCount' => $SampleQty
        );

        echo json_encode($arrData);
    }
    else if($_POST['action']=='TestRequest_tbl'){
        $tabID = isset($_POST['tabID']) ? $_POST['tabID'] : null;
        $search = "%".$_POST["search"]["value"]."%";

        $column = array("RequestSysID", "", "RequestDate", "ProjectName", "ClassificationTxt", "TestObjective", "Overalltotal", "", "Overalltotal", "StatusTxt", "" );

        $query = "select request.RequestID, request.RequestSysID, requisition.ProjectName, requisition.TestObjective, classification.ClassificationTxt, request.DateCreated as RequestDate, Total.Overalltotal, Status.StatusTxt, Status.StatusID, request.PrioritizationID, reqSpecialInstruct.SpecialInstruction ";
        $query .="from Request_tbl request ";
        $query .="join Requisition_tbl requisition ON request.RequisitionID = requisition.RequisitionID ";
        $query .="left join BatteryDetails_tbl batdetails ON request.RequisitionID = batdetails.RequisitionID ";
        $query .="join Classification_tbl classification ON requisition.ClassificationID = classification.ClassificationID ";
        $query .="left join RequestSpecialInstruction_tbl reqSpecialInstruct ON request.RequisitionID = reqSpecialInstruct.RequisitionID ";
        // $query .="join RequestStatus_tbl reqstat ON request.RequestID = reqstat.RequestID ";
        $query .="cross apply (select sum(TotalQty) as Overalltotal from TestPlan_tbl where RequestID = request.RequestID and IsActive = 1 and IsDeleted = 0) as Total ";
        $query .="cross apply (
            select top 1 res.StatusID, stat.StatusTxt  from RequestStatus_tbl res join Status_tbl stat ON res.StatusID = stat.StatusID where res.RequestID = request.RequestID order by res.DateCreated DESC
        ) as Status ";
        $query .="WHERE request.IsActive = 1 and request.IsDeleted = 0 ";
        if($tabID==1){
            $query .="and Status.StatusID in (6,8) ";
        }
        else{
            $query .="and Status.StatusID = 6 ";
        }
        
        

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

            $query .='ORDER BY request.RequestID DESC ';
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

            if($StatusID==8){
                $statusContainer = '<div class="badges">
                                    <span class="badge bg-light-success">Received</span>
                                </div>';
            }
            else{
                $statusContainer = '<div class="badges">
                                    <span class="badge bg-light-info">For Receiving</span>
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

            if($tabID==1){
                $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                <button type="button" onclick="ViewBtr('.$RequestID.')" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                            </div>';
            }
            else{
                $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                <button type="button" onclick="ViewBtr('.$RequestID.')" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                <button type="button" onclick="ViewRequest('.$RequestID.')" class="btn btn-sm btn-primary"><i class="bi bi-check"></i></button>
                            </div>';
            }
            

            
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
    else if($_POST['action']=='process_tbl'){
        $tabID = isset($_POST['tabID']) ? $_POST['tabID'] : null;
        $search = "%".$_POST["search"]["value"]."%";
        $column = array("TestSysID", "TestSamplesNo", "BatteryCode", "", "Test", "DateCreated", "", "", "" );
        $query = "select samples.TestSamplesID, samples.DateCreated as RecievedDate, testDetails.TestSysID, testDetails.TestPlanDetailsID, testDetails.TestTableID, samples.TestSamplesNo, batdetails.BatteryCode, test.Test, Status.DateCreated, Status.StatusTxt, Status.StatusID, request.RequestID, request.PrioritizationID, reqSpecialInstruct.SpecialInstruction,AllocationStat.AllocationStatus, allocation.WaterBathCellNoID, testPlan.UserTestCategory, batdetails.BatteryTypeID, samples.StatusID ";
        $query .= "from TestSamples_tbl samples ";
        $query .= "join TestPlanDetails_tbl testDetails ON samples.TestPlanDetailsID = testDetails.TestPlanDetailsID ";
        $query .= "join TestTable_tbl test ON testDetails.TestTableID = test.TestTableID ";
        $query .= "join TestPlan_tbl testPlan ON testDetails.TestPlanID = testPlan.TestPlanID ";
        $query .= "join Request_tbl request ON testPlan.RequestID = request.RequestID ";
        $query .= "join Requisition_tbl requi ON request.RequisitionID = requi.RequisitionID ";
        $query .="left join RequestSpecialInstruction_tbl reqSpecialInstruct ON request.RequisitionID = reqSpecialInstruct.RequisitionID ";
        $query .="left join SamplesWaterBathAllocation_tbl allocation ON samples.TestSamplesID = allocation.TestSampleID and allocation.IsTransfer = 0 ";
        $query .= "left join BatteryDetails_tbl batdetails ON requi.RequisitionID = batdetails.RequisitionID ";
        $query .= "cross apply (
            select top 1 res.StatusID, stat.StatusTxt, stat.DateCreated  from RequestStatus_tbl res join Status_tbl stat ON res.StatusID = stat.StatusID where res.RequestID = request.RequestID order by res.DateCreated DESC
         ) as Status ";
         $query .= "cross apply (
            select 
            CASE WHEN EXISTS (SELECT * FROM SamplesWaterBathAllocation_tbl where TestSamplesID = samples.TestSamplesID and IsTransfer = 0) THEN 1 ELSE 0 END AS paramSetValue,
                (select top 1 WaterBathCellStatusCategoryID from WaterBathCellStatus_tbl where WaterBathCellNoID = allocation.WaterBathCellNoID
            order by DateCreated DESC) AS AllocationStatus
        ) AllocationStat ";
         $query .= "WHERE samples.IsActive = 1 and samples.IsDeleted = 0 ";
         if($tabID==3){
            $query .="and samples.StatusID = 8 ";
        }
        else{
            $query .="and samples.StatusID = 3 ";
        }

         if(isset($_POST["search"]["value"])){											
            if(!empty($_POST["search"]["value"])){
                $query .='AND ( CAST(testDetails.TestSysID AS VARCHAR) LIKE ? ';
                $query .='OR CAST(samples.TestSamplesNo AS VARCHAR) LIKE ? ';
                $query .='OR Status.StatusTxt LIKE ? ) ';
                // $query .='OR CAST(Total.Overalltotal AS VARCHAR) LIKE ?) ';
            }
        }

        if(isset($_POST["order"])){

            $query .='ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir']. ' ';
        } 
        else{

            $query .='ORDER BY samples.TestSamplesID DESC ';
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
            odbc_execute($result, array($search, $search, $search));
            $count = odbc_num_rows($result);
            // $result = odbc_fetch_array($stmt);
        }
        else{
            $table = odbc_exec($connServer, $query);
            $count = odbc_num_rows($table);
            $result = odbc_exec($connServer, $query.$query1);
        }
        $data = array();
        $n = 1;
        while($row = odbc_fetch_array($result)){
            $RequestID = $row['RequestID'];
            $TestSampleID  = $row['TestSamplesID'];
            $TestSysID  = $row['TestSysID'];
            $TestSamplesNo    = $row['TestSamplesNo'];
            $BatteryCode   = $row['BatteryCode'];
            $Test      = $row['Test'];
            $ReceivedDate = $row['RecievedDate'];
            $DateCreated     = $row['DateCreated'];
            $priorityID = $row['PrioritizationID'];
            $instruction = $row['SpecialInstruction'];
            $StatusID     = $row['StatusID'];
            $StatusTxt     = $row['StatusTxt'];
            $Assignedstatus = $row['AllocationStatus'];
            $TestTableID = $row['TestTableID'];
            $testPlanUserTestCategory = $row['UserTestCategory'];
            $batterytypeID = $row['BatteryTypeID'];
            $WaterBarhCellNoID = $row['WaterBathCellNoID'];

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
            where tp.RequestID = ".$RequestID." ";
            $requirementResult = odbc_exec($connServer, $requirementQuery);

            $RequirementsDiv = '<div>';
            while($requirementRow = odbc_fetch_array($requirementResult)){
                $RequirementsDiv .= '<div class="badges">
                                        <span class="badge bg-light-secondary">'.$requirementRow['Test'].'</span>
                                    </div>';
            }
            $RequirementsDiv .= '</div>';

            $statusContainer = '';

            $statusContainer = '<div class="badges">
                                    <span class="badge bg-light-info">For Receiving</span>
                                </div>';

            $requestorQuery = "select emp.Fname, emp.Lname
            from Request_tbl request
            join Requestor_tbl requestor ON request.RequisitionID = requestor.RequisitionID
            join Employee_tbl emp ON requestor.EmployeeID = emp.EmployeeID
            where request.RequestID = ".$RequestID." and requestor.IsActive = 1 and requestor.IsDeleted = 0 ";
            $requestorResult = odbc_exec($connServer, $requestorQuery);

            $requestorOutput = "";
            if($requestorResult){
                while($requestorRow = odbc_fetch_array($requestorResult)){
                   
                    $fname = GetNameInitials($requestorRow['Fname']);
                    $Lname = GetNameInitials($requestorRow['Lname']);
                    $nameInitials = $fname.$Lname;
                    $concatName = $requestorRow['Fname']." ".$requestorRow['Lname'];
                    
                    $requestorOutput .= '<div class="avatar avatar-sm  me-1" data-toggle="tooltip" data-placement="top" title="'.$concatName.'" id="my-tooltip-button" style="cursor:pointer;background:#babdbf;" >
                                            <span class="avatar-content">'.$nameInitials.'</span>
                                        </div>';
                }
            }
            $IshaveInitialRequirements = false;
            $initialRequirementRowCount = 0;
            if($batterytypeID==1){
                $initialRequirementSql = "select * from InitialMeasurementMF_tbl where TestSamplesID = ".$TestSampleID;
                $InitialRequirementsExec = odbc_exec($connServer, $initialRequirementSql);
                $initialRequirementRowCount = odbc_num_rows($InitialRequirementsExec);
            }
            else if($batterytypeID==2){
                $initialRequirementSql = "select * from InitialMeasurementLM_tbl where TestSamplesID = ".$TestSampleID;
                $InitialRequirementsExec = odbc_exec($connServer, $initialRequirementSql);
                $initialRequirementRowCount = odbc_num_rows($InitialRequirementsExec);
            }
            
            $sub_array = array();
            
            $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".$TestSysID." ".$priorityIcon." ".$instructionContainer."</span>";
            $sub_array[] = $TestSamplesNo;
            $sub_array[] = $BatteryCode;
            $sub_array[] = $requestorOutput;
            $sub_array[] =  '<div class="badges">
                                <span class="badge bg-light-secondary">'.$Test.'</span>
                            </div>';
            $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".date('M d, Y',strtotime($ReceivedDate))."</span>";
            if($testPlanUserTestCategory!=4){
                if($Assignedstatus == 2){
                    $sub_array[] =  '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="transferwAllocate('.$TestSampleID.', \''.$TestSamplesNo.'\')"><i class="bi bi-shuffle"></i> Transfer</button>
                                </div>';
                }
                else{
                    $sub_array[] =  '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="circuitAllocate('.$TestSampleID.', \''.$TestSamplesNo.'\')"><i class="bi bi-plus-circle"></i> Allocate</button>
                                </div>';
                }
            }
            else{
                $sub_array[] = '<div class="badges">
                                        <span class="badge bg-light-secondary">For TDL</span>
                                    </div>';
            }
            
            if($testPlanUserTestCategory!=4){
                if($tabID==3){
                    $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="TestPlan('.$TestSampleID.', '.$TestTableID.', '.$testPlanUserTestCategory.', '.$batterytypeID.', '.$RequestID.')"><i class="bi bi-calendar-range"></i> Set</button>
                                </div>';
                }
                else{
                    // $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                    //             <button type="button" class="btn btn-sm btn-outline-primary" onclick="TestPlan('.$TestSampleID.', '.$TestTableID.', '.$testPlanUserTestCategory.', '.$batterytypeID.', '.$RequestID.')"><i class="bi bi-pen"></i> Edit</button>
                    //             </div>';
                    if($testPlanUserTestCategory==1){
                        $testFormsSql = 'select top 1 ptp.TestPTPScheduleID, test.TestTableID, test.Test, Test.TestFormCategoryID from TestPTPSchedule_tbl ptp
                        join TestMSeries_tbl MseriesTest ON ptp.TestSourceID = MseriesTest.TestMSeriesID
                        join TestTable_tbl test ON MseriesTest.TestTableTestID = test.TestTableID
                        where ptp.TestSamplesID = '.$TestSampleID.' and test.IsHaveForm = 1 and ptp.TestStatus = 0 order by ptp.DateCreated ASC ';
                        $resultTestForm = odbc_exec($connServer, $testFormsSql);
                        $resultTestFormCount = odbc_num_rows($resultTestForm);
                        if($resultTestFormCount!=0){
                            if($resultTestForm){
                                $rowTestForm = odbc_fetch_array($resultTestForm);
                                $TestPTPScheduleID = $rowTestForm['TestPTPScheduleID'];
                                $currentTestForm = $rowTestForm['Test'];
                                $testTableID = $rowTestForm['TestTableID'];
                                $formCategoryID = $rowTestForm['TestFormCategoryID'];
    
                                $testDataRow = "select top 1 * from TestDataInput_tbl testData
                                join TestDataStatus_tbl stat ON testData.TestDataInputID = stat.TestDataInputID where testData.TestPTPScheduleID = ".$TestPTPScheduleID."  and testData.IsActive = 1 and testData.IsDeleted = 0 order by stat.DateCreated DESC ";
                                $TestPTProwExecute = odbc_exec($connServer, $testDataRow);
                                if($TestPTProwExecute){
                                    $checkforRowExistence = odbc_num_rows($TestPTProwExecute);
                                    $TestDataRow = odbc_fetch_array($TestPTProwExecute);
                                    $TestStatusID = 0;
                                    $haveRow = 0;
                                    $waterBathCellNoIDData = $WaterBarhCellNoID ? $WaterBarhCellNoID:0;
                                    if($checkforRowExistence !=0){
                                        $haveRow = 1;
                                        $TestStatusID = $TestDataRow['StatusID'];
                                        if($TestStatusID == 9){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-info"><i class="bi bi-info-circle"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 10){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-warning"><i class="bi bi-bootstrap-reboot"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 11){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-primary"><i class="bi bi-check2-circle"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 13){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-secondary"><i class="bi bi-journal-plus"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 14){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-light"><i class="bi bi-arrow-left-right"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 15){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-warning"><i class="bi bi-bootstrap-reboot"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        
                                    }
                                    else{
                                        $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                        <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.','.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm btn-outline-primary" style="position: relative;"><i class="bi bi-pen"></i> '.$currentTestForm.'</button>
                                        </div>';
                                    }
                                }
                            }
                        }
                        else{
                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-sm icon btn-light-success"><i class="bi bi-clipboard-check"></i> Done</button>
                                            </div>
                                            ';
                        }
                    }
                    else{
                        $testFormsSql = 'select top 1 ptp.TestPTPScheduleID, test.TestTableID, test.Test, Test.TestFormCategoryID from TestPTPSchedule_tbl ptp
                        join TestSpecialTest_tbl specialTest ON ptp.TestSourceID = specialTest.TestSpecialTestID
                        join TestTable_tbl test ON specialTest.TestTableID = test.TestTableID
                        where ptp.TestSamplesID = '.$TestSampleID.' and test.IsHaveForm = 1 and ptp.TestStatus = 0  order by ptp.DateCreated ASC ';
                        $resultTestForm = odbc_exec($connServer, $testFormsSql);
                        $resultTestFormCount = odbc_num_rows($resultTestForm);
                        if($resultTestFormCount!=0){
                            if($resultTestForm){
                                $rowTestForm = odbc_fetch_array($resultTestForm);
                                $TestPTPScheduleID = $rowTestForm['TestPTPScheduleID'];
                                $currentTestForm = $rowTestForm['Test'];
                                $testTableID = $rowTestForm['TestTableID'];
                                $formCategoryID = $rowTestForm['TestFormCategoryID'];
    
                                $testDataRow = "select top 1 *
                                from TestDataInput_tbl testData
                                cross apply (
                                    select top 1 StatusID, Remarks from TestDataStatus_tbl where TestDataInputID = testData.TestDataInputID order by DateCreated DESC
                                ) as status
                                where testData.TestPTPScheduleID = ".$TestPTPScheduleID." and testData.IsActive = 1 and testData.IsDeleted = 0 ";
                                $TestPTProwExecute = odbc_exec($connServer, $testDataRow);
                                if($TestPTProwExecute){
                                    $checkforRowExistence = odbc_num_rows($TestPTProwExecute);
                                    $TestDataRow = odbc_fetch_array($TestPTProwExecute);
                                    $TestStatusID = 0;
                                    $haveRow = 0;
                                    $waterBathCellNoIDData = $WaterBarhCellNoID ? $WaterBarhCellNoID:0;
                                    if($checkforRowExistence !=0){
                                        $haveRow = 1;
                                        $TestStatusID = $TestDataRow['StatusID'];
                                        if($TestStatusID == 9){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-info"><i class="bi bi-info-circle"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 10){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-warning"><i class="bi bi-bootstrap-reboot"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 11){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-primary"><i class="bi bi-check2-circle"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 13){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-secondary"><i class="bi bi-journal-plus"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 14){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-light"><i class="bi bi-arrow-left-right"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                        else if($TestStatusID == 15){
                                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm icon btn-warning"><i class="bi bi-bootstrap-reboot"></i> '.$currentTestForm.'</button>
                                            </div>
                                            ';
                                        }
                                    }
                                    else{
                                        $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                        <button type="button" onclick="TestDataInput('.$TestPTPScheduleID.', '.$TestSampleID.', \''.$currentTestForm.'\', '.$testTableID.', '.$formCategoryID.', \''.$TestSamplesNo.'\', '.$waterBathCellNoIDData.', '.$TestStatusID.', '.$haveRow.')" class="btn btn-sm btn-outline-primary" ><i class="bi bi-pen"></i> '.$currentTestForm.'</button>
                                        </div>';
                                    }
                                }
    
                            }
                        }
                        else{
                            $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-sm icon btn-light-success"><i class="bi bi-clipboard-check"></i> Done</button>
                                            </div>
                                            ';
                        }
                    }
                }
                
            }
            else{
                $sub_array[] = '<div class="badges">
                                        <span class="badge bg-light-secondary">For TDL</span>
                                    </div>';
            }
            

            if($StatusID==6){
                $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                <button type="button" onclick="ViewBtr('.$TestSampleID.')" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                            </div>';
            }
            else{
                if($initialRequirementRowCount !=0){
                    $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                <button type="button" onclick="ViewSamples('.$TestSampleID.', '.$WaterBarhCellNoID.')" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                <button type="button" onclick="TestForms('.$TestSampleID.', '.$batterytypeID.')" class="btn btn-sm btn-secondary"><i class="fa fa-folder-open"></i></button>
                            </div>';
                }
                else{
                    $sub_array[] = '<div class="btn-group mb-0" role="group" aria-label="Basic example">
                                <button type="button" onclick="ViewSamples('.$TestSampleID.', '.$WaterBarhCellNoID.')" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                <button type="button" onclick="InitialRequirements('.$TestSampleID.', '.$batterytypeID.')" class="btn btn-sm btn-primary"><i class="bi 	
                                bi-pen"></i></button>
                            </div>';
                }
            }
            $data[] = $sub_array;
        }

        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsFiltered' => $count,
            'data' => $data,
            "recordsTotal"=> $count
        );
        

        echo json_encode($output);
    }
    else if($_POST['action']=='waterBaths'){
        //Water Bath 1
        $waterBathController = new WaterBathController($connServer);
       $waterBath1 = $waterBathController->generateWaterBathOutput(1, 6);
       $waterBath2 = $waterBathController->generateWaterBathOutput(2, 6);
       $waterBath4 = $waterBathController->generateWaterBathOutput(3, 6);
       $waterBath5 = $waterBathController->generateWaterBathOutput(13, 6);
       $waterBath6 = $waterBathController->generateWaterBathOutput(14, 6);
       $waterBath7 = $waterBathController->generateWaterBathOutput(10, 5);
       $waterBath8 = $waterBathController->generateWaterBathOutput(11, 5);
       $waterBath10 = $waterBathController->generateWaterBathOutput(15, 3);
       $waterBath11 = $waterBathController->generateWaterBathOutput(9, 6);
       $waterBath12 = $waterBathController->generateWaterBathOutput(8, 6);
       $waterBath13 = $waterBathController->generateWaterBathOutput(7, 6);
       $waterBath14 = $waterBathController->generateWaterBathOutput(5, 6);
       $waterBath15 = $waterBathController->generateWaterBathOutput(6, 6);
       $waterBath16 = $waterBathController->generateWaterBathOutput(4, 6);
       $waterBath17 = $waterBathController->generateWaterBathOutput(18, 2);
       $waterBath18 = $waterBathController->generateWaterBathOutput(19, 2);
       $waterBath19 = $waterBathController->generateWaterBathOutput(20, 2);
       $waterBath20 = $waterBathController->generateWaterBathOutput(21, 2);
       $waterBath9 = $waterBathController->generateWaterBathOutputWithBlank(12,  3);
       $waterBathRCT_RCN = $waterBathController->generateWaterBathOutputRCTandRCN(16, 17, 8);

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
    else if($_POST['action']=='fetchCellData'){
        $CellID = isset($_POST['CellID']) ? $_POST['CellID'] : null;
        // $params = array($CellID, $CellID);
        $sql = "select cell.WaterBathCellNo, circuit.Circuit, circuit.CircuitDesc, status.WaterBathCellStatusCategoryID, status.WaterBathCellStatusCategory, bath.WaterBathNo, testSample.TestSamplesNo
        from WaterBathCellNo_tbl cell
        join WaterBath_tbl bath ON cell.WaterBathID = bath.WaterBathID
        join Circuit_tbl circuit ON bath.CircuitID = circuit.CircuitID
        left join SamplesWaterBathAllocation_tbl Samples ON cell.WaterBathCellNoID = Samples.WaterBathCellNoID
        left join TestSamples_tbl testSample ON Samples.TestSampleID = testSample.TestSamplesID
        cross apply (Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory  from WaterBathCellStatus_tbl wbStat
        left join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
        left join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
        where cell2.WaterBathCellNoID = ".$CellID."
        order by wbStat.DateCreated DESC ) as status
        where cell.WaterBathCellNoID = ".$CellID." ";

        $result = odbc_exec($connServer, $sql);
        // $execute = odbc_execute($result, $params);
        $Row = odbc_fetch_array($result);
        $count = odbc_num_rows($result);
        $statusID = 1;
        if($count!=0){
            $statusID = $Row['WaterBathCellStatusCategoryID'];
        }
        $output = ' <div class="row mt-2">
                        <div class="col-lg-6">
                            <div class="cube">
                                <div class="row">';
                    if($statusID == 1){
                            $output .= '<div class="cell-allocate text-center bg-light">
                                            <span class="font-bold position-relative" style="font-size:45px;top:20px;">'.$Row['WaterBathCellNo'].'</span>
                                        </div>';
                    }
                    else if($statusID == 2){
                            $output .= '<div class="cell-allocate text-center bg-primary">
                                    <span class="font-bold position-relative text-white" style="font-size:45px;top:20px;">'.$Row['WaterBathCellNo'].'</span>
                                </div>';
                    }
                    else{
                            $output .= '<div class="cell-allocate text-center bg-danger">
                                    <span class="font-bold position-relative text-white" style="font-size:45px;top:20px;">'.$Row['WaterBathCellNo'].'</span>
                                </div>';
                    }
                     
                    $output .= '</div> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                             ';
                            if($Row['WaterBathNo'] > 0){
                                $output .= '<p>Circuit: <span class="font-bold text-primary">'.$Row['Circuit'].'</span></p>
                                <p>Water Bath: <span class="font-bold text-primary">'.$Row['WaterBathNo'].'</span></p>';
                            }
                            else{
                                $output .= '<p>Circuit: <span class="font-bold text-primary">'.$Row['CircuitDesc'].' '.$Row['Circuit'].'</span></p>
                                <p>Water Bath: <span class="font-bold text-primary">Long Bath</span></p>';
                            }

                            if($statusID == 2){
                                $output .= '<p>Sample ID: <span class="font-bold text-primary">'.$Row['TestSamplesNo'].'</span></p>
                                ';
                            }
                            
                    $output .= '<p>Status: <span class="font-bold text-primary">'.$Row['WaterBathCellStatusCategory'].'</span></p>
                        </div>
                    </div>';

                    $output .= '<div class="container-fluid mb-0 p-0 mt-3">
                                <div class="form-group mt-2 ">
                                    <label for="AllocationRemarks" class="form-label">Remarks (optional)</label>
                                    <textarea class="form-control" id="AllocationRemarks" rows="3"></textarea>
                                </div>
                            </div>';
        $arr = array(
            'output' => $output,
            'status' => $statusID
        );

        echo json_encode($arr);
    }
    else if($_POST['action']=='samplesAllocation'){
        $CellID = isset($_POST['CellID']) ? $_POST['CellID'] : null;
        $TestSamplesID = isset($_POST['TestSamplesID']) ? $_POST['TestSamplesID'] : null;
        $Remarks = isset($_POST['Remarks']) ? $_POST['Remarks'] : null;
        $employeeID = $_COOKIE['BTL_employeeID'];

        $result = 0;
        $ifexistSql = "select * from SamplesWaterBathAllocation_tbl where TestSampleID = ".$TestSamplesID." and IsActive = 1 ";
        $existResult = odbc_exec($connServer, $ifexistSql);
        $existCount = odbc_num_rows($existResult);
        if($existCount==0){
            $params = array($CellID, $TestSamplesID, $Remarks);
            $sqlAllocate = "insert into SamplesWaterBathAllocation_tbl(WaterBathCellNoID, TestSampleID, SamplesWaterBathAllocationRemarks) values(?, ?, ?) ";
            $stmt = odbc_prepare($connServer, $sqlAllocate);
            $executeAllocate = odbc_execute($stmt, $params);
            if($executeAllocate){
                $param2 = array(2, $CellID, 'Allocated', $employeeID);
                $statusSql = "insert into WaterBathCellStatus_tbl(WaterBathCellStatusCategoryID, WaterBathCellNoID, Remarks, EmployeeID) values(?, ?, ?, ?) ";
                $stmt2 = odbc_prepare($connServer, $statusSql);
                $executeStatus = odbc_execute($stmt2, $param2);
                if($executeStatus){
                    $result = 1;
                }
            }
        }
        else{
            $result = 2;
        }
        
        echo json_encode($result);
    }
    else if($_POST['action']=='transferWaterBath'){
        $sampleID = isset($_POST['SampleID']) ? $_POST['SampleID'] : null;

        $sql = "select top 1 WaterBathCellNoID from SamplesWaterBathAllocation_tbl where TestSampleID = ".$sampleID." and IsActive = 1 and IsTransfer = 0
        order by DateCreated DESC ";

        $WaterBathCellNoID = 0;
        $result = odbc_exec($connServer, $sql);
        while($row = odbc_fetch_array($result)){
            $WaterBathCellNoID = $row['WaterBathCellNoID'];
        }
        

        $waterBathController = new WaterBathController($connServer);
        $waterBath1 = $waterBathController->generateSampleLocationBathOutput(1, 6, $WaterBathCellNoID, $sampleID);
        $waterBath2 = $waterBathController->generateSampleLocationBathOutput(2, 6, $WaterBathCellNoID, $sampleID);
        $waterBath4 = $waterBathController->generateSampleLocationBathOutput(3, 6, $WaterBathCellNoID, $sampleID);
        $waterBath5 = $waterBathController->generateSampleLocationBathOutput(13, 6, $WaterBathCellNoID, $sampleID);
        $waterBath6 = $waterBathController->generateSampleLocationBathOutput(14, 6, $WaterBathCellNoID, $sampleID);
        $waterBath7 = $waterBathController->generateSampleLocationBathOutput(10, 5, $WaterBathCellNoID, $sampleID);
        $waterBath8 = $waterBathController->generateSampleLocationBathOutput(11, 5, $WaterBathCellNoID, $sampleID);
        $waterBath9 = $waterBathController->generateSampleLocationBathOutput(15, 3, $WaterBathCellNoID, $sampleID);
        $waterBath10 = $waterBathController->generateSampleLocationBathOutput(9, 6, $WaterBathCellNoID, $sampleID);
        $waterBath11 = $waterBathController->generateSampleLocationBathOutput(8, 6, $WaterBathCellNoID, $sampleID);
        $waterBath12 = $waterBathController->generateSampleLocationBathOutput(7, 6, $WaterBathCellNoID, $sampleID);
        $waterBath13 = $waterBathController->generateSampleLocationBathOutput(5, 6, $WaterBathCellNoID, $sampleID);
        $waterBath14 = $waterBathController->generateSampleLocationBathOutput(6, 6, $WaterBathCellNoID, $sampleID);
        $waterBath15 = $waterBathController->generateSampleLocationBathOutput(4, 6, $WaterBathCellNoID, $sampleID);
        $waterBath16 = $waterBathController->generateSampleLocationBathOutput(18, 2, $WaterBathCellNoID, $sampleID);
        $waterBath17 = $waterBathController->generateSampleLocationBathOutput(19, 2, $WaterBathCellNoID, $sampleID);
        $waterBath18 = $waterBathController->generateSampleLocationBathOutput(20, 2, $WaterBathCellNoID, $sampleID);
        $waterBath19 = $waterBathController->generateSampleLocationBathOutput(21, 2, $WaterBathCellNoID, $sampleID);
        $waterBath20 = $waterBathController->generateSampleLocationBathWithBlank(12, 3, $WaterBathCellNoID, $sampleID);
        $waterBathRcnRct = $waterBathController->generateSampleLocationBathOutputRCTandRCN(16, 17, 8, $WaterBathCellNoID, $sampleID);

        $arr = array(
            'wb1' => $waterBath1,
            'wb2' => $waterBath2,
            'wb3' => $waterBath4,
            'wb4' => $waterBath5,
            'wb5' => $waterBath6,
            'wb6' => $waterBath7,
            'wb7' => $waterBath8,
            'wb8' => $waterBath9,
            'wb9' => $waterBath10,
            'wb10' => $waterBath11,
            'wb11' => $waterBath12,
            'wb12' => $waterBath13,
            'wb13' => $waterBath14,
            'wb14' => $waterBath15,
            'wb15' => $waterBath16,
            'wb16' =>  $waterBath17,
            'wb17' =>  $waterBath18,
            'wb18' =>  $waterBath19,
            'wb19' =>  $waterBath20,
            'WaterBathRcnRct' => $waterBathRcnRct,
            'test' => $WaterBathCellNoID
        
        );

        echo json_encode($arr);
    }
    else if($_POST['action']=='transferCellDetails'){
        $CellID = isset($_POST['cellBathID']) ? $_POST['cellBathID'] : null;
        $TransferCellID = isset($_POST['transferCellID']) ? $_POST['transferCellID'] : null;
        $IsTransferCheck = 0;
        // $params = array($CellID, $CellID);
        $sql = "select cell.WaterBathCellNo, circuit.Circuit, circuit.CircuitDesc, status.WaterBathCellStatusCategoryID, status.WaterBathCellStatusCategory, bath.WaterBathNo, testSample.TestSamplesNo
        from WaterBathCellNo_tbl cell
        join WaterBath_tbl bath ON cell.WaterBathID = bath.WaterBathID
        join Circuit_tbl circuit ON bath.CircuitID = circuit.CircuitID
        left join SamplesWaterBathAllocation_tbl Samples ON cell.WaterBathCellNoID = Samples.WaterBathCellNoID
        left join TestSamples_tbl testSample ON Samples.TestSampleID = testSample.TestSamplesID
        cross apply (Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory  from WaterBathCellStatus_tbl wbStat
        left join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
        left join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
        where cell2.WaterBathCellNoID = ".$CellID."
        order by wbStat.DateCreated DESC ) as status
        where cell.WaterBathCellNoID = ".$CellID." ";

        $result = odbc_exec($connServer, $sql);
        // $execute = odbc_execute($result, $params);
        $Row = odbc_fetch_array($result);

        $statusID = $Row['WaterBathCellStatusCategoryID'];

        if($CellID==$TransferCellID){
            $IsTransferCheck = 1;
        }

        $output = ' <div class="row mt-2">
                        <div class="col-lg-6">
                            <div class="cube">
                                <div class="row">';
                    if($statusID == 1){
                            $output .= '<div class="cell-allocate text-center bg-light">
                                            <span class="font-bold position-relative" style="font-size:45px;top:20px;">'.$Row['WaterBathCellNo'].'</span>
                                        </div>';
                    }
                    else if($statusID == 2){
                        $IsTransferCheck = 1;
                        if($CellID==$TransferCellID){
                            $output .= '<div class="cell-allocate text-center bg-success">
                                    <span class="font-bold position-relative text-white" style="font-size:45px;top:20px;">'.$Row['WaterBathCellNo'].'</span>
                                </div>';
                        }
                        else{
                            $output .= '<div class="cell-allocate text-center bg-primary">
                                    <span class="font-bold position-relative text-white" style="font-size:45px;top:20px;">'.$Row['WaterBathCellNo'].'</span>
                                </div>';
                        }
                            
                    }
                    else{
                            $IsTransferCheck = 1;
                            $output .= '<div class="cell-allocate text-center bg-danger">
                                    <span class="font-bold position-relative text-white" style="font-size:45px;top:20px;">'.$Row['WaterBathCellNo'].'</span>
                                </div>';
                    }
                     
                    $output .= '</div> 
                            </div>
                        </div>
                        <div class="col-lg-6">
                             ';
                            if($Row['WaterBathNo'] > 0){
                                $output .= '<p>Circuit: <span class="font-bold text-primary">'.$Row['Circuit'].'</span></p>
                                <p>Water Bath: <span class="font-bold text-primary">'.$Row['WaterBathNo'].'</span></p>';
                            }
                            else{
                                $output .= '<p>Circuit: <span class="font-bold text-primary">'.$Row['CircuitDesc'].' '.$Row['Circuit'].'</span></p>
                                <p>Water Bath: <span class="font-bold text-primary">Long Bath</span></p>';
                            }

                            if($statusID == 2){
                                $output .= '<p>Sample ID: <span class="font-bold text-primary">'.$Row['TestSamplesNo'].'</span></p>
                                ';
                            }
                            
                    $output .= '<p>Status: <span class="font-bold text-primary">'.$Row['WaterBathCellStatusCategory'].'</span></p>
                        </div>
                    </div>';

                    $output .= '<div class="container-fluid mb-0 p-0 mt-3">
                                <div class="form-group mt-2 ">
                                    <label for="AllocationRemarks" class="form-label">Remarks (optional)</label>
                                    <textarea class="form-control" id="AllocationRemarks" rows="3"></textarea>
                                </div>
                            </div>';
        $arr = array(
            'output' => $output,
            'status' => $statusID,
            'transferCheck' => $IsTransferCheck
        );

        echo json_encode($arr);
    }
    else if($_POST['action']=='transferCell'){
        $NewCellID = isset($_POST['cellBathID']) ? $_POST['cellBathID'] : null;
        $TransferCellID = isset($_POST['transferCellID']) ? $_POST['transferCellID'] : null;
        $employeeID = $_COOKIE['BTL_employeeID'];
        $ResultSet = 0;
        //get the current Allocation ID
        $currentSampleAllocatedID = 0;
        $sql = "select SamplesWaterBathAllocationID from SamplesWaterBathAllocation_tbl where WaterBathCellNoID = ".$TransferCellID." ";
        $result = odbc_exec($connServer, $sql);
        while($RowSampleAllocatedID = odbc_fetch_array($result)){
            $currentSampleAllocatedID = $RowSampleAllocatedID['SamplesWaterBathAllocationID'];
        }
        
        //get the current Allocation ID end

        //update the current row data referencing the $currentSampleAllocatedID: set IsTransfer to 1 and  IsActive to 0, if successful insert the new mapping
        $updateData = array(1, 0, $currentSampleAllocatedID);
        $updateSql = "update SamplesWaterBathAllocation_tbl set IsTransfer = ?, IsActive = ?, DateModified = GETDATE() where SamplesWaterBathAllocationID = ? ";
        $result = odbc_prepare($connServer, $updateSql);
        $execute = odbc_execute($result, $updateData);

        if($execute){
            $ResultSet = 1;
            $newSetStatData = array(1, $TransferCellID, 'Vacant', $employeeID);
            $insertNewStat = "insert into WaterBathCellStatus_tbl(WaterBathCellStatusCategoryID, WaterBathCellNoID, Remarks, EmployeeID) values(?, ?, ?, ?) ";
            $resultNewstat = odbc_prepare($connServer, $insertNewStat);
            $ExecuteNewstat = odbc_execute($resultNewstat, $newSetStatData);

            if($ExecuteNewstat){
                $newSetStatData2 = array(2, $NewCellID, 'Allocated', $employeeID);
                $insertNewStat2 = "insert into WaterBathCellStatus_tbl(WaterBathCellStatusCategoryID, WaterBathCellNoID, Remarks, EmployeeID) values(?, ?, ?, ?) ";
                $resultNewstat2 = odbc_prepare($connServer, $insertNewStat2);
                $ExecuteNewstat2 = odbc_execute($resultNewstat2, $newSetStatData2);

                if($ExecuteNewstat2){
                    $transfersample = "insert into SamplesWaterBathAllocation_tbl (WaterBathCellNoID, TestSampleID, SamplesWaterBathAllocationRemarks) 
                    select ".$NewCellID.", TestSampleID, 'From Transfer' from SamplesWaterBathAllocation_tbl where SamplesWaterBathAllocationID = ".$currentSampleAllocatedID." ";
                    $ExecuteNewMap = odbc_exec($connServer, $transfersample);
                    $lastID = GetLast_Id($connServer);

                    if($ExecuteNewMap){
                        $transferLogData = array($currentSampleAllocatedID, $lastID);
                        $transferLog = "insert into SamplesWBAllocationTransferLog_tbl(SamplesWBAllocationID_from, SamplesWBAllocationID_to) values (?, ?) ";
                        $transferLogStmt = odbc_prepare($connServer, $transferLog);
                        $resultTransferLog = odbc_execute($transferLogStmt, $transferLogData);
                    }
                }
            }
        }



        echo json_encode($NewCellID.' - '.$TransferCellID. ' -  '.$currentSampleAllocatedID. ' - '. $ResultSet);
    }
    else if($_POST['action']=='defective'){
        $cellID = isset($_POST['CellID']) ? $_POST['CellID'] : 0;
        $StatusID = isset($_POST['StatusID']) ? $_POST['StatusID'] : 0;
        $remarks = isset($_POST['Remarks']) ? $_POST['Remarks'] : null;
        $result = 0;
        $employeeID = $_COOKIE['BTL_employeeID'];

        if($StatusID==1){
            if($remarks==null){
                $remarks = 'Defective';
            }
            $newSetStatData = array(3, $cellID, $remarks, $employeeID);
            $insertNewStat = "insert into WaterBathCellStatus_tbl(WaterBathCellStatusCategoryID, WaterBathCellNoID, Remarks, EmployeeID) values(?, ?, ?, ?) ";
            $resultNewstat = odbc_prepare($connServer, $insertNewStat);
            $ExecuteNewstat = odbc_execute($resultNewstat, $newSetStatData);

            if($ExecuteNewstat){
                $result = 1;
            }
        }
        else if($StatusID==3){
            if($remarks==null){
                $remarks = 'Vacant';
            }
            $newSetStatData = array(1, $cellID, $remarks, $employeeID);
            $insertNewStat = "insert into WaterBathCellStatus_tbl(WaterBathCellStatusCategoryID, WaterBathCellNoID, Remarks, EmployeeID) values(?, ?, ?, ?) ";
            $resultNewstat = odbc_prepare($connServer, $insertNewStat);
            $ExecuteNewstat = odbc_execute($resultNewstat, $newSetStatData);

            if($ExecuteNewstat){
                $result = 1;
            }
        }
        
        echo json_encode($result);

    }
    else if($_POST['action']=='FetchPTPschedule'){
        $testSampleID = isset($_POST['testSampleID']) ? $_POST['testSampleID']:null;
        $testTableID = isset($_POST['testTableID']) ? $_POST['testTableID']:null;
        $UserTestStat = isset($_POST['UserTestStat']) ? $_POST['UserTestStat']:null;

        $task = array();
        $data = array();
        $duration = 5;
        $checkQuery = "select * from TestPTPSchedule_tbl where TestSamplesID = ".$testSampleID." ";
        $checkResult = odbc_exec($connServer, $checkQuery);
        $checkIfExist = odbc_num_rows($checkResult);
        if($checkIfExist != 0){
            if($UserTestStat==1){
                $sql = "select mtest.TestMSeriesID, testTable.Test, ptp.TestStartDate, ptp.TestEndDate
                from TestPTPSchedule_tbl ptp
                join TestMSeries_tbl mtest ON ptp.TestSourceID = mtest.TestMSeriesID
                join TestTable_tbl testTable ON mtest.TestTableTestID = testTable.TestTableID
                Where ptp.TestSamplesID = ". $testSampleID;
    
                $result = odbc_exec($connServer, $sql);
                $count = odbc_num_rows($result);
                
                // Get the current date
                $currentDate = new DateTime();
    
                // Add 2 days to the current date
                $twoDaysLater = $currentDate->modify('+2 days');
    
                // Format the result as a string (if needed)
                $formattedDate = $twoDaysLater->format('Y-m-d');
    
                if ($result) {
                    $prevEndDate = null;
                    $lastTaskID = null; // Keep track of the last task ID
                    $counter = 1;
                
                        while ($row = odbc_fetch_array($result)) {
                            $taskData = array(
                                'id' => $row['TestMSeriesID'],
                                'name' => $row['Test'],
                                'start' => $row['TestStartDate'], // Adjust as needed
                                'end' => $row['TestEndDate'],     // Adjust as needed
                                'progress' => 100,
                                'dependencies' => $lastTaskID ? $lastTaskID : ''// Use the first loop ID as a dependency in subsequent loops
                            );
                            $lastTaskID = $row['TestMSeriesID'];
                            $data[] = $taskData;
                        }
                }
                else {
                    die("Error executing query: " . odbc_errormsg());
                }
            }
            else if($UserTestStat==2 || $UserTestStat==3){
                $sql = "select Specialtest.TestSpecialTestID, testTable.Test, ptp.TestStartDate, ptp.TestEndDate
                from TestPTPSchedule_tbl ptp
                join TestSpecialTest_tbl Specialtest ON ptp.TestSourceID = Specialtest.TestSpecialTestID
                join TestTable_tbl testTable ON Specialtest.TestTableID = testTable.TestTableID
                Where ptp.TestSamplesID = ".$testSampleID;
    
                $result = odbc_exec($connServer, $sql);
                $count = odbc_num_rows($result);
                
                // Get the current date
                $currentDate = new DateTime();
    
                // Add 2 days to the current date
                $twoDaysLater = $currentDate->modify('+2 days');
    
                // Format the result as a string (if needed)
                $formattedDate = $twoDaysLater->format('Y-m-d');
    
                if ($result) {
                    $prevEndDate = null;
                    $lastTaskID = null; // Keep track of the last task ID
                    $counter = 1;
                
                        while ($row = odbc_fetch_array($result)) {

                            $taskData = array(
                                'id' => $row['TestSpecialTestID'],
                                'name' => $row['Test'],
                                'start' => $row['TestStartDate'], // Adjust as needed
                                'end' => $row['TestEndDate'],     // Adjust as needed
                                'progress' => 100,
                                'dependencies' => $lastTaskID ? $lastTaskID : ''// Use the first loop ID as a dependency in subsequent loops
                            );
                            $lastTaskID = $row['TestSpecialTestID'];
                            $data[] = $taskData;
                        }
                }
                else {
                    die("Error executing query: " . odbc_errormsg());
                }
            }
            
        }
        else{
            if($UserTestStat==1){
                $sql = "select series.TestMSeriesID, testTable.Test, duration.DurationUnit, duration.DurationValue  from TestSamples_tbl samples
                left join  TestPlanDetails_tbl details ON samples.TestPlanDetailsID = details.TestPlanDetailsID
                join TestMSeries_tbl series ON details.TestTableID = series.TestTableID
                join TestTable_tbl testTable ON series.TestTableTestID = testTable.TestTableID
                join TestDuration_tbl duration ON series.TestTableTestID = duration.TestTableID
                where samples.TestSamplesID = ".$testSampleID." order by series.TestMSeriesID ASC ";
    
                $result = odbc_exec($connServer, $sql);
                $count = odbc_num_rows($result);
                
                // Get the current date
                $currentDate = new DateTime();
    
                // Add 2 days to the current date
                $twoDaysLater = $currentDate->modify('+2 days');
    
                // Format the result as a string (if needed)
                $formattedDate = $twoDaysLater->format('Y-m-d');
    
                if ($result) {
                    $prevEndDate = null;
                    $lastTaskID = null; // Keep track of the last task ID
                    $counter = 1;
                
                        while ($row = odbc_fetch_array($result)) {
                            // Set the start date of the current task
                            $startDate = $prevEndDate ? date("Y-m-d H:i:s", strtotime($prevEndDate)) : $date2;
    
                            // Calculate the end date based on duration value and unit
                            $endDate = calculateEndDate($startDate, $row['DurationValue'], $row['DurationUnit']);
    
                            // Update the previous end date for the next iteration
                            $prevEndDate = $endDate;
                            // Create an associative array for each row
                            $taskData = array(
                                'id' => $row['TestMSeriesID'],
                                'name' => $row['Test'],
                                'start' => $startDate, // Adjust as needed
                                'end' => $endDate,     // Adjust as needed
                                'progress' => 100,
                                'dependencies' => $lastTaskID ? $lastTaskID : ''// Use the first loop ID as a dependency in subsequent loops
                            );
                    
                            // If this is the first loop, store the ID for later use
                            $lastTaskID = $row['TestMSeriesID'];
                    
                            // Increment the counter variabl
                    
                            // Append the task data array to the $data array
                            $data[] = $taskData;
                        }
                }
                else {
                    die("Error executing query: " . odbc_errormsg());
                }
            }
            else if($UserTestStat==2 || $UserTestStat==3){
                $sql = "select specialTest.TestSpecialTestID, Ttable.Test, duration.DurationUnit, duration.DurationValue
                from TestSamples_tbl samples
                left join TestPlanDetails_tbl details ON samples.TestPlanDetailsID = details.TestPlanDetailsID
                left join TestSpecialTest_tbl specialTest ON details.TestPlanDetailsID = specialTest.TestPlanDetailID
                left join TestTable_tbl Ttable ON specialTest.TestTableID = Ttable.TestTableID
                left join TestDuration_tbl duration ON specialTest.TestTableID = duration.TestTableID
                where samples.TestSamplesID = ".$testSampleID." and specialTest.IsActive = 1 and specialTest.IsDeleted = 0 order by specialTest.TestSpecialTestID ASC ";
    
                $result = odbc_exec($connServer, $sql);
                $count = odbc_num_rows($result);
                
                // Get the current date
                $currentDate = new DateTime();
    
                // Add 2 days to the current date
                $twoDaysLater = $currentDate->modify('+2 days');
    
                // Format the result as a string (if needed)
                $formattedDate = $twoDaysLater->format('Y-m-d');
    
                if ($result) {
                    $prevEndDate = null;
                    $lastTaskID = null; // Keep track of the last task ID
                    $counter = 1;
                
                        while ($row = odbc_fetch_array($result)) {
                            // Set the start date of the current task
                            $startDate = $prevEndDate ? date("Y-m-d H:i:s", strtotime($prevEndDate)) : $date2;
    
                            // Calculate the end date based on duration value and unit
                            $endDate = calculateEndDate($startDate, $row['DurationValue'], $row['DurationUnit']);
    
                            // Update the previous end date for the next iteration
                            $prevEndDate = $endDate;
                            // Create an associative array for each row
                            $taskData = array(
                                'id' => $row['TestSpecialTestID'],
                                'name' => $row['Test'],
                                'start' => $startDate, // Adjust as needed
                                'end' => $endDate,     // Adjust as needed
                                'progress' => 100,
                                'dependencies' => $lastTaskID ? $lastTaskID : ''// Use the first loop ID as a dependency in subsequent loops
                            );
                    
                            // If this is the first loop, store the ID for later use
                            $lastTaskID = $row['TestSpecialTestID'];
                    
                            // Increment the counter variabl
                    
                            // Append the task data array to the $data array
                            $data[] = $taskData;
                        }
                }
                else {
                    die("Error executing query: " . odbc_errormsg());
                }
            }
        }
        echo json_encode($data);
    }
    else if($_POST['action']=='fetchSelectedTest'){
        $TestSamplesID = isset($_POST['testSampleID']) ? $_POST['testSampleID']:0;
        $output = "";

        $sql = 'select specialTest.TestSpecialTestID, Ttable.Test, specialTest.TestTableID
        from TestSamples_tbl samples
        left join TestPlanDetails_tbl details ON samples.TestPlanDetailsID = details.TestPlanDetailsID
        join TestSpecialTest_tbl specialTest ON details.TestPlanDetailsID = specialTest.TestPlanDetailID
        join TestTable_tbl Ttable ON specialTest.TestTableID = Ttable.TestTableID
        where samples.TestSamplesID = '.$TestSamplesID.' and specialTest.IsActive = 1 and specialTest.IsDeleted = 0 order by specialTest.TestSpecialTestID ASC ';

        $result = odbc_exec($connServer, $sql);
        if($result){
            while($row = odbc_fetch_array($result)){
                $testTableID = $row['TestTableID'];
                $Test = $row['Test'];

                $output .= '
                    <li>
                        <input type="hidden" value="'.$testTableID.'">
                        <span class="badge bg-primary">'.$Test.'  <i class="bi bi-arrow-right-circle"></i></span>
                    </li>
                ';
            }
        }
        echo json_encode($output);
    }
    else if($_POST['action']=='UpdateSelectedTest'){
        $SampleID = isset($_POST['testSampleID']) ? $_POST['testSampleID'] : 0;
        $sortedData = isset($_POST['sortedTestData']) ? $_POST['sortedTestData'] : 0;
        $TestPlanDetailsID = 0;
        $output = 0;
        $sql = "select details.TestPlanDetailsID
        from TestSamples_tbl samples
        join TestPlanDetails_tbl details ON samples.TestPlanDetailsID = details.TestPlanDetailsID
        where samples.TestSamplesID = ".$SampleID;
        $result = odbc_exec($connServer, $sql);
        if($result){
            $row = odbc_fetch_array($result);
            $TestPlanDetailsID = $row['TestPlanDetailsID'];
        }
        if(!empty($sortedData)){
            $query = "update TestSpecialTest_tbl set IsActive = 0, IsDeleted = 1, DateModified = getdate() where TestPlanDetailID = ".$TestPlanDetailsID;
            $execute = odbc_exec($connServer, $query);
            if($execute){
                $count = count($sortedData);
                if($count!=0){
                    for($i=0; $i<$count; $i++){
                        $data = array($TestPlanDetailsID, $sortedData[$i]);
                        $insertQuery = "insert into TestSpecialTest_tbl (TestPlanDetailID, TestTableID) ";
                        $insertQuery .= "values (?, ?) ";
                        $stmt = odbc_prepare($connServer, $insertQuery);
                        $insertExecute = odbc_execute($stmt, $data);
                    }
                }
            }
            
            $output = 1;
        }
        else{
            $output = 0;
        }
        

        echo json_encode($output);
    }
    else if($_POST['action']=='updateTask'){
        $taskID = isset($_POST['taskID'])? $_POST['taskID'] : null;
        $sampleID = isset($_POST['sampleID'])? $_POST['sampleID'] : null;
        $startDate = isset($_POST['startDate'])? $_POST['startDate'] : null;
        $endDate = isset($_POST['endDate'])? $_POST['endDate'] : null;
        $result = 0;

        $data = array($startDate, $endDate, $sampleID, $taskID);
        $sql = "update TestPTPSchedule_tbl set TestStartDate = ?,  TestEndDate = ?, DateModified = getdate() ";
        $sql .= "Where TestSamplesID = ? and TestSourceID = ? and IsActive = 1 and IsDeleted = 0 ";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $data);
        if($execute){
            $result = 1;
        }
        
        echo json_encode($count);
    }
    else if($_POST['action'] == 'MF_InitialMeasurement'){
        $sampleID = isset($_POST['sampleID']) ? $_POST['sampleID'] : 0;
        $batteryTypeID = isset($_POST['batteryTypeID']) ? $_POST['batteryTypeID'] : 0;
        $mf_weight = isset($_POST['mf_weight']) ? $_POST['mf_weight'] : 0;
        $mf_ocv = isset($_POST['mf_ocv']) ? $_POST['mf_ocv'] : 0;
        $mf_ir = isset($_POST['mf_ir']) ? $_POST['mf_ir'] : 0;
        $mf_cca = isset($_POST['mf_cca']) ? $_POST['mf_cca'] : 0;
        $CellData = isset($_POST['CellData']) ? $_POST['CellData'] : 0;

        $count = count($CellData);
        $InitialData = array($sampleID, $mf_weight, $mf_ocv, $mf_ir, $mf_cca);
        $sql = "Insert into InitialMeasurementMF_tbl(TestSamplesID, WeightVal, OCV_Val, IR_Val, CCA_Val) ";
        $sql .= "values(?, ?, ?, ?, ?)";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $InitialData);
        $result = 0;
        if($execute){
            $result = 1;
            for($i=0; $i < $count; $i++){
                $CellID = $CellData[$i]['CellId'];
                $CellSG = $CellData[$i]['Cell_SG'];
                $CellTemp = $CellData[$i]['Cell_Temp'];
                $dataCell = array($sampleID, $CellID, $CellSG, $CellTemp);
                $sql2 = "Insert into InitialMeasurementCellDataMF_tbl(TestSamplesID, CellNo, Acid_SG, CellTemp) ";
                $sql2 .= "values (?, ?, ?, ?) ";
                $stmt2 = odbc_prepare($connServer, $sql2);
                $execute2 = odbc_execute($stmt2, $dataCell);
                if($execute2){
                    $result = 1;
                }
            }
        }
        
        echo json_encode($result);
    }
    else if($_POST['action'] == 'LM_InitialMeasurement'){
        $sampleID = isset($_POST['sampleID']) ? $_POST['sampleID'] : 0;
        $batteryTypeID = isset($_POST['batteryTypeID']) ? $_POST['batteryTypeID'] : 0;
        $LM_weight = isset($_POST['LM_weight']) ? $_POST['LM_weight'] : 0;
        $SG = isset($_POST['SG']) ? $_POST['SG'] : 0;
        $OCV5s = isset($_POST['OCV5s']) ? $_POST['OCV5s'] : 0;
        $OCV30s = isset($_POST['OCV30s']) ? $_POST['OCV30s'] : 0;
        $weightAfterActivation = isset($_POST['weightAfterActivation']) ? $_POST['weightAfterActivation'] : 0;
        $TimeTo12V = isset($_POST['TimeTo12V']) ? $_POST['TimeTo12V'] : 0;
        $ActivationData = isset($_POST['ActivationData']) ? $_POST['ActivationData'] : 0;

        $count = count($ActivationData);
        $InitialData = array($sampleID, $LM_weight, $SG, $OCV5s, $OCV30s, $TimeTo12V, $weightAfterActivation);
        $sql = "Insert into InitialMeasurementLM_tbl(TestSamplesID, Pre_Weight, Pre_SG, Act_OCV5s, Act_OCV30s, TimeToReach12V, AfterAct_Weight) ";
        $sql .= "values(?, ?, ?, ?, ?, ?, ?)";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $InitialData);
        $result = 0;
        if($execute){
            $result = 1;
            for($i=0; $i < $count; $i++){
                $time = $ActivationData[$i]['time'];
                $OCV = $ActivationData[$i]['OCVTbl'];
                $CCA = $ActivationData[$i]['CCATbl'];
                $Temp = $ActivationData[$i]['TempTbl'];
                $SG = $ActivationData[$i]['SGTbl'];
                $IR = $ActivationData[$i]['IRTbl'];

                $dataCell = array($sampleID, $time, $OCV, $CCA, $Temp, $SG, $IR);
                $sql2 = "Insert into PostActivationLM_tbl(TestSamplesID, TimeText, OCV, CCA, Temp, SG, IR) ";
                $sql2 .= "values (?, ?, ?, ?, ?, ?, ?) ";
                $stmt2 = odbc_prepare($connServer, $sql2);
                $execute2 = odbc_execute($stmt2, $dataCell);
                if($execute2){
                    $result = 1;
                }
            }
        }
        
        echo json_encode($result);
    }
    else if($_POST['action'] == 'ReprintSamples'){
        $TestSampleID = isset($_POST['TestSampleID']) ? $_POST['TestSampleID'] : 0;
        $CellNoID = isset($_POST['CellNoID']) ? $_POST['CellNoID'] : 0;

        $CircuitLocation  = '';
        $output = '';

        if($CellNoID > 0){
            $sqlCircuit = "select circuit.Circuit, bath.WaterBathNo from WaterBathCellNo_tbl CellNo
            join WaterBath_tbl bath ON CellNo.WaterBathID = bath.WaterBathID
            join Circuit_tbl circuit ON bath.CircuitID = circuit.CircuitID
            where CellNo.WaterBathCellNoID = ".$CellNoID." ";
            $resultCircuit = odbc_exec($connServer, $sqlCircuit);
            $rowCircuit = odbc_fetch_array($resultCircuit);
            $Circuit = $rowCircuit['Circuit'];
            $WaterBathNo = $rowCircuit['WaterBathNo'];
            $CircuitLocation  = $Circuit.' '.$WaterBathNo;
        }

        $sql = "select samples.TestSamplesNo, testTbl.Test, testTbl.IsSeries, requi.ProjectName, requi.RequisitionID, testPlan.UserTestCategory
        from TestSamples_tbl samples
        join TestPlanDetails_tbl testDetails ON samples.TestPlanDetailsID = testDetails.TestPlanDetailsID
        join TestTable_tbl testTbl ON testDetails.TestTableID = testTbl.TestTableID
        join TestPlan_tbl testPlan ON testDetails.TestPlanID = testPlan.TestPlanID
        join Request_tbl request ON testPlan.RequestID = request.RequestID
        join Requisition_tbl requi ON request.RequisitionID = requi.RequisitionID
        where samples.TestSamplesID = ".$TestSampleID." ";
        $result = odbc_exec($connServer, $sql);
        $row = odbc_fetch_array($result);
        $SampleID = $row['TestSamplesNo'];
        $SampleImgaUrl = '../SamplesQRuploads/'.$SampleID.'.png';
        $SampleIsSeries = $row['IsSeries'];
        $userTest = $row['UserTestCategory'];
        $requisitionID = $row['RequisitionID'];
        $ProjectName = $row['ProjectName'];

        $test = '';
        if($userTest==1){
            $test = $row['Test'];
        }
        else if($userTest==4){
            $test = 'Benchmark';
        }
        else{
            $test = 'Special Test';
        }

        $requestorQuery = "select emp.Fname, emp.Lname from Requestor_tbl requestor
        join Requisition_tbl requi ON requestor.RequisitionID = requi.RequisitionID
        join Employee_tbl emp ON requestor.EmployeeID = emp.EmployeeID
        where requi.RequisitionID = ".$requisitionID." and requestor.IsActive = 1 and requestor.IsDeleted = 0 ";
        $requestorReault = odbc_exec($connServer, $requestorQuery);
        $numRows = odbc_num_rows($requestorReault);
        $requestorOutput = "";
        $requestorInitials = "";
        $delimiter = ' / '; // Space and '/'
        if($requestorReault){
            $currentRow = 0;
            while($requestorRow = odbc_fetch_array($requestorReault)){
                
                $fname = GetNameInitials($requestorRow['Fname']);
                $Lname = GetNameInitials($requestorRow['Lname']);
                $nameInitials = $fname.$Lname;
                // Check if it's the last iteration before adding the delimiter
                $requestorInitials .= $nameInitials;
                if (++$currentRow < $numRows) {
                    $requestorInitials .= $delimiter;
                }
            }
        }

        $output .='<div class="row">
                    <div class="col-lg-4">
                        <input type="hidden" value="'.$TestSampleID.'" id="SampleID_holder" />
                        <img src="'.$SampleImgaUrl.'" alt="" class="img-fluid position-relative" style="border: 1px solid #000000;padding:5px;border-radius: 5px;top:12px;">
                    </div>
                    <div class="col-lg-8">
                        <h3 class="position-relative" style="top:7px;">'.$SampleID.'</h3>
                        <span class="" style="font-size:14px;">'.$ProjectName.'</span></br>
                        <span class="" style="font-size:14px;">Requestor: '.$requestorInitials.'</span>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="">'.$test.'</h6>
                            </div>
                            <div class="col-md-6">
                                <h6 class="">'.$CircuitLocation.'</h6>
                            </div>
                        </div>
                        
                    </div>
                </div>';
        
        echo json_encode($output);
    }
    else if($_POST['action'] == 'printSamples'){
        $testSamplesID = isset($_POST['SampleID']) ? $_POST['SampleID'] : 0;
        $data = array($testSamplesID);
        $sql = "update TestSamples_tbl set IsReprint = 1 where TestSamplesID = ? ";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $data);
        $result = 0;
        if($execute){
            $result = 1;
        }
        echo json_encode($result);
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
    else if($_POST['action'] == 'StartTest'){
        $employeeID = $_COOKIE['BTL_employeeID'];
        $testSampleID = isset($_POST['TestSampleID']) ? $_POST['TestSampleID'] : 0;
        $data = isset($_POST['data_holder']) ? $_POST['data_holder'] : 0;
        $batteryType = isset($_POST['BatteryType']) ? $_POST['BatteryType'] : 0;
        $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
        $userStatID = isset($_POST['userStatID']) ? $_POST['userStatID'] : 0;

        $count = count($data);
        $output = '';
        $isStartTest = 0;

        if($batteryType == 2){
            $checkSql = "select * from InitialMeasurementLM_tbl where TestSamplesID = ".$testSampleID;
            $checkResult = odbc_exec($connServer, $checkSql);
            $count = odbc_num_rows($checkResult);
            if($count!=0){
                $isStartTest = 1;
            }
        }
        else{
            $checkSql = "select * from InitialMeasurementMF_tbl where TestSamplesID = ".$testSampleID;
            $checkResult = odbc_exec($connServer, $checkSql);
            $count = odbc_num_rows($checkResult);
            if($count!=0){
                $isStartTest = 1;
            }
        }

        $output = '';
        foreach ($data as $taskData) {
            // Access individual $taskData arrays
            $output .= "ID: " . $taskData['id'] . "<br>";
            $output .= "Name: " . $taskData['name'] . "<br>";
            $output .= "Start Date: " . $taskData['start'] . "<br>";
            $output .= "End Date: " . $taskData['end'] . "<br>";
            // Add other fields as needed
            $output .= "<br>";
        }
        // echo $output;

        if($isStartTest==1){
            $proceed = false;
            if($count !=0){
                foreach ($data as $taskData) {
                    $testSourceID = $taskData['id'];
                    $testStartTest = $taskData['start'];
                    $testEndTest = $taskData['end'];

                    $TestData = array($testSampleID, $testSourceID, $testStartTest, $testEndTest, $userStatID);
                    $insertQuery = "Insert into TestPTPSchedule_tbl(TestSamplesID, TestSourceID, TestStartDate, TestEndDate, UserTestCategoryID) values(?, ?, ?, ?, ?) ";
                    $stmt = odbc_prepare($connServer, $insertQuery);
                    $insertExecute = odbc_execute($stmt, $TestData);
                    if($insertExecute){
                        $proceed = true;
                    }
                }
                if($proceed){
                    $dataHolder = array( $requestID, 3, 'On-going testing', $employeeID);
                    $statRequestInsert = "insert into RequestStatus_tbl(RequestID, StatusID, Remarks, EmployeeID) ";
                    $statRequestInsert .= "Values(?, ?, ?, ?) ";
                    $stmt = odbc_prepare($connServer, $statRequestInsert);
                    $execute = odbc_execute($stmt, $dataHolder);

                    if($execute){
                        $statusUpdateQuery = "update TestSamples_tbl set StatusID = 3, DateModified = getdate() where TestSamplesID = ".$testSampleID." and IsActive = 1 and IsDeleted = 0 ";
                        $ExecuteStatusUpdate = odbc_exec($connServer, $statusUpdateQuery);
                    }
                }
            }
        }

        echo json_encode($isStartTest);
    }
    else if($_POST['action'] == 'equipmentNo'){
        $CellNoID = isset($_POST['CellNoID']) ? $_POST['CellNoID'] : 0;

        $CircuitLocation  = '';
        $output = '';

        if($CellNoID > 0){
            $sqlCircuit = "select circuit.Circuit, bath.WaterBathNo from WaterBathCellNo_tbl CellNo
            join WaterBath_tbl bath ON CellNo.WaterBathID = bath.WaterBathID
            join Circuit_tbl circuit ON bath.CircuitID = circuit.CircuitID
            where CellNo.WaterBathCellNoID = ".$CellNoID." ";
            $resultCircuit = odbc_exec($connServer, $sqlCircuit);
            $rowCircuit = odbc_fetch_array($resultCircuit);
            $Circuit = $rowCircuit['Circuit'];
            $WaterBathNo = $rowCircuit['WaterBathNo'];
            $CircuitLocation  = $Circuit.' '.$WaterBathNo;
        }
        $arr = array(
            'WaterBathCellNoID' => $CellNoID,
            'CircuitLocation' => $CircuitLocation
        );
        echo json_encode($arr);
    }
    //-------------Capacity Test Form-------------
    else if($_POST['action'] == 'submitCapcityForm'){
        $output = 0;
        $formaCatID = isset($_POST['formaCatID']) ? $_POST['formaCatID'] : 'n/a';
        $TestPTPScheduleID = isset($_POST['ptpTestScheduleID']) ? $_POST['ptpTestScheduleID'] : 'n/a';
        $sampleID = isset($_POST['sampleID']) ? $_POST['sampleID'] : 'n/a';
        $testTableID = isset($_POST['testTableID']) ? $_POST['testTableID'] : 'n/a';
        $cycleNo = isset($_POST['cycleNo']) ? $_POST['cycleNo'] : 'n/a';
        $dataFileName = isset($_POST['dataFileName']) ? $_POST['dataFileName'] : 'n/a';
        $waterBathCellNoID = isset($_POST['waterBathCellNoID']) ? $_POST['waterBathCellNoID'] : 'n/a';
        $dischargeCurrent = isset($_POST['dischargeCurrent']) ? $_POST['dischargeCurrent'] : 'n/a';
        $cuttOffVoltage = isset($_POST['cuttOffVoltage']) ? $_POST['cuttOffVoltage'] : 'n/a';
        $ocv = isset($_POST['ocv']) ? $_POST['ocv'] : 'n/a';
        $dischargeTimeMins = isset($_POST['dischargeTimeMins']) ? $_POST['dischargeTimeMins'] : 'n/a';
        $cca = isset($_POST['cca']) ? $_POST['cca'] : 'n/a';
        $PostCapacity1SG1 = isset($_POST['PostCapacity1SG1']) ? $_POST['PostCapacity1SG1'] : 'n/a';
        $PostCapacity2SG2 = isset($_POST['PostCapacity2SG2']) ? $_POST['PostCapacity2SG2'] : 'n/a';
        $PreCapacity1SG1 = isset($_POST['PreCapacity1SG1']) ? $_POST['PreCapacity1SG1'] : 'n/a';
        $PreCapacity2SG2 = isset($_POST['PreCapacity2SG2']) ? $_POST['PreCapacity2SG2'] : 'n/a';
        $CapacityRemarks = isset($_POST['CapacityRemarks']) ? $_POST['CapacityRemarks'] : 'For Review';

        $employeeID = $_COOKIE['BTL_employeeID'];

        $dataInput = array($TestPTPScheduleID, $formaCatID, $sampleID, $testTableID, $cycleNo);
        $insertTestInput = "Insert into TestDataInput_tbl (TestPTPScheduleID, FormCategoryID, SampleID, TestTableID, CycleNo) values (?, ?, ?, ?, ?) ";
        $stmt_input = odbc_prepare($connServer, $insertTestInput);
        $inputDataExecute = odbc_execute($stmt_input, $dataInput);

        $TestInputLastID = 0;
        if($inputDataExecute){
            $TestInputLastID = GetLast_Id($connServer);

            $detailsData = array($TestInputLastID, $waterBathCellNoID, $dischargeCurrent, $cuttOffVoltage, $dataFileName);
            $insertCapacityDetails = "insert into CapacityTestDetails_tbl (TestDataInputID, WaterBathCellNoID, DischargeCurrent, CuttOffV, DataFileName) values (?, ?, ?, ?, ?)";
            $stmt_details = odbc_prepare($connServer, $insertCapacityDetails);
            $detailsExecute = odbc_execute($stmt_details, $detailsData);
            $output = 1;
            if($detailsExecute){
                $dataPretest = array($TestInputLastID, $ocv, $cca, $PreCapacity1SG1, $PreCapacity2SG2);
                $insertPretest = "insert into CapacityPreTestMeasurement_tbl (TestDataInputID, OCV, CCA, SG1, SG2) values (?, ?, ?, ?, ?) ";

                $stmt_preTest = odbc_prepare($connServer, $insertPretest);
                $PretestExecute = odbc_execute($stmt_preTest, $dataPretest);
                $output = 1;
                if($PretestExecute){
                    $dataPosttest = array($TestInputLastID, $dischargeTimeMins, $dischargeTimeMins, $PostCapacity1SG1, $PostCapacity2SG2);
                    $insertPosttest = "insert into CapacityPostTestMeasurement_tbl (TestDataInputID, DCHMins, SG1, SG2) values (?, ?, ?, ?) ";

                    $stmt_postTest = odbc_prepare($connServer, $insertPosttest);
                    $PosttestExecute = odbc_execute($stmt_postTest, $dataPosttest);
                    $output = 1;
                    $statusID = 11; //For Approval
                    // $ForReviewRemarks = 'For Review';
                    if($PosttestExecute){
                        $dataAddstatus = array($TestInputLastID, $employeeID, $statusID, $CapacityRemarks);
                        $insertAddstatus = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) values (?, ?, ?, ?) ";

                        $stmt_addStatus = odbc_prepare($connServer, $insertAddstatus);
                        $addStatusExecute = odbc_execute($stmt_addStatus, $dataAddstatus);
                        $output = 1;
                    }
                }
                else{
                    $output = 0;
                }
            }
            else{
                $output = 0;
            }
        }

        echo json_encode($output);
    }
    else if($_POST['action'] == 'fetchCapacityForm'){
        $ptpTestScheduleID = isset($_POST['ptpTestScheduleID']) ? $_POST['ptpTestScheduleID'] : 0;
        $HaveRow = isset($_POST['HaveRow']) ? $_POST['HaveRow'] : 0;
        $sampleId = isset($_POST['sampleId']) ? $_POST['sampleId'] : 0;
        $currentTestTxt = isset($_POST['currentTestTxt']) ? $_POST['currentTestTxt'] : null;
        $testTableId = isset($_POST['testTableId']) ? $_POST['testTableId'] : 0;
        $formCategoryId = isset($_POST['formCategoryId']) ? $_POST['formCategoryId'] : 0;
        $testSampleSysText = isset($_POST['testSampleSysText']) ? $_POST['testSampleSysText'] : null;
        $currentDate = isset($_POST['currentDate']) ? $_POST['currentDate'] : null;
        $waterBathCellNoId = isset($_POST['waterBathCellNoId']) ? $_POST['waterBathCellNoId'] : 0;
        $TestStatusID = isset($_POST['TestStatusID']) ? $_POST['TestStatusID'] : 0;
        $HaveRow = isset($_POST['HaveRow']) ? $_POST['HaveRow'] : 0;
        $output = '';
        $actionBtn = '';
        if($HaveRow !=0){
            $sql = "select top 1 testData.TestDataInputID, details.DischargeCurrent, details.CuttOffV, PreTest.OCV, PreTest.CCA, PreTest.SG1 as preSG1, PreTest.SG2 as preSG2,
            PostTest.DCHMins, PostTest.SG1 as postSG1, PostTest.SG2 as postSG2, details.DataFileName, status.StatusID, status.Remarks
            from TestDataInput_tbl testData
            join CapacityTestDetails_tbl details ON testData.TestDataInputID = details.TestDataInputID
            join CapacityPreTestMeasurement_tbl PreTest ON testData.TestDataInputID = PreTest.TestDataInputID
            join CapacityPostTestMeasurement_tbl PostTest ON testData.TestDataInputID = PostTest.TestDataInputID
            cross apply (
                select top 1 StatusID, Remarks from TestDataStatus_tbl where TestPTPScheduleID = testData.TestPTPScheduleID order by DateCreated DESC
            ) as status
            where testData.TestPTPScheduleID = ".$ptpTestScheduleID." and testData.IsActive = 1 and testData.IsDeleted = 0 and PreTest.IsActive = 1 and PreTest.IsDeleted = 0 and PostTest.IsActive = 1 and PostTest.IsDeleted = 0 
            ";
            $execute = odbc_exec($connServer, $sql);
            if($execute){
                $rowData = odbc_fetch_array($execute);
                $TestDataInputID = $rowData['TestDataInputID'];
                $DischargeA = $rowData['DischargeCurrent'];
                $CutOffVoltage = $rowData['CuttOffV'];
                $OCV = $rowData['OCV'];
                $DischargeTime = $rowData['DCHMins'];
                $MidtronicsCCA = $rowData['CCA'];
                $AcidSG1C1 = $rowData['postSG1'];
                $AcidSG1C2 = $rowData['postSG2'];
                $AcidSG2C1 = $rowData['preSG1'];
                $AcidSG2C2 = $rowData['preSG2'];
                $dataFileName = $rowData['DataFileName'];
                $StatusID = $rowData['StatusID'];
                $StatusRemarks = $rowData['Remarks'];
                if($StatusID==10){
                    $output .= '<div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Battery No.</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="text" id="TestDataBatNo" class="form-control font-bold" name="TestDataBatNo" value="'.$testSampleSysText.'"
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
                                            <input type="text" id="TestDataTestDate" class="form-control font-bold" name="TestDataTestDate" value="'.$currentDate.'"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <h6 class="text-center">Capacity Test</h6>
                                    <div class="row">
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Test Type</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="hidden" id="ptpTestScheduleID" value="'.$ptpTestScheduleID.'">

                                            <input type="hidden" id="formCategoryId" value="'.$formCategoryId.'">

                                            <input type="hidden" id="testTableId" value="'.$testTableId.'">

                                            <input type="hidden" id="sampleId" value="'.$sampleId.'">

                                            <input type="hidden" id="TestDataStatusID" value="'.$TestStatusID.'">

                                            <input type="text" id="TestDataTestType" class="form-control" name="TestDataTestType" value="'.$currentTestTxt.'"
                                                disabled>
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Cycle No.</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="hidden" id="TestDataCycleNoData" class="form-control" value="1">

                                            <input type="text" id="TestDataCycleNo" class="form-control" name="TestDataCycleNo"
                                                disabled value="1">
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Equipment No.</label>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <input type="hidden" id="TestDataEquipmentCapacityID" name="TestDataEquipmentCapacity">

                                            <input type="text" id="TestDataEquipmentCapacity" class="form-control" name="TestDataEquipmentCapacity"
                                                disabled>
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6 class="text-center">Test Parameters</h6>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label>Discharge current, A</label>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <input type="number" id="TestDataDischargeA" class="form-control" name="TestDataDischargeA"
                                                placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label>Cut-off voltage, V</label>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <input type="number" id="TestDataCutOffV" class="form-control" name="TestDataCutOffV"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered mb-0" id="initialMeasureTbl">
                                    <thead>
                                        <tr>
                                            <th colspan="3" style="white-space:nowrap;">Pre-test Measurements</th>
                                            <th colspan="3" style="white-space:nowrap;">Post-test Measurements</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-bold-500" style="white-space:nowrap;">
                                                <h6>OCV, V.</h6>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" id="PreCapacityOCV" class="form-control" name="PreCapacityOCV"
                                                placeholder="" >
                                            </td>
                                            <td class="text-bold-500" style="white-space:nowrap;">
                                                <h6>Discharge time, mins</h6>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" id="PostCapacityDischargeTime" class="form-control" name="PostCapacityDischargeTime"
                                                placeholder="" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-500" style="white-space:nowrap;"><h6>Midtronics CCA, A</h6>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" id="CapacityCCA" class="form-control" name="CapacityCCA"
                                                placeholder="" >
                                            </td>
                                            <td class="text-bold-500" style="white-space:nowrap;"><h6 class="superscript">Acid SG/ Temp, ºC <span>1, 2</span></h6>
                                            </td>
                                            <td>
                                                <input type="text" id="Capacity1SG1" class="form-control" name="Capacity1SG1"
                                                placeholder="" >
                                            </td>
                                            <td>
                                                <input type="text" id="Capacity1SG2" class="form-control" name="Capacity1SG2"
                                                placeholder="" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-500" style="white-space:nowrap;"><h6 class="superscript">Acid SG/ Temp, ºC <span>1</span> </h6>
                                            </td>
                                            <td>
                                                <input type="text" id="Capacity2SG1" class="form-control" name="Capacity2SG1"
                                                placeholder="" >
                                            </td>
                                            <td>
                                                <input type="text" id="Capacity2SG2" class="form-control" name="Capacity2SG2"
                                                placeholder="" >
                                            </td>

                                            <td class="text-bold-500" style="white-space:nowrap;"><h6>Data file name </h6>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" id="CapacityDataFileName" class="form-control" name="CapacityDataFileName"
                                                placeholder="" >
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group mt-3 ">
                                <label for="CapacityFormRemarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="CapacityFormRemarks" rows="2">For Approval</textarea>
                            </div>

                            <div class="mt-4">
                                <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">1</span> Measure the SG of the middle cell. If SG is measured using a float hydrometer, measure the electrolyte temperature as well. No need to do this if a digital hydrometer is used as its measurement is already temperature-compensated.</p>

                                <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">2</span> For RC test, electrolyte temperature after testing should be measured regardless of type hydrometer used.</p>
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
                            </div>';

                            $actionBtn = '<button type="button" class="btn btn-secondary"       data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-warning ml-1" id="SubmitRetestFormBtn" onclick="RetestCapacityBtn('.$TestDataInputID.')">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Submit</span>
                                            </button>';
                }
                else{
                    $output .= '<div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Battery No.</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="text" id="TestDataBatNo" class="form-control font-bold" name="TestDataBatNo" value="'.$testSampleSysText.'"
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
                                            <input type="text" id="TestDataTestDate" class="form-control font-bold" name="TestDataTestDate" value="'.$currentDate.'"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <h6 class="text-center">Capacity Test</h6>
                                    <div class="row">
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Test Type</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="hidden" id="ptpTestScheduleID" value="'.$ptpTestScheduleID.'">

                                            <input type="hidden" id="formCategoryId" value="'.$formCategoryId.'">

                                            <input type="hidden" id="testTableId" value="'.$testTableId.'">

                                            <input type="hidden" id="sampleId" value="'.$sampleId.'">

                                            <input type="hidden" id="TestDataStatusID" value="'.$TestStatusID.'">

                                            <input type="text" id="TestDataTestType" class="form-control" name="TestDataTestType" value="'.$currentTestTxt.'"
                                                disabled>
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Cycle No.</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input type="hidden" id="TestDataCycleNoData" class="form-control" value="1">

                                            <input type="text" id="TestDataCycleNo" class="form-control" name="TestDataCycleNo"
                                                disabled value="1">
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Equipment No.</label>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <input type="hidden" id="TestDataEquipmentCapacityID" name="TestDataEquipmentCapacity">

                                            <input type="text" id="TestDataEquipmentCapacity" class="form-control" name="TestDataEquipmentCapacity"
                                                disabled>
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6 class="text-center">Test Parameters</h6>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label>Discharge current, A</label>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <input type="number" id="TestDataDischargeA" class="form-control" name="TestDataDischargeA"
                                                placeholder="" value="'.$DischargeA.'">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <label>Cut-off voltage, V</label>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <input type="number" id="TestDataCutOffV" class="form-control" name="TestDataCutOffV"
                                                placeholder="" value="'.$CutOffVoltage.'">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered mb-0" id="initialMeasureTbl">
                                    <thead>
                                        <tr>
                                            <th colspan="3" style="white-space:nowrap;">Pre-test Measurements</th>
                                            <th colspan="3" style="white-space:nowrap;">Post-test Measurements</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-bold-500" style="white-space:nowrap;">
                                                <h6>OCV, V.</h6>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" id="PreCapacityOCV" class="form-control" name="PreCapacityOCV"
                                                placeholder="" value="'.$OCV.'">
                                            </td>
                                            <td class="text-bold-500" style="white-space:nowrap;">
                                                <h6>Discharge time, mins</h6>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" id="PostCapacityDischargeTime" class="form-control" name="PostCapacityDischargeTime"
                                                placeholder="" value="'.$DischargeTime.'">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-500" style="white-space:nowrap;"><h6>Midtronics CCA, A</h6>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" id="CapacityCCA" class="form-control" name="CapacityCCA"
                                                placeholder="" value="'.$MidtronicsCCA.'">
                                            </td>
                                            <td class="text-bold-500" style="white-space:nowrap;"><h6 class="superscript">Acid SG/ Temp, ºC <span>1, 2</span></h6>
                                            </td>
                                            <td>
                                                <input type="text" id="Capacity1SG1" class="form-control" name="Capacity1SG1"
                                                placeholder="" value="'.$AcidSG1C1.'">
                                            </td>
                                            <td>
                                                <input type="text" id="Capacity1SG2" class="form-control" name="Capacity1SG2"
                                                placeholder="" value="'.$AcidSG1C2.'">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-500" style="white-space:nowrap;"><h6 class="superscript">Acid SG/ Temp, ºC <span>1</span> </h6>
                                            </td>
                                            <td>
                                                <input type="text" id="Capacity2SG1" class="form-control" name="Capacity2SG1"
                                                placeholder="" value="'.$AcidSG2C1.'">
                                            </td>
                                            <td>
                                                <input type="text" id="Capacity2SG2" class="form-control" name="Capacity2SG2"
                                                placeholder="" value="'.$AcidSG2C2.'">
                                            </td>

                                            <td class="text-bold-500" style="white-space:nowrap;"><h6>Data file name </h6>
                                            </td>
                                            <td colspan="2">
                                                <input type="text" id="CapacityDataFileName" class="form-control" name="CapacityDataFileName"
                                                placeholder="" value="'.$dataFileName.'">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group mt-3 ">
                                <label for="CapacityFormRemarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="CapacityFormRemarks" rows="2">For Approval</textarea>
                            </div>

                            <div class="mt-4">
                                <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">1</span> Measure the SG of the middle cell. If SG is measured using a float hydrometer, measure the electrolyte temperature as well. No need to do this if a digital hydrometer is used as its measurement is already temperature-compensated.</p>

                                <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">2</span> For RC test, electrolyte temperature after testing should be measured regardless of type hydrometer used.</p>
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
                            </div>';

                            if($StatusID==9){
                                $actionBtn = '
                                            <button type="button" class="btn btn-info ml-1" id="ReviewCapacityBtn" onclick="ReviewCapacityBtn('.$TestDataInputID.' , '.$ptpTestScheduleID.')">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reviewed</span>
                                            </button>
                                            
                                            <button type="button" class="btn btn-warning ml-1" id="RetestCapacityBtn" onclick="RejectCapacityStatBtn('.$TestDataInputID.', '.$ptpTestScheduleID.', '.$formCategoryId.')">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reject</span>
                                            </button>';
                            }
                            else if($StatusID==11){
                                    $actionBtn = '<button type="button" class="btn btn-warning ml-1" id="RetestCapacityBtn" onclick="RejectCapacityStatBtn('.$TestDataInputID.', '.$ptpTestScheduleID.', '.$formCategoryId.')">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reject</span>
                                </button>

                                                <button type="button" class="btn btn-primary ml-1" id="ApprovedTestFormBtn" onclick="ApprovalCapacityBtn('.$TestDataInputID.', '.$ptpTestScheduleID.')">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Approved</span>
                                                </button>';
                            }
                            else if($StatusID==14){
                                $actionBtn = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="button" class="btn btn-primary ml-1" id="ApprovedTestFormBtn" onclick="SubmitChangesCapacityBtn('.$TestDataInputID.', '.$ptpTestScheduleID.')">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Submit Changes</span>
                                                </button>';
                            }
                }
            }
        }
        else{
            $output .= '<div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Battery No.</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" id="TestDataBatNo" class="form-control font-bold" name="TestDataBatNo" value="'.$testSampleSysText.'"
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
                                        <input type="text" id="TestDataTestDate" class="form-control font-bold" name="TestDataTestDate" value="'.$currentDate.'"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <h6 class="text-center">Capacity Test</h6>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Test Type</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="hidden" id="ptpTestScheduleID" value="'.$ptpTestScheduleID.'">

                                        <input type="hidden" id="formCategoryId" value="'.$formCategoryId.'">

                                        <input type="hidden" id="testTableId" value="'.$testTableId.'">

                                        <input type="hidden" id="sampleId" value="'.$sampleId.'">

                                        <input type="hidden" id="TestDataStatusID" value="'.$TestStatusID.'">

                                        <input type="text" id="TestDataTestType" class="form-control" name="TestDataTestType" value="'.$currentTestTxt.'"
                                            disabled>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cycle No.</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="hidden" id="TestDataCycleNoData" class="form-control" value="1">

                                        <input type="text" id="TestDataCycleNo" class="form-control" name="TestDataCycleNo"
                                            disabled value="1">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-5">
                                        <label>Equipment No.</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="hidden" id="TestDataEquipmentCapacityID" name="TestDataEquipmentCapacity">

                                        <input type="text" id="TestDataEquipmentCapacity" class="form-control" name="TestDataEquipmentCapacity"
                                            disabled>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h6 class="text-center">Test Parameters</h6>
                                <div class="row">
                                    <div class="col-md-7">
                                        <label>Discharge current, A</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="TestDataDischargeA" class="form-control" name="TestDataDischargeA"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7">
                                        <label>Cut-off voltage, V</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="TestDataCutOffV" class="form-control" name="TestDataCutOffV"
                                            placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered mb-0" id="initialMeasureTbl">
                                <thead>
                                    <tr>
                                        <th colspan="3" style="white-space:nowrap;">Pre-test Measurements</th>
                                        <th colspan="3" style="white-space:nowrap;">Post-test Measurements</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-bold-500" style="white-space:nowrap;">
                                            <h6>OCV, V.</h6>
                                        </td>
                                        <td colspan="2">
                                            <input type="text" id="PreCapacityOCV" class="form-control" name="PreCapacityOCV"
                                            placeholder="">
                                        </td>
                                        <td class="text-bold-500" style="white-space:nowrap;">
                                            <h6>Discharge time, mins</h6>
                                        </td>
                                        <td colspan="2">
                                            <input type="text" id="PostCapacityDischargeTime" class="form-control" name="PostCapacityDischargeTime"
                                            placeholder="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500" style="white-space:nowrap;"><h6>Midtronics CCA, A</h6>
                                        </td>
                                        <td colspan="2">
                                            <input type="text" id="CapacityCCA" class="form-control" name="CapacityCCA"
                                            placeholder="">
                                        </td>
                                        <td class="text-bold-500" style="white-space:nowrap;"><h6 class="superscript">Acid SG/ Temp, ºC <span>1, 2</span></h6>
                                        </td>
                                        <td>
                                            <input type="text" id="Capacity1SG1" class="form-control" name="Capacity1SG1"
                                            placeholder="">
                                        </td>
                                        <td>
                                            <input type="text" id="Capacity1SG2" class="form-control" name="Capacity1SG2"
                                            placeholder="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500" style="white-space:nowrap;"><h6 class="superscript">Acid SG/ Temp, ºC <span>1</span> </h6>
                                        </td>
                                        <td>
                                            <input type="text" id="Capacity2SG1" class="form-control" name="Capacity2SG1"
                                            placeholder="">
                                        </td>
                                        <td>
                                            <input type="text" id="Capacity2SG2" class="form-control" name="Capacity2SG2"
                                            placeholder="">
                                        </td>

                                        <td class="text-bold-500" style="white-space:nowrap;"><h6>Data file name </h6>
                                        </td>
                                        <td colspan="2">
                                            <input type="text" id="CapacityDataFileName" class="form-control" name="CapacityDataFileName"
                                            placeholder="">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mt-3 ">
                            <label for="CapacityFormRemarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="CapacityFormRemarks" rows="2">For Approval</textarea>
                        </div>

                        <div class="mt-4">
                            <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">1</span> Measure the SG of the middle cell. If SG is measured using a float hydrometer, measure the electrolyte temperature as well. No need to do this if a digital hydrometer is used as its measurement is already temperature-compensated.</p>

                            <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">2</span> For RC test, electrolyte temperature after testing should be measured regardless of type hydrometer used.</p>
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
                        </div>';

                        $actionBtn = '<button type="button" class="btn btn-secondary"   data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="button" class="btn btn-primary ml-1" id="SubmitCapacityTestForm" onclick="SubmitCapacity()">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Submit</span>
                        </button>';
        }

        $arr = array(
            'content' => $output,
            'actionBtn' => $actionBtn
        );

        echo json_encode($arr);
    }
    else if($_POST['action'] == 'submitReviewedCapacityform'){
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $ptpTestScheduleID = isset($_POST['ptpTestScheduleID']) ? $_POST['ptpTestScheduleID'] : 0;
        $CapacityRemarks = isset($_POST['CapacityRemarks']) ? $_POST['CapacityRemarks'] : '';
        if($CapacityRemarks=='' || $CapacityRemarks==null){
            $CapacityRemarks = 'For Approval';
        }
        $employeeID = $_COOKIE['BTL_employeeID'];
        $statusID = 11; // status: for approval
        $result = 0;
        $data = array($ptpTestScheduleID, $employeeID, $statusID, $CapacityRemarks);
        $sql = "insert into TestDataStatus_tbl (TestPTPScheduleID, EmployeeID, StatusID, Remarks) ";
        $sql .= "values (?, ?, ?, ?) ";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $data);

        if($execute){
            $result = 1;
        }

        echo json_encode($result);
    }
    else if($_POST['action'] == 'submitRetestStatCapacityform'){
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $PtpTestScheduleID = isset($_POST['PtpTestScheduleID']) ? $_POST['PtpTestScheduleID'] : 0;
        $formCategoryID = isset($_POST['formCategoryID']) ? $_POST['formCategoryID'] : 0;
        $result = 0;

        $CapacityRemarks = isset($_POST['CapacityRemarks']) ? $_POST['CapacityRemarks'] : '';
        if($CapacityRemarks=='' || $CapacityRemarks==null){
            $CapacityRemarks = 'For Retest';
        }
        if($formCategoryID == 2){
            $updateTestDetail = "update HRDTestDetails_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
            $testDetailExec = odbc_exec($connServer, $updateTestDetail);
            if($testDetailExec){
                $updateDischargeProfile = "update HRDTDischargeProfile_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
                $updateDischargeProfileExec = odbc_exec($connServer, $updateDischargeProfile);
                if($updateDischargeProfileExec){
                    $updateTestResult = "update HRDTTestResult_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
                    $updateDischargeProfileExec = odbc_exec($connServer, $updateTestResult);

                    if($updateDischargeProfileExec){
                        $employeeID = $_COOKIE['BTL_employeeID'];
                        $statusID = 15; // status: On-going Retest
                        $result = 0;
                        $data = array($testDataInputId, $employeeID, $statusID, $CapacityRemarks);
                        $sql = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) ";
                        $sql .= "values (?, ?, ?, ?) ";
                        $stmt = odbc_prepare($connServer, $sql);
                        $execute = odbc_execute($stmt, $data);

                        if($execute){
                            $result = 1;
                        }
                    }
                }
            }
        }
        else{
            $employeeID = $_COOKIE['BTL_employeeID'];
            $statusID = 10; // status: for retest
            $result = 0;
            $data = array($testDataInputId, $employeeID, $statusID, $CapacityRemarks);
            $sql = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) ";
            $sql .= "values (?, ?, ?, ?) ";
            $stmt = odbc_prepare($connServer, $sql);
            $execute = odbc_execute($stmt, $data);

            if($execute){
                $result = 1;
            }
        }
        

        echo json_encode($result);
    }
    else if($_POST['action'] == 'submitRetestCapacityForm'){
        $output = 0;
        $formaCatID = isset($_POST['formaCatID']) ? $_POST['formaCatID'] : 'n/a';
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $TestPTPScheduleID = isset($_POST['ptpTestScheduleID']) ? $_POST['ptpTestScheduleID'] : 'n/a';
        $sampleID = isset($_POST['sampleID']) ? $_POST['sampleID'] : 'n/a';
        $testTableID = isset($_POST['testTableID']) ? $_POST['testTableID'] : 'n/a';
        $cycleNo = isset($_POST['cycleNo']) ? $_POST['cycleNo'] : 'n/a';
        $dataFileName = isset($_POST['dataFileName']) ? $_POST['dataFileName'] : 'n/a';
        $waterBathCellNoID = isset($_POST['waterBathCellNoID']) ? $_POST['waterBathCellNoID'] : 'n/a';
        $dischargeCurrent = isset($_POST['dischargeCurrent']) ? $_POST['dischargeCurrent'] : 'n/a';
        $cuttOffVoltage = isset($_POST['cuttOffVoltage']) ? $_POST['cuttOffVoltage'] : 'n/a';
        $ocv = isset($_POST['ocv']) ? $_POST['ocv'] : 'n/a';
        $dischargeTimeMins = isset($_POST['dischargeTimeMins']) ? $_POST['dischargeTimeMins'] : 'n/a';
        $cca = isset($_POST['cca']) ? $_POST['cca'] : 'n/a';
        $PostCapacity1SG1 = isset($_POST['PostCapacity1SG1']) ? $_POST['PostCapacity1SG1'] : 'n/a';
        $PostCapacity2SG2 = isset($_POST['PostCapacity2SG2']) ? $_POST['PostCapacity2SG2'] : 'n/a';
        $PreCapacity1SG1 = isset($_POST['PreCapacity1SG1']) ? $_POST['PreCapacity1SG1'] : 'n/a';
        $PreCapacity2SG2 = isset($_POST['PreCapacity2SG2']) ? $_POST['PreCapacity2SG2'] : 'n/a';
        $CapacityRemarks = isset($_POST['CapacityRemarks']) ? $_POST['CapacityRemarks'] : 'For Review';

        $employeeID = $_COOKIE['BTL_employeeID'];
        $TestDataInputLastID = 0;
        $updateTestDataInput = "update TestDataInput_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
        $updateTestDataInputExec = odbc_exec($connServer, $updateTestDataInput);
        if($updateTestDataInputExec){
            $testData = array($formaCatID, $sampleID, $testTableID, $cycleNo, $TestPTPScheduleID);
            $InsertTestDataInput = "insert into TestDataInput_tbl (FormCategoryID, SampleID, TestTableID, Cycleno, TestPTPScheduleID) values (?, ?, ?, ?, ?) ";
            $testDataInsertStmt = odbc_prepare($connServer, $InsertTestDataInput);
            $testDataInputExec = odbc_execute($testDataInsertStmt, $testData);
            if($testDataInputExec){
                $TestDataInputLastID = GetLast_Id($connServer);
                $updatesql1 = "update CapacityTestDetails_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
                $update1exec = odbc_exec($connServer, $updatesql1);
                if($update1exec){
                    $detailsData = array($TestDataInputLastID, $waterBathCellNoID, $dischargeCurrent, $cuttOffVoltage, $dataFileName);
                    $insertCapacityDetails = "insert into CapacityTestDetails_tbl (TestDataInputID, WaterBathCellNoID, DischargeCurrent, CuttOffV, DataFileName) values (?, ?, ?, ?, ?)";
                    $stmt_details = odbc_prepare($connServer, $insertCapacityDetails);
                    $detailsExecute = odbc_execute($stmt_details, $detailsData);
                    $output = 1;

                    if($detailsExecute){
                        $updatesql2 = "update CapacityPreTestMeasurement_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
                        $update2exec = odbc_exec($connServer, $updatesql2);
                        $output = 1;
                        if($update2exec){
                            $dataPretest = array($TestDataInputLastID, $ocv, $cca, $PreCapacity1SG1, $PreCapacity2SG2);
                            $insertPretest = "insert into CapacityPreTestMeasurement_tbl (TestDataInputID, OCV, CCA, SG1, SG2) values (?, ?, ?, ?, ?) ";

                            $stmt_preTest = odbc_prepare($connServer, $insertPretest);
                            $PretestExecute = odbc_execute($stmt_preTest, $dataPretest);
                            $output = 1;

                            if($PretestExecute){
                                $updatesql3 = "update CapacityPostTestMeasurement_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
                                $update3exec = odbc_exec($connServer, $updatesql3);
                                $output = 1;

                                if($update3exec){
                                    $dataPosttest = array($TestDataInputLastID, $dischargeTimeMins, $dischargeTimeMins, $PostCapacity1SG1, $PostCapacity2SG2);
                                    $insertPosttest = "insert into CapacityPostTestMeasurement_tbl (TestDataInputID, DCHMins, SG1, SG2) values (?, ?, ?, ?) ";

                                    $stmt_postTest = odbc_prepare($connServer, $insertPosttest);
                                    $PosttestExecute = odbc_execute($stmt_postTest, $dataPosttest);
                                    $output = 1;
                                    $statusID = 11; //For Approval
                                    if($PosttestExecute){
                                        $dataAddstatus = array($TestDataInputLastID, $employeeID, $statusID, $CapacityRemarks);
                                        $insertAddstatus = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) values (?, ?, ?, ?) ";
                
                                        $stmt_addStatus = odbc_prepare($connServer, $insertAddstatus);
                                        $addStatusExecute = odbc_execute($stmt_addStatus, $dataAddstatus);
                                        $output = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        
        echo json_encode($output);
    }
    else if($_POST['action'] == 'submitChangeDataStatCapacityform'){
        $TestDataInputID = isset($_POST['TestDataInputID']) ? $_POST['TestDataInputID'] : 0;
        $PtpTestScheduleID = isset($_POST['PtpTestScheduleID']) ? $_POST['PtpTestScheduleID'] : 0;
        $ChangeDataRemarks = isset($_POST['ChangeDataRemarks']) ? $_POST['ChangeDataRemarks'] : '';
        if($ChangeDataRemarks=='' || $ChangeDataRemarks==null){
            $ChangeDataRemarks = 'For Change Data';
        }
        $employeeID = $_COOKIE['BTL_employeeID'];
        $statusID = 14; // status: for change data
        $result = 0;
        $data = array($TestDataInputID, $employeeID, $statusID, $ChangeDataRemarks);
        $sql = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) ";
        $sql .= "values (?, ?, ?, ?) ";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $data);

        if($execute){
            $result = 1;
        }

        echo json_encode($result);
    }
    else if($_POST['action'] == 'submitChangeDataCapacityForm'){
        $output = 0;
        $formaCatID = isset($_POST['formaCatID']) ? $_POST['formaCatID'] : 'n/a';
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $TestPTPScheduleID = isset($_POST['ptpTestScheduleID']) ? $_POST['ptpTestScheduleID'] : 'n/a';
        $sampleID = isset($_POST['sampleID']) ? $_POST['sampleID'] : 'n/a';
        $testTableID = isset($_POST['testTableID']) ? $_POST['testTableID'] : 'n/a';
        $cycleNo = isset($_POST['cycleNo']) ? $_POST['cycleNo'] : 'n/a';
        $dataFileName = isset($_POST['dataFileName']) ? $_POST['dataFileName'] : 'n/a';
        $waterBathCellNoID = isset($_POST['waterBathCellNoID']) ? $_POST['waterBathCellNoID'] : 'n/a';
        $dischargeCurrent = isset($_POST['dischargeCurrent']) ? $_POST['dischargeCurrent'] : 'n/a';
        $cuttOffVoltage = isset($_POST['cuttOffVoltage']) ? $_POST['cuttOffVoltage'] : 'n/a';
        $ocv = isset($_POST['ocv']) ? $_POST['ocv'] : 'n/a';
        $dischargeTimeMins = isset($_POST['dischargeTimeMins']) ? $_POST['dischargeTimeMins'] : 'n/a';
        $cca = isset($_POST['cca']) ? $_POST['cca'] : 'n/a';
        $PostCapacity1SG1 = isset($_POST['PostCapacity1SG1']) ? $_POST['PostCapacity1SG1'] : 'n/a';
        $PostCapacity2SG2 = isset($_POST['PostCapacity2SG2']) ? $_POST['PostCapacity2SG2'] : 'n/a';
        $PreCapacity1SG1 = isset($_POST['PreCapacity1SG1']) ? $_POST['PreCapacity1SG1'] : 'n/a';
        $PreCapacity2SG2 = isset($_POST['PreCapacity2SG2']) ? $_POST['PreCapacity2SG2'] : 'n/a';
        $CapacityRemarks = isset($_POST['CapacityRemarks']) ? $_POST['CapacityRemarks'] : 'For Review';

        $employeeID = $_COOKIE['BTL_employeeID'];
        $TestDataInputLastID = 0;
        $updateTestDataInput = "update TestDataInput_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
        $updateTestDataInputExec = odbc_exec($connServer, $updateTestDataInput);
        if($updateTestDataInputExec){
            $testData = array($formaCatID, $sampleID, $testTableID, $cycleNo, $TestPTPScheduleID);
            $InsertTestDataInput = "insert into TestDataInput_tbl (FormCategoryID, SampleID, TestTableID, Cycleno, TestPTPScheduleID) values (?, ?, ?, ?, ?) ";
            $testDataInsertStmt = odbc_prepare($connServer, $InsertTestDataInput);
            $testDataInputExec = odbc_execute($testDataInsertStmt, $testData);
            if($testDataInputExec){
                $TestDataInputLastID = GetLast_Id($connServer);
                $updatesql1 = "update CapacityTestDetails_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
                $update1exec = odbc_exec($connServer, $updatesql1);
                if($update1exec){
                    $detailsData = array($TestDataInputLastID, $waterBathCellNoID, $dischargeCurrent, $cuttOffVoltage, $dataFileName);
                    $insertCapacityDetails = "insert into CapacityTestDetails_tbl (TestDataInputID, WaterBathCellNoID, DischargeCurrent, CuttOffV, DataFileName) values (?, ?, ?, ?, ?)";
                    $stmt_details = odbc_prepare($connServer, $insertCapacityDetails);
                    $detailsExecute = odbc_execute($stmt_details, $detailsData);
                    $output = 1;

                    if($detailsExecute){
                        $updatesql2 = "update CapacityPreTestMeasurement_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
                        $update2exec = odbc_exec($connServer, $updatesql2);
                        $output = 1;
                        if($update2exec){
                            $dataPretest = array($TestDataInputLastID, $ocv, $cca, $PreCapacity1SG1, $PreCapacity2SG2);
                            $insertPretest = "insert into CapacityPreTestMeasurement_tbl (TestDataInputID, OCV, CCA, SG1, SG2) values (?, ?, ?, ?, ?) ";

                            $stmt_preTest = odbc_prepare($connServer, $insertPretest);
                            $PretestExecute = odbc_execute($stmt_preTest, $dataPretest);
                            $output = 1;

                            if($PretestExecute){
                                $updatesql3 = "update CapacityPostTestMeasurement_tbl set IsActive = 0, DateModified = getdate() where TestDataInputID = ".$testDataInputId;
                                $update3exec = odbc_exec($connServer, $updatesql3);
                                $output = 1;

                                if($update3exec){
                                    $dataPosttest = array($TestDataInputLastID, $dischargeTimeMins, $dischargeTimeMins, $PostCapacity1SG1, $PostCapacity2SG2);
                                    $insertPosttest = "insert into CapacityPostTestMeasurement_tbl (TestDataInputID, DCHMins, SG1, SG2) values (?, ?, ?, ?) ";

                                    $stmt_postTest = odbc_prepare($connServer, $insertPosttest);
                                    $PosttestExecute = odbc_execute($stmt_postTest, $dataPosttest);
                                    $output = 1;
                                    $statusID = 11; //For Approval
                                    if($PosttestExecute){
                                        $dataAddstatus = array($TestDataInputLastID, $employeeID, $statusID, $CapacityRemarks);
                                        $insertAddstatus = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) values (?, ?, ?, ?) ";
                
                                        $stmt_addStatus = odbc_prepare($connServer, $insertAddstatus);
                                        $addStatusExecute = odbc_execute($stmt_addStatus, $dataAddstatus);
                                        $output = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        
        echo json_encode($output);
    }
    else if($_POST['action'] == 'submitApprovedCapacityform'){
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $CapacityRemarks = isset($_POST['CapacityRemarks']) ? $_POST['CapacityRemarks'] : '';
        $ptpScheduleid = isset($_POST['ptpScheduleid']) ? $_POST['ptpScheduleid'] : 0;
        if($CapacityRemarks=='' || $CapacityRemarks==null){
            $CapacityRemarks = 'Test Approved';
        }
        $employeeID = $_COOKIE['BTL_employeeID'];
        $statusID = 12; // status: for approval
        $result = 0;
        $data = array($testDataInputId, $employeeID, $statusID, $CapacityRemarks);
        $sql = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) ";
        $sql .= "values (?, ?, ?, ?) ";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $data);

        if($execute){
            $result = 1;
            $updateSql = "update TestPTPSchedule_tbl set TestStatus = 1, DateModified = getdate() where TestPTPScheduleID = ".$ptpScheduleid;
            $updateExecute = odbc_exec($connServer, $updateSql);
            if($updateSql){
                $result = 1;
            }
            else{
                $result = 0;
            }
        }

        echo json_encode($result);
    }
    //-------------Capacity Test Form End-------------
    
    //-------------HRDT Test Form-------------
    else if($_POST['action'] == 'fetchHRDTForm'){
        $ptpTestScheduleID = isset($_POST['ptpTestScheduleID']) ? $_POST['ptpTestScheduleID'] : 0;
        $HaveRow = isset($_POST['HaveRow']) ? $_POST['HaveRow'] : 0;
        $sampleId = isset($_POST['sampleId']) ? $_POST['sampleId'] : 0;
        $currentTestTxt = isset($_POST['currentTestTxt']) ? $_POST['currentTestTxt'] : null;
        $testTableId = isset($_POST['testTableId']) ? $_POST['testTableId'] : 0;
        $formCategoryId = isset($_POST['formCategoryId']) ? $_POST['formCategoryId'] : 0;
        $testSampleSysText = isset($_POST['testSampleSysText']) ? $_POST['testSampleSysText'] : null;
        $currentDate = isset($_POST['currentDate']) ? $_POST['currentDate'] : null;
        $waterBathCellNoId = isset($_POST['waterBathCellNoId']) ? $_POST['waterBathCellNoId'] : 0;
        $TestStatusID = isset($_POST['TestStatusID']) ? $_POST['TestStatusID'] : 0;
        $HaveRow = isset($_POST['HaveRow']) ? $_POST['HaveRow'] : 0;
        $output = '';
        $actionBtn = '';
        $TestDataInputID = 0;
        if($HaveRow !=0){
            $sql = "select testData.TestDataInputID, testDetail.EquipmentNo, testDetail.BatteryTemp, testDetail.OCV, testDetail.CCA, testDetail.IR, testDetail.DataFileName, status.StatusID, status.Remarks, testDetail.HRDTestDetailID from TestDataInput_tbl testData
            cross apply (
                select Top 1 * from HRDTestDetails_tbl details where testData.TestDataInputID = details.TestDataInputID order by details.DateCreated DESC 
            ) as testDetail
            cross apply (
                select top 1 StatusID, Remarks from TestDataStatus_tbl where TestPTPScheduleID = testData.TestPTPScheduleID order by DateCreated DESC
            ) as status
            where testData.TestPTPScheduleID = ".$ptpTestScheduleID." and testData.IsActive = 1 and testData.IsDeleted = 0 ";
            $execute = odbc_exec($connServer, $sql);
            if($execute){
                $rowData = odbc_fetch_array($execute);
                $TestDataInputID = $rowData['TestDataInputID'];
                $EquipmentNo = $rowData['EquipmentNo'];
                $BatteryTemp = $rowData['BatteryTemp'];
                $OCV = $rowData['OCV'];
                $MidtronicsCCA = $rowData['CCA'];
                $IR = $rowData['IR'];
                $dataFileName = $rowData['DataFileName'];
                $StatusID = $rowData['StatusID'];
                $StatusRemarks = $rowData['Remarks'];
                $HRDTestDetailId = $rowData['HRDTestDetailID'];

                if($StatusID == 10 || $StatusID == 15){
                    $output .= '<div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Battery No.</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" id="TestDataBatNoHRDT" class="form-control font-bold" name="TestDataBatNo" value="'.$testSampleSysText.'"
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
                                        <input type="text" id="TestDataTestDateHRDT" class="form-control font-bold" name="TestDataTestDate" value="'.$currentDate.'"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <h6 class="text-center">Test Parameters and Equipment</h6>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Test Type</label>
                                    </div>
                                    <div class="col-md-6 form-group">

                                        <input type="hidden" id="HRDTestDetailId" value="'.$HRDTestDetailId.'">

                                        <input type="hidden" id="TestDataInputiID" value="'.$TestDataInputID.'">

                                        <input type="hidden" id="ptpTestScheduleID" value="'.$ptpTestScheduleID.'">

                                        <input type="hidden" id="formCategoryId" value="'.$formCategoryId.'">

                                        <input type="hidden" id="testTableId" value="'.$testTableId.'">

                                        <input type="hidden" id="sampleId" value="'.$sampleId.'">

                                        <input type="hidden" id="TestDataStatusID" value="'.$StatusID.'">

                                        <input type="text" id="TestDataTestTypeHRDT" class="form-control" name="TestDataTestType" value="'.$currentTestTxt.'"
                                            disabled>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cycle No.</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" id="TestDataCycleNo" class="form-control" name="TestDataCycleNo"
                                            disabled value="1">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-5">
                                        <label>Equipment No.</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" id="HRDTTestDataEqipment" class="form-control" name="HRDTTestDataEqipment" value="'.$EquipmentNo.'">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="superscript">Battery temp., ºC <span>1</span></label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTBatTemp" class="form-control" name="HRDTBatTemp" value="'.$BatteryTemp.'">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h6 class="text-center">Pre-Test Measurements</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>OCV, V</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTOCV" class="form-control" name="HRDTOCV"
                                            placeholder="" value="'.$OCV.'">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Midtronics CCA, A</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTCCA" class="form-control" name="HRDTCCA"
                                            placeholder="" value="'.$MidtronicsCCA.'">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>IR, mΩ</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTIR" class="form-control" name="HRDTIR"
                                            placeholder="" value="'.$IR.'">
                                    </div>
                                </div>
                                <div class="row mt">
                                    <div class="col-md-6">
                                        <label>Data File Name</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" id="HRDTDataFileName" class="form-control" name="HRDTDataFileName"
                                            placeholder="" value="'.$dataFileName.'">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between mb-1">
                                    <div>
                                        <h6 class="text-center float-start">Discharge Profile</h6>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm float-end" id="ShowProfileBtn" onclick="HRDTDischargeProfileModalBtn('.$ptpTestScheduleID.')"><i class="fa fa-plus-circle"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="initialMeasureTbl">
                                        <thead>
                                            <tr>
                                                <th style="white-space:nowrap;">Step</th>
                                                <th style="white-space:nowrap;">Discharge Current, A</th>
                                                <th style="white-space:nowrap;">Time, s</th>
                                            </tr>
                                        </thead>
                                        <tbody id="HRDTDischargeProfile_tbl">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between mb-1">
                                    <div>
                                        <h6 class="text-center float-start">Test Results</h6>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm float-end"><i class="fa fa-plus-circle" id="HRDTTestResultBtn" onclick="HRDTTestResultModalBtn('.$ptpTestScheduleID.')"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="initialMeasureTbl">
                                        <thead>
                                            <tr>
                                                <th style="white-space:nowrap;">Time, s</th>
                                                <th style="white-space:nowrap;">Battery Voltage, V</th>
                                            </tr>
                                        </thead>
                                        <tbody id="HRDTTestResult_tbl">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3 ">
                            <label for="HRDTFormRemarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="HRDTFormRemarks" rows="2">For Review</textarea>
                        </div>

                        <div class="mt-4">
                            <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">*</span> The battery temperature should be taken from the electrolyte temperature of the middle cell. If the electrolyte is not accessible, it can be assumed that the battery temperature will be the same as the freezer temperature 24 hrs after placing the battery inside the freezer.</p>
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
                        </div>';

                        $actionBtn = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-warning ml-1" id="SubmitTestForm" onclick="SubmitHRDT()">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Submit</span>
                                        </button>';
                }
                else{
                    $output .= '<div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Battery No.</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" id="TestDataBatNoHRDT" class="form-control font-bold" name="TestDataBatNo" value="'.$testSampleSysText.'"
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
                                        <input type="text" id="TestDataTestDateHRDT" class="form-control font-bold" name="TestDataTestDate" value="'.$currentDate.'"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <h6 class="text-center">Test Parameters and Equipment</h6>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Test Type</label>
                                    </div>
                                    <div class="col-md-6 form-group">

                                        <input type="hidden" id="TestDataInputiID" value="'.$TestDataInputID.'">

                                        <input type="hidden" id="ptpTestScheduleID" value="'.$ptpTestScheduleID.'">

                                        <input type="hidden" id="formCategoryId" value="'.$formCategoryId.'">

                                        <input type="hidden" id="testTableId" value="'.$testTableId.'">

                                        <input type="hidden" id="sampleId" value="'.$sampleId.'">

                                        <input type="hidden" id="TestDataStatusID" value="'.$TestStatusID.'">

                                        <input type="hidden" id="StatusID" value="'.$StatusID.'">

                                        <input type="text" id="TestDataTestTypeHRDT" class="form-control" name="TestDataTestType" value="'.$currentTestTxt.'"
                                            disabled>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cycle No.</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" id="TestDataCycleNo" class="form-control" name="TestDataCycleNo"
                                            disabled value="1">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-5">
                                        <label>Equipment No.</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" id="HRDTTestDataEqipment" class="form-control" name="HRDTTestDataEqipment" value="'.$EquipmentNo.'">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="superscript">Battery temp., ºC <span>1</span></label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTBatTemp" class="form-control" name="HRDTBatTemp" value="'.$BatteryTemp.'">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h6 class="text-center">Pre-Test Measurements</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>OCV, V</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTOCV" class="form-control" name="HRDTOCV"
                                            placeholder="" value="'.$OCV.'">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Midtronics CCA, A</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTCCA" class="form-control" name="HRDTCCA"
                                            placeholder="" value="'.$MidtronicsCCA.'">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>IR, mΩ</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTIR" class="form-control" name="HRDTIR"
                                            placeholder="" value="'.$IR.'">
                                    </div>
                                </div>
                                <div class="row mt">
                                    <div class="col-md-6">
                                        <label>Data File Name</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" id="HRDTDataFileName" class="form-control" name="HRDTDataFileName"
                                            placeholder="" value="'.$dataFileName.'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between mb-1">
                                    <div>
                                        <h6 class="text-center float-start">Discharge Profile</h6>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm float-end" id="ShowProfileBtn" onclick="HRDTDischargeProfileModalBtn('.$ptpTestScheduleID.')"><i class="fa fa-plus-circle"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive shadow-sm bg-white p-0 rounded mb-0">
                                    <table class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="white-space:nowrap;">Step</th>
                                                <th style="white-space:nowrap;">Discharge Current, A</th>
                                                <th style="white-space:nowrap;">Time, s</th>';
                                                if($StatusID==14){
                                                    $output .= '<th style="white-space:nowrap;">Action</th>';
                                                }
                                $output .= '</tr>
                                        </thead>
                                        <tbody id="HRDTDischargeProfile_tbl" >
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between mb-1">
                                    <div>
                                        <h6 class="text-center float-start">Test Results</h6>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm float-end" id="HRDTTestResultBtn" onclick="HRDTTestResultModalBtn('.$ptpTestScheduleID.')"><i class="fa fa-plus-circle"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive shadow-sm bg-white p-0 rounded mb-0">
                                    <table class="table table-hover" id="initialMeasureTbl">
                                        <thead>
                                            <tr>
                                                <th style="white-space:nowrap;">Time, s</th>
                                                <th style="white-space:nowrap;">Battery Voltage, V</th>';
                                                if($StatusID==14){
                                                    $output .= '<th style="white-space:nowrap;">Action</th>';
                                                }
                                $output .= '</tr>
                                        </thead>
                                        <tbody id="HRDTTestResult_tbl">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3 ">
                            <label for="HRDTFormRemarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="HRDTFormRemarks" rows="2">For Approval</textarea>
                        </div>
                        
                        <div class="mt-4">
                            <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">*</span> The battery temperature should be taken from the electrolyte temperature of the middle cell. If the electrolyte is not accessible, it can be assumed that the battery temperature will be the same as the freezer temperature 24 hrs after placing the battery inside the freezer.</p>
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
                        </div>';

                        if($StatusID==9){
                            $actionBtn = '
                                        <button type="button" class="btn btn-info ml-1" id="ReviewHRDTBtn" onclick="ReviewHRDTBtn('.$TestDataInputID.')">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reviewed</span>
                                        </button>
                                        
                                        <button type="button" class="btn btn-warning ml-1" id="RetestHRDTBtn" onclick="RejectCapacityStatBtn('.$TestDataInputID.', '.$ptpTestScheduleID.', '.$formCategoryId.')">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reject</span>
                                        </button>
                                        ';
                        }
                        else if($StatusID==11){
                                $actionBtn = '<button type="button" class="btn btn-warning ml-1" id="RetestCapacityBtn" onclick="RejectCapacityStatBtn('.$TestDataInputID.', '.$ptpTestScheduleID.', '.$formCategoryId.')">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reject</span>
                            </button>

                                <button type="button" class="btn btn-primary ml-1" id="ApprovedTestFormBtn" onclick="ApprovalCapacityBtn('.$TestDataInputID.', '.$ptpTestScheduleID.')">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Approved</span>
                                </button>';
                        }
                        else if($StatusID==13){
                            $actionBtn = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="ApprovedHRDTTestFormBtn" onclick="SubmitHRDT()">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Submit</span>
                                            </button>';
                        }
                        else if($StatusID==14){
                            $actionBtn = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" id="ApprovedTestFormBtn" onclick="SubmitChangesHRDTBtn('.$TestDataInputID.', '.$ptpTestScheduleID.')">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Submit Changes</span>
                                            </button>';
                        }
                }
            }
        }
        else{
            $ptpIdArray  = array();
            $selectIfExist =  "select * from TestDataInput_tbl where TestPTPScheduleID = ". $ptpTestScheduleID;
            $executeExists = odbc_exec($connServer, $selectIfExist);
            $rowCount = odbc_num_rows($executeExists);

            if($rowCount==0){
                $HRDTData = array($formCategoryId, $sampleId, $testTableId, 1, $ptpTestScheduleID);
                $insertHRDTTestDataInput = "Insert into TestDataInput_tbl (FormCategoryID, SampleID, TestTableID, CycleNo, TestPTPScheduleID ) values (?, ?, ?, ?, ?)";
                $stmt = odbc_prepare($connServer, $insertHRDTTestDataInput);
                $execute = odbc_execute($stmt, $HRDTData);
            }

            $output .= '<div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Battery No.</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" id="TestDataBatNoHRDT" class="form-control font-bold" name="TestDataBatNo" value="'.$testSampleSysText.'"
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
                                        <input type="text" id="TestDataTestDateHRDT" class="form-control font-bold" name="TestDataTestDate" value="'.$currentDate.'"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <h6 class="text-center">Test Parameters and Equipment</h6>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Test Type</label>
                                    </div>
                                    <div class="col-md-6 form-group">

                                        <input type="hidden" id="ptpTestScheduleID" value="'.$ptpTestScheduleID.'">

                                        <input type="hidden" id="formCategoryId" value="'.$formCategoryId.'">

                                        <input type="hidden" id="testTableId" value="'.$testTableId.'">

                                        <input type="hidden" id="sampleId" value="'.$sampleId.'">

                                        <input type="hidden" id="TestDataStatusID" value="'.$TestStatusID.'">

                                        <input type="text" id="TestDataTestTypeHRDT" class="form-control" name="TestDataTestType" value="'.$currentTestTxt.'"
                                            disabled>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cycle No.</label>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" id="TestDataCycleNo" class="form-control" name="TestDataCycleNo"
                                            disabled value="1">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-5">
                                        <label>Equipment No.</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" id="HRDTTestDataEqipment" class="form-control" name="HRDTTestDataEqipment">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="superscript">Battery temp., ºC <span>1</span></label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTBatTemp" class="form-control" name="HRDTBatTemp">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h6 class="text-center">Pre-Test Measurements</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>OCV, V</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTOCV" class="form-control" name="HRDTOCV"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Midtronics CCA, A</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTCCA" class="form-control" name="HRDTCCA"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>IR, mΩ</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="number" id="HRDTIR" class="form-control" name="HRDTIR"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="row mt">
                                    <div class="col-md-6">
                                        <label>Data File Name</label>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <input type="text" id="HRDTDataFileName" class="form-control" name="HRDTDataFileName"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between mb-1">
                                    <div>
                                        <h6 class="text-center float-start">Discharge Profile</h6>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm float-end" id="ShowProfileBtn" onclick="HRDTDischargeProfileModalBtn('.$ptpTestScheduleID.')" ><i class="fa fa-plus-circle"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th style="white-space:nowrap;">Step</th>
                                                <th style="white-space:nowrap;">Discharge Current, A</th>
                                                <th style="white-space:nowrap;">Time, s</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between mb-1">
                                    <div>
                                        <h6 class="text-center float-start">Test Results</h6>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm float-end" onclick="HRDTTestResultModalBtn('.$ptpTestScheduleID.')"><i class="fa fa-plus-circle" id="HRDTTestResultBtn"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th style="white-space:nowrap;">Time, s</th>
                                                <th style="white-space:nowrap;">Battery Voltage, V</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3 ">
                            <label for="HRDTFormRemarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="HRDTFormRemarks" rows="2">For Review</textarea>
                        </div>

                        <div class="mt-4">
                            <p style=" font-size: 0.9em;font-style: italic;"><span style=" font-size: 0.6em;top:-4px;position:relative;">*</span> The battery temperature should be taken from the electrolyte temperature of the middle cell. If the electrolyte is not accessible, it can be assumed that the battery temperature will be the same as the freezer temperature 24 hrs after placing the battery inside the freezer.</p>
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
                        </div>';

                        $actionBtn = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="button" class="btn btn-primary ml-1" id="SubmitTestForm">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Submit</span>
                                        </button>';
        }

        $arr = array(
            'content' => $output,
            'actionBtn' => $actionBtn
        );

        echo json_encode($arr);
    }
    else if($_POST['action'] == 'HRDTSaveDetails'){
        $formCategoryId = isset($_POST['formCategoryId']) ? $_POST['formCategoryId'] : 0;
        $sampleId = isset($_POST['sampleId']) ? $_POST['sampleId'] : 0;
        $testTableId = isset($_POST['testTableId']) ? $_POST['testTableId'] : 0;
        $ptpTestScheduleId = isset($_POST['ptpTestScheduleId']) ? $_POST['ptpTestScheduleId'] : 0;
        $EquipmentNo = isset($_POST['EquipmentNo']) ? $_POST['EquipmentNo'] : '';
        $BatteryTemp = isset($_POST['BatteryTemp']) ? $_POST['BatteryTemp'] : 0;
        $OCV = isset($_POST['OCV']) ? $_POST['OCV'] : 0;
        $CCA = isset($_POST['CCA']) ? $_POST['CCA'] : 0;
        $IR = isset($_POST['IR']) ? $_POST['IR'] : 0;
        $DataFileName = isset($_POST['DataFileName']) ? $_POST['DataFileName'] : 0;
        $employeeID = $_COOKIE['BTL_employeeID'];
        $output = '';
        $result = 0;

        $sql = "select * from TestDataInput_tbl where TestPTPScheduleID = ".$ptpTestScheduleId." and IsActive = 1 ";
        $execute = odbc_exec($connServer, $sql);
        $counter = 0;
        if($execute){
            $counter = odbc_num_rows($execute);
            if($counter ==0){
                $data = array($formCategoryId, $sampleId, $testTableId, 1, $ptpTestScheduleId);
                $insertTestData = "insert into TestDataInput_tbl (FormCategoryID, SampleID, TestTableID, CycleNo, TestPTPScheduleID) values(?, ?, ?, ?, ?) ";
                $stmtprepareTestData = odbc_prepare($connServer, $insertTestData);
                $testDataExecute = odbc_execute($stmtprepareTestData, $data);
                $TestInputLastID = 0;
                if($testDataExecute){
                    $TestInputLastID = GetLast_Id($connServer);
                    $data2 = array($TestInputLastID, $EquipmentNo, $BatteryTemp, $OCV, $CCA, $IR, $DataFileName);
                    $insertTestDetails = "insert into HRDTestDetails_tbl (TestDataInputID, EquipmentNo, BatteryTemp, OCV, CCA, IR, DataFileName) values(?, ?, ?, ?, ?, ?, ?) ";
                    $stmtprepareTestDetail = odbc_prepare($connServer, $insertTestDetails);
                    $testDetailsExecute = odbc_execute($stmtprepareTestDetail, $data2);

                    if($testDetailsExecute){
                        $statusId = 13; //--On-going Testing
                        $dataStatus = array( $ptpTestScheduleId, $employeeID, $statusId, 'On-going Testing');
                        $insertInTestStatus = "insert into TestDataStatus_tbl (TestPTPScheduleID, EmployeeID, StatusID, Remarks) values(?, ?, ?, ?) ";
                        $statusStmt = odbc_prepare($connServer, $insertInTestStatus);
                        $statusExecute = odbc_execute($statusStmt, $dataStatus);

                        if($statusExecute){
                            $result = 1;
                        }
                        
                    }
                }
                else{
                    $result = 1;
                }
                
            }
            else{
                $rowData = odbc_fetch_array($execute);
                $TestDataInputID = $rowData['TestDataInputID'];

                $selectTestDetails = "select * from HRDTestDetails_tbl where TestDataInputID = ".$TestDataInputID." and IsActive = 1 ";
                $execute = odbc_exec($connServer, $selectTestDetails);
                if($execute){
                    $countrow = odbc_num_rows($execute);
                    if($countrow==0){
                        $data2 = array($TestDataInputID, $EquipmentNo, $BatteryTemp, $OCV, $CCA, $IR, $DataFileName);
                        $insertTestDetails = "insert into HRDTestDetails_tbl (TestDataInputID, EquipmentNo, BatteryTemp, OCV, CCA, IR, DataFileName) values(?, ?, ?, ?, ?, ?, ?) ";
                        $stmtprepareTestDetail = odbc_prepare($connServer, $insertTestDetails);
                        $testDetailsExecute = odbc_execute($stmtprepareTestDetail, $data2);

                        if($testDetailsExecute){
                            $statusId = 13; //--On-going Testing
                            $dataStatus = array( $TestDataInputID, $employeeID, $statusId, 'On-going Testing');
                            $insertInTestStatus = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) values(?, ?, ?, ?) ";
                            $statusStmt = odbc_prepare($connServer, $insertInTestStatus);
                            $statusExecute = odbc_execute($statusStmt, $dataStatus);

                            if($statusExecute){
                                $result = 1;
                            }
                            
                        }
                    }
                    else{
                        $result = 1;
                    }
                }
                
                
            }
        }
        else{
            $result = 1;
        }

        $arr = array(
            'result' => $result
        );
        echo json_encode($arr);
    }
    else if($_POST['action'] == 'ShowHRDTDischargeProfileModal'){
        $TestDataInputID = isset($_POST['TestDataInputID']) ? $_POST['TestDataInputID'] : 0;
        $result = 0;
        $sql = "select * from TestDataInput_tbl where TestDataInputID = ".$TestDataInputID." and IsActive = 1 and IsDeleted = 0 ";
        $execute = odbc_exec($connServer, $sql);
        $count = 0;
        if($execute){
            $count = odbc_num_rows($execute);
            if($count !=0){
                $result = 1;
            }
        }
        echo json_encode($result);
    }
    else if($_POST['action'] == 'ShowHRDTTestResultModal'){
        $TestDataInputID = isset($_POST['TestDataInputID']) ? $_POST['TestDataInputID'] : 0;
        $result = 0;
        $sql = "select * from TestDataInput_tbl where TestDataInputID = ".$TestDataInputID." and IsActive = 1 and IsDeleted = 0 ";
        $execute = odbc_exec($connServer, $sql);
        $count = 0;
        if($execute){
            $count = odbc_num_rows($execute);
            if($count !=0){
                $result = 1;
            }
        }
        echo json_encode($result);
    }
    else if($_POST['action'] == 'DischargeProfileData'){
        $TestPtpScheduleId = isset($_POST['TestPtpScheduleId']) ? $_POST['TestPtpScheduleId'] : 0;
        $DischargeCurrent = isset($_POST['DischargeCurrent']) ? $_POST['DischargeCurrent'] : 0;
        $seconds = isset($_POST['seconds']) ? $_POST['seconds'] : 0;
        $result = 0;

        $sql = "select * from TestDataInput_tbl where TestPTPScheduleID = ".$TestPtpScheduleId." and IsActive = 1 ";
        $execute = odbc_exec($connServer, $sql);
        if($execute){
            $rowData = odbc_fetch_array($execute);
            $testDataInputID = $rowData['TestDataInputID'];

            $data = array($testDataInputID, $DischargeCurrent,  $seconds);
            $sql = "insert into HRDTDischargeProfile_tbl (TestDataInputID, DischargeA, ss_value) values(?, ?, ?) ";
            $stmt = odbc_prepare($connServer, $sql);
            $execute = odbc_execute($stmt, $data);

            if($execute){
                $result = 1;
            }
            else{
                $result = 0;
            }
        }
        echo json_encode($result);
    }
    else if($_POST['action'] == 'HRDTDichargeProfileTbl'){
        $TestPTPScheduleId = isset($_POST['TestPTPScheduleId']) ? $_POST['TestPTPScheduleId'] : 0;
        $StatusID = isset($_POST['StatusID']) ? $_POST['StatusID'] : 0;
        $output = '';
        $sql = "select * from TestDataInput_tbl where TestPTPScheduleID = ".$TestPTPScheduleId." and IsActive = 1 ";
        $execute = odbc_exec($connServer, $sql);
        if($execute){
            $rowData = odbc_fetch_array($execute);
            $testDataInputID = $rowData['TestDataInputID'];
            $query = "select HRDTDischargeProfileID, TestDataInputID, DischargeA, ss_value, DateCreated
            from HRDTDischargeProfile_tbl
            where TestDataInputID = ".$testDataInputID ." and IsActive = 1 and IsDeleted = 0 ORDER BY DateCreated ASC  ";
            
            $result = odbc_exec($connServer, $query);

            $n = 1;
            while($row = odbc_fetch_array($result)){
                $DischargeProfileID = $row['HRDTDischargeProfileID'];
                $TestDataInputID = $testDataInputID;
                $DischargeA = $row['DischargeA'];
                $ComputedTimeInMins = number_format($row['ss_value']);

                $output .= '
                    <tr>
                        <td>'.$n.'</td>
                        <td>'.$DischargeA.'</td>
                        <td>'.$ComputedTimeInMins.' s</td>';
                        if($StatusID==14){
                            $output .= ' <td><i class="fa fa-pen" onclick="DischargeProfileEdit('.$DischargeProfileID.', '.$TestDataInputID.')"></i></td>';
                        }
                $output .= ' </tr>
                ';

                $n++;
            }
        }
        

        echo json_encode($output);
    }
    else if($_POST['action'] == 'TestResultData'){
        $TestPTPScheduleId = isset($_POST['TestPTPScheduleId']) ? $_POST['TestPTPScheduleId'] : 0;
        $Voltage = isset($_POST['Voltage']) ? $_POST['Voltage'] : 0;
        $Seconds = isset($_POST['Seconds']) ? $_POST['Seconds'] : 0;
        $result = 0;

        $sql = "select * from TestDataInput_tbl where TestPTPScheduleID = ".$TestPTPScheduleId." and IsActive = 1 ";
        $execute = odbc_exec($connServer, $sql);
        if($execute){
            $rowData = odbc_fetch_array($execute);
            $testDataInputID = $rowData['TestDataInputID'];

            $data = array($testDataInputID, $Voltage, $Seconds);
            $sql = "insert into HRDTTestResult_tbl (TestDataInputID, BattVoltage, ss_value) values(?, ?, ?) ";
            $stmt = odbc_prepare($connServer, $sql);
            $execute = odbc_execute($stmt, $data);

            if($execute){
                $result = 1;
            }
            else{
                $result = 0;
            }
        }
        echo json_encode($result);
    }
    else if($_POST['action'] == 'HRDTTestResultTbl'){
        $TestPTPScheduleId = isset($_POST['TestPTPScheduleId']) ? $_POST['TestPTPScheduleId'] : 0;
        $StatusID = isset($_POST['StatusID']) ? $_POST['StatusID'] : 0;
        $output = '';
        $sql = "select * from TestDataInput_tbl where TestPTPScheduleID = ".$TestPTPScheduleId." and IsActive = 1 ";
        $execute = odbc_exec($connServer, $sql);
        if($execute){
            $rowData = odbc_fetch_array($execute);
            $testDataInputID = $rowData['TestDataInputID'];
            $query = "select HRDTTestResultID, TestDataInputID, BattVoltage, ss_value, DateCreated
            from HRDTTestResult_tbl
            where TestDataInputID = ".$testDataInputID ." and IsActive = 1 and IsDeleted = 0 ORDER BY DateCreated ASC  ";
            
            $result = odbc_exec($connServer, $query);

            $n = 1;
            while($row = odbc_fetch_array($result)){
                $TestResultID = $row['HRDTTestResultID'];
                $TestDataInputID = $testDataInputID;
                $BattVoltage = $row['BattVoltage'];
                $ComputedTimeInMins = number_format($row['ss_value']);

                $output .= '
                    <tr>
                        <td>'.$n.'</td>
                        <td>'.$BattVoltage.'</td>';
                        if($StatusID==14){
                            $output .= ' <td><i class="fa fa-pen" onclick="TestResultEdit('.$TestResultID.', '.$TestDataInputID.')"></i></td>';
                        }
                $output .= '</tr>
                ';

                $n++;
            }
        }
        

        echo json_encode($output);
    }
    else if($_POST['action'] == 'SubmitHRDTData'){
        $TestDataInputId = isset($_POST['TestDataInputId']) ? $_POST['TestDataInputId'] : 0;
        $TestPTPScheduleID = isset($_POST['TestPTPScheduleID']) ? $_POST['TestPTPScheduleID'] : 0;
        $HRDTRemarks = isset($_POST['HRDTRemarks']) ? $_POST['HRDTRemarks'] : '';
        if($HRDTRemarks=='' || $HRDTRemarks==null){
            $HRDTRemarks = 'For Review';
        }
        $employeeID = $_COOKIE['BTL_employeeID'];
        $result = 0;
        $statusID =11; //For Approval
        $dataAddstatus = array($TestDataInputId, $employeeID, $statusID, $HRDTRemarks);
        $insertAddstatus = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) values (?, ?, ?, ?) ";

        $stmt_addStatus = odbc_prepare($connServer, $insertAddstatus);
        $addStatusExecute = odbc_execute($stmt_addStatus, $dataAddstatus);
        if($addStatusExecute){
            $result = 1;
        }

        echo json_encode($result);
    }
    else if($_POST['action'] == 'submitReviewedHRDTform'){
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $HRDTRemarks = isset($_POST['HRDTRemarks']) ? $_POST['HRDTRemarks'] : '';
        if($HRDTRemarks=='' || $HRDTRemarks==null){
            $HRDTRemarks = 'For Approval';
        }
        $employeeID = $_COOKIE['BTL_employeeID'];
        $statusID = 11; // status: for approval
        $result = 0;
        $data = array($testDataInputId, $employeeID, $statusID, $HRDTRemarks);
        $sql = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) ";
        $sql .= "values (?, ?, ?, ?) ";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $data);

        if($execute){
            $result = 1;
        }

        echo json_encode($result);
    }
    else if($_POST['action'] == 'submitRetestStatHRDTform'){
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $HRDTRemarks = isset($_POST['HRDTRemarks']) ? $_POST['HRDTRemarks'] : '';
        $HRDTestDetailId = isset($_POST['HRDTestDetailId']) ? $_POST['HRDTestDetailId'] : 0;
        $ptpTestScheduleID = isset($_POST['ptpTestScheduleID']) ? $_POST['ptpTestScheduleID'] : 0;

        if($HRDTRemarks=='' || $HRDTRemarks==null){
            $HRDTRemarks = 'For Review';
        }

        $employeeID = $_COOKIE['BTL_employeeID'];
        $statusID = 9; // status: for approval
        $result = 0;
        $data = array($ptpTestScheduleID, $employeeID, $statusID, $HRDTRemarks);
        $sql = "insert into TestDataStatus_tbl (TestPTPScheduleID, EmployeeID, StatusID, Remarks) ";
        $sql .= "values (?, ?, ?, ?) ";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $data);

        if($execute){
            $result = 1;
        }
        
        echo json_encode($result);
    }
    else if($_POST['action'] == 'submitDataChangedHRDTform'){
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $HRDTRemarks = isset($_POST['HRDTRemarks']) ? $_POST['HRDTRemarks'] : '';
        $ptpScheduleid = isset($_POST['ptpScheduleid']) ? $_POST['ptpScheduleid'] : 0;

        if($HRDTRemarks=='' || $HRDTRemarks==null){
            $HRDTRemarks = 'For Approval';
        }

        //User Input
        $EquipmentNo = isset($_POST['EquipmentNos']) ? $_POST['EquipmentNos'] : 0;
        $BatteryTemp = isset($_POST['BatteryTemp']) ? $_POST['BatteryTemp'] : 0;
        $OCV = isset($_POST['OCV']) ? $_POST['OCV'] : 0;
        $CCA = isset($_POST['CCA']) ? $_POST['CCA'] : 0;
        $IR = isset($_POST['IR']) ? $_POST['IR'] : 0;
        $DataFileName = isset($_POST['DataFileName']) ? $_POST['DataFileName'] : 0;

        $userInput = array(
            'Equipment No' => $EquipmentNo,
            'Battery Temperature' => $BatteryTemp,
            'OCV' => $OCV,
            'CCA' => $CCA,
            'IR' => $IR,
            'Data FileName' => $DataFileName
        );
        //User Input End

        //Current Data
        $HRDTestDetailID = 0;
        $currentData = array();
        $sql = "select top 1 * from HRDTestDetails_tbl where TestDataInputID = ".$testDataInputId." and IsActive = 1 order by DateCreated DESC ";
        $execute = odbc_exec($connServer, $sql);
        if($execute){
            $rowData = odbc_fetch_array($execute);
            $HRDTestDetailID = $rowData['HRDTestDetailID'];
            $currentData[] = $rowData['EquipmentNo'];
            $currentData[] = $rowData['BatteryTemp'];
            $currentData[] = $rowData['OCV'];
            $currentData[] = $rowData['CCA'];
            $currentData[] = $rowData['IR'];
            $currentData[] = $rowData['DataFileName'];

            $updateData = array($EquipmentNo, $BatteryTemp, $OCV, $CCA, $IR, $DataFileName, $HRDTestDetailID);
            $update = "update HRDTestDetails_tbl set EquipmentNo = ?, BatteryTemp = ?, OCV = ?, CCA = ?, IR = ?, DataFileName = ? where HRDTestDetailID = ? ";
            $updateStmt = odbc_prepare($connServer, $update);
            $executeUpdate = odbc_execute($updateStmt, $updateData);
        }
        //Current Data End
        $DataChangeLog = array();

        $employeeID = $_COOKIE['BTL_employeeID'];
        $statusID = 9; // status: for change data
        $result = 0;

        $countUserInput = count($userInput);
        $countCurrentData = count($currentData);

        $keys = array_keys($userInput);
        $numKeys = count($keys);

        for ($i = 0; $i < $numKeys; $i++) {
            $key = $keys[$i];
            $UserInputValue = $userInput[$key];
            $currentDataValue = $currentData[$i];
            // $result .=$key. ": ".$UserInputValue. " | ".$currentDataValue."</br>";

            if($UserInputValue != $currentDataValue){
                $upatedData = array(
                    'Field' => $key,
                    'OldData' => $currentDataValue,
                    'NewData' => $UserInputValue
                );
                array_push($DataChangeLog, $upatedData);
            }
        }

        foreach ($DataChangeLog as $change) {
            // echo "Field: " . $change['Field'] . "<br>";
            // echo "Old Data: " . $change['OldData'] . "<br>";
            // echo "New Data: " . $change['NewData'] . "<br><br>";
            // echo $change['Field']. " Field is change from ".$change['OldData']." to ".$change['NewData'];
            $field =  $change['Field'];
            $oldData = $change['OldData'];
            $newData = $change['NewData'];
            $TransactionRemarks = $change['Field']." Field is change from ".$change['OldData']." to ".$change['NewData'];

            $dataInsert = array($HRDTestDetailID, $ptpScheduleid, $employeeID, $field, $oldData, $newData, $TransactionRemarks);
            $insertLogs = "insert into ChangeDataTransactionLogs_tbl (TestReferenceID, TestPTPScheduleID, EmployeeID, FieldName, OldData, NewData, TransactionRemarks) values (?, ?, ?, ?, ?, ?, ?) ";
            $stmt = odbc_prepare($connServer, $insertLogs);
            $execute = odbc_execute($stmt, $dataInsert);
        }

        $statusID = 11; //For Approval
        $dataAddstatus = array($testDataInputId, $employeeID, $statusID, $HRDTRemarks);
        $insertAddstatus = "insert into TestDataStatus_tbl (TestDataInputID, EmployeeID, StatusID, Remarks) values (?, ?, ?, ?) ";
        $stmt_addStatus = odbc_prepare($connServer, $insertAddstatus);
        $addStatusExecute = odbc_execute($stmt_addStatus, $dataAddstatus);
        if($addStatusExecute){
            $result = 1;
        }

        echo json_encode($result);
    }
    else if($_POST['action'] == 'submitApprovedHRDTform'){
        $testDataInputId = isset($_POST['testDataInputId']) ? $_POST['testDataInputId'] : 0;
        $HRDTRemarks = isset($_POST['HRDTRemarks']) ? $_POST['HRDTRemarks'] : '';
        $ptpScheduleid = isset($_POST['ptpScheduleid']) ? $_POST['ptpScheduleid'] : 0;
        if($HRDTRemarks=='' || $HRDTRemarks==null){
            $HRDTRemarks = 'Test Approved';
        }
        $employeeID = $_COOKIE['BTL_employeeID'];
        $statusID = 12; // status: for approval
        $result = 0;
        $data = array($ptpScheduleidd, $employeeID, $statusID, $HRDTRemarks);
        $sql = "insert into TestDataStatus_tbl (TestPTPScheduleID, EmployeeID, StatusID, Remarks) ";
        $sql .= "values (?, ?, ?, ?) ";
        $stmt = odbc_prepare($connServer, $sql);
        $execute = odbc_execute($stmt, $data);

        if($execute){
            $result = 1;
            $updateSql = "update TestPTPSchedule_tbl set TestStatus = 1, DateModified = getdate() where TestPTPScheduleID = ".$ptpScheduleid;
            $updateExecute = odbc_exec($connServer, $updateSql);
            if($updateSql){
                $result = 1;
            }
            else{
                $result = 0;
            }
        }

        echo json_encode($result);
    }

    //Edit DCH Profile blocks
    else if($_POST['action'] == 'FetchDCHProfileEdit'){
        $DCHProfileID = isset($_POST['DCHProfileID']) ? $_POST['DCHProfileID']:0;
        $DCHVal = 0;
        $ss_value = 0;
        $sql = "select DischargeA, ss_value from HRDTDischargeProfile_tbl where HRDTDischargeProfileID = ".$DCHProfileID." and IsActive = 1 and IsDeleted = 0";
        $execute = odbc_exec($connServer, $sql);
        if($execute){
            $rowData = odbc_fetch_array($execute);
            $DCHVal = $rowData['DischargeA'];
            $ss_value = $rowData['ss_value'];
        }

        $arr = array(
            'DCHVal' => $DCHVal,
            'seconds' => $ss_value
        );

        echo json_encode($arr);
    }
    //Edit DCH Profile blocks end

    //Edit Test Result Block
    else if($_POST['action'] == 'FetchTestResultEdit'){
        $TestResultID = isset($_POST['TestResultID']) ? $_POST['TestResultID']:0;
        $DCHVal = 0;
        $ss_value = 0;
        $sql = "select BattVoltage, ss_value from HRDTTestResult_tbl where HRDTTestResultID = ".$TestResultID." and IsActive = 1 and IsDeleted = 0";
        $execute = odbc_exec($connServer, $sql);
        if($execute){
            $rowData = odbc_fetch_array($execute);
            $Voltage = $rowData['BattVoltage'];
            $ss_value = $rowData['ss_value'];
        }

        $arr = array(
            'Voltage' => $Voltage,
            'seconds' => $ss_value
        );

        echo json_encode($arr);
    }
    //Edit Test Result Block end

    //-------------HRDT Test Form End-------------
}
?>
