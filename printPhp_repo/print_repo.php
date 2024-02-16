<?php
    include "../Database/db_connection.php";
    include "../Php_function/functions.php";
    $action = isset($_POST['action']) ? $_POST['action'] : 0;
    if($action=='fetchAvailPrint'){
        $CircuitLocation  = '';
        $output = '';

        $sql = "select samples.TestSamplesID, samples.TestSamplesNo, testTbl.Test, testTbl.IsSeries, requi.RequisitionID, requi.ProjectName, ISNULL(allocation.WaterBathCellNoID, 0) as CellNo
        from TestSamples_tbl samples
        join TestPlanDetails_tbl testDetails ON samples.TestPlanDetailsID = testDetails.TestPlanDetailsID
        join TestTable_tbl testTbl ON testDetails.TestTableID = testTbl.TestTableID
        join TestPlan_tbl testPlan ON testDetails.TestPlanID = testPlan.TestPlanID
        join Request_tbl request ON testPlan.RequestID = request.RequestID
        join Requisition_tbl requi ON request.RequisitionID = requi.RequisitionID
        left join SamplesWaterBathAllocation_tbl allocation ON samples.TestSamplesID = allocation.TestSampleID and allocation.IsTransfer = 0
        where samples.IsPrint = 1 ";
        $result = odbc_exec($connServer, $sql);
        while($row = odbc_fetch_array($result)){
            $TestSamplesID = $row['TestSamplesID'];
            $SampleID = $row['TestSamplesNo'];
            $SampleImgaUrl = 'SamplesQRuploads/'.$SampleID.'.png';
            $SampleIsSeries = $row['IsSeries'];
            $requisitionID = $row['RequisitionID'];
            $ProjectName = $row['ProjectName'];
            $CellNoID = $row['CellNo'];
            $CircuitLocation = '';
            $test = '';
            if($SampleIsSeries==1){
                $test = $row['Test'];
            }
            else{
                $test = 'Special Test';
            }

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

            $requestorQuery = "select emp.Fname, emp.Lname from Requestor_tbl requestor
            join Requisition_tbl requi ON requestor.RequisitionID = requi.RequisitionID
            join Employee_tbl emp ON requestor.EmployeeID = emp.EmployeeID
            where requestor.IsActive = 1 and requestor.IsDeleted = 0 and requi.RequisitionID = ".$requisitionID." ";
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

            $output .='<div class="col-lg-4 mt-4">
                            <div class="container rounded-rectangle">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <img src="'.$SampleImgaUrl.'" alt="" class="img-fluid position-relative" style="border: 1px solid #000000;padding:5px;border-radius: 5px;top:12px;">
                                    </div>
                                    <div class="col-lg-8">
                                        <h3 class="position-relative" style="top:7px;">'.$SampleID.'</h3>
                                        <span class="float-start" style="font-size:14px;">'. $ProjectName.'</span></br>
                                        <span class="float-start" style="font-size:14px;">Requestor: '.$requestorInitials.'</span></br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="">'. $test.'</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="">'.$CircuitLocation.'</h6>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="container text-center mt-2">
                                <div class="button-group">
                                    <button type="button" class="btn btn-light-secondary btn-sm"><i class="bi bi-trash" onclick="DeleteSamplesBtn('.$TestSamplesID.')"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="PrintSamplesBtn('.$TestSamplesID.')"><i class="bi bi-printer-fill"></i></button>
                                </div>
                            </div>
                        </div>';
        }
        

        echo json_encode($output);
    }else if($action=='fetchAvailRePrint'){
        $CircuitLocation  = '';
        $output = '';

        $sql = "select samples.TestSamplesID, samples.TestSamplesNo, testTbl.Test, testTbl.IsSeries, requi.RequisitionID, requi.ProjectName, ISNULL(allocation.WaterBathCellNoID, 0) as CellNo
        from TestSamples_tbl samples
        join TestPlanDetails_tbl testDetails ON samples.TestPlanDetailsID = testDetails.TestPlanDetailsID
        join TestTable_tbl testTbl ON testDetails.TestTableID = testTbl.TestTableID
        join TestPlan_tbl testPlan ON testDetails.TestPlanID = testPlan.TestPlanID
        join Request_tbl request ON testPlan.RequestID = request.RequestID
        join Requisition_tbl requi ON request.RequisitionID = requi.RequisitionID
        left join SamplesWaterBathAllocation_tbl allocation ON samples.TestSamplesID = allocation.TestSampleID and allocation.IsTransfer = 0
        where samples.IsReprint = 1 ";
        $result = odbc_exec($connServer, $sql);
        while($row = odbc_fetch_array($result)){
            $TestSamplesID = $row['TestSamplesID'];
            $SampleID = $row['TestSamplesNo'];
            $SampleImgaUrl = 'SamplesQRuploads/'.$SampleID.'.png';
            $SampleIsSeries = $row['IsSeries'];
            $requisitionID = $row['RequisitionID'];
            $ProjectName = $row['ProjectName'];
            $CellNoID = $row['CellNo'];
            $CircuitLocation = '';
            $test = '';
            if($SampleIsSeries==1){
                $test = $row['Test'];
            }
            else{
                $test = 'Special Test';
            }

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

            $requestorQuery = "select emp.Fname, emp.Lname from Requestor_tbl requestor
            join Requisition_tbl requi ON requestor.RequisitionID = requi.RequisitionID
            join Employee_tbl emp ON requestor.EmployeeID = emp.EmployeeID
            where requestor.IsActive = 1 and requestor.IsDeleted = 0 and requi.RequisitionID = ".$requisitionID." ";
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

            $output .='<div class="col-lg-4 mt-4">
                            <div class="container rounded-rectangle">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <img src="'.$SampleImgaUrl.'" alt="" class="img-fluid position-relative" style="border: 1px solid #000000;padding:5px;border-radius: 5px;top:12px;">
                                    </div>
                                    <div class="col-lg-8">
                                        <h3 class="position-relative" style="top:7px;">'.$SampleID.'</h3>
                                        <span class="float-start" style="font-size:14px;">'. $ProjectName.'</span></br>
                                        <span class="float-start" style="font-size:14px;">Requestor: '.$requestorInitials.'</span></br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="">'. $test.'</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="">'.$CircuitLocation.'</h6>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="container text-center mt-2">
                                <div class="button-group">
                                    <button type="button" class="btn btn-light-secondary btn-sm"><i class="bi bi-trash" onclick="DeleteSamplesBtn('.$TestSamplesID.')"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="PrintSamplesBtn('.$TestSamplesID.')"><i class="bi bi-printer-fill"></i></button>
                                </div>
                            </div>
                        </div>';
        }
        echo json_encode($output);
    }

?>