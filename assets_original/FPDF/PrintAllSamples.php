<?php
    require('class_list.php');
    // Instanciation of inherited class
    // $pdf = new PDF(); -----------HxW-----
    $pdf = new FPDF();
    $CircuitLocation  = '';

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

        $pdf->AddPage('L', array(20, 64)); // 'L' for landscape orientation, specify width and height in millimeters

        // Set margins if needed
        $pdf->SetMargins(0, 0, 0);

        // Set auto page break to avoid overflow
        $pdf->SetAutoPageBreak(false);
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->SetXY(0,0);
        $pdf->Image("../../".$SampleImgaUrl, 1, 1.5, 15);
        $pdf->Cell(18, 7, '', 0, 0, 'C');
        $pdf->Cell(42, 7, $SampleID, 0, 0, 'L');
        $pdf->Cell(4, 7, '', 0, 0, 'C');

        $pdf->SetXY(0,6);
        $pdf->SetFont('Helvetica','',7);
        $pdf->Cell(18, 4, '', 0, 0, 'C');
        $pdf->Cell(44, 4, $ProjectName, 0, 0, 'L');
        $pdf->Cell(2, 4, '', 0, 0, 'C');

        $pdf->SetXY(0,10);
        $pdf->SetFont('Helvetica','',7);
        $pdf->Cell(18, 4, '', 0, 0, 'C');
        $pdf->Cell(44, 4, 'Requestor: '.$requestorInitials, 0, 0, 'L');
        $pdf->Cell(2, 4, '', 0, 0, 'C');

        $pdf->SetXY(0,14);
        $pdf->SetFont('Helvetica','B',7);
        $pdf->Cell(18, 4, '', 0, 0, 'C');
        $pdf->Cell(22, 4,$test , 0, 0, 'L');
        $pdf->Cell(22, 4,$CircuitLocation, 0, 0, 'L');
        $pdf->Cell(2, 4, '', 0, 0, 'C');

        $updateAfterPrint = "update TestSamples_tbl set IsPrint = 0 where TestSamplesID = ".$TestSamplesID." ";
        $updateExecute = odbc_exec($connServer, $updateAfterPrint);
    }
    
    // $pdf->AddPage('L', array(20, 67));
    $FileName = "AllSamples";
    $file = "../../SamplesFileSystem/".$FileName.".pdf";
    $pdf->Output('F', $file);

    echo json_encode($FileName);
?>