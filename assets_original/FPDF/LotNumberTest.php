<?php
require('class_list.php');

date_default_timezone_set("Asia/Manila");
$date = date('jS M Y');

$cat_id = "";
if(isset($_POST['lot_id'])){
    $cat_id = $_POST['lot_id'];
}

// Instanciation of inherited class
// $pdf = new PDF(); -----------HxW-----
$pdf = new FPDF('L','mm',array(112,152));
$pdf->AliasNbPages();
$pdf->AddPage();

//------------------------------------//
// $pdf->SetXY(1,5);
// $pdf->Cell(133,0,'',1,0);
$pdf->Ln(1);
// $pdf->Cell(20);
$pdf->SetFont('Helvetica','B',20);
$pdf->SetXY(0,5);
$pdf->Cell(40,7,'',0,0, 'L');
$pdf->Cell(72,7,'PRODUCTION TAG',0,0, 'C');
$pdf->Cell(40,7,'',0,1, 'L');


$pdf->SetFont('Helvetica','',11);
$pdf->SetXY(0,12);
$pdf->Cell(40,7,'',0,0, 'L');
$pdf->Cell(72,7,'Pasting & Plate Curing',0,0, 'C');
$pdf->Cell(40,7,'',0,1, 'L');

$query = "SELECT QRDataUrl FROM sheetdetailqr ";
$query .= "WHERE SheetDetailID = (SELECT SheetDetailID FROM sheetdetail_tbl WHERE LotNumber = '".$cat_id."') ";
$result = mysqli_query($con, $query);
confirmQuery($result);
$rowImage = mysqli_fetch_array($result);


$pdf->Image("../../QRuploads/".$rowImage['QRDataUrl'], 117, 1, 30);

$pdf->SetFont('Helvetica','B',10);
$pdf->SetXY(0,30);
$pdf->Cell(40,7,'',0,0, 'L');
$pdf->Cell(72,7,'',0,0, 'C');
$pdf->Cell(40,7,$cat_id,0,1, 'C');


$queryDetail = "SELECT p.PlateType, sd.Quantity, l.Line, sd.RackNo, sd.DateCreated, cb.CurringBooth, sd.Shift, sd.BatchNo, paster.LastName as 'PasterLname', paster.FirstName as 'PasterFname', stacker.LastName as 'stackerLname', stacker.FirstName as 'stackerFname', sd.MoiseContent, s.SheetNo ";
$queryDetail .= "FROM sheetdetail_tbl sd ";
$queryDetail .= "JOIN line_tbl l ON sd.LineID = l.LineID ";
$queryDetail .= "JOIN platetype_tbl p ON sd.PlateTypeID = p.PlateTypeID ";
$queryDetail .= "JOIN curringbooth_tbl cb ON sd.CurringBoothID = cb.CurringBoothID ";
$queryDetail .= "JOIN employee_tbl paster ON sd.PasterID = paster.EmployeeID ";
$queryDetail .= "JOIN employee_tbl stacker ON sd.StackerID = stacker.EmployeeID ";
$queryDetail .= "JOIN sheet_tbl s ON sd.SheetID = s.SheetID ";
$queryDetail .= "WHERE sd.LotNumber = '".$cat_id."' ";

$resultDetail = mysqli_query($con, $queryDetail);

$rowDetail = mysqli_fetch_array($resultDetail);

$pdf->SetXY(5,42);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Plate Type:',0,0, 'L');

$pdf->SetFont('Helvetica','B',20);
$pdf->Cell(50,7,$rowDetail['PlateType'],0,0, 'L');

$pdf->Cell(12,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(15,7,'Qty Produced:',0,0, 'L');

$pdf->SetFont('Helvetica','B',20);
$pdf->Cell(30,7,number_format($rowDetail['Quantity']),0,0, 'R');
$pdf->SetFont('Helvetica','',10);
$pdf->Cell(10,7,'pcs',0,1, 'R');

$pdf->SetXY(5,52);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Line:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(50,7,$rowDetail['Line'],0,0, 'L');

$pdf->Cell(12,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(25,7,'Rack No.:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(40,7,$rowDetail['RackNo'],0,1, 'L');

$pdf->SetXY(5,60);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Date:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(50,7,date('d-M-y', strtotime($rowDetail['DateCreated'])),0,0, 'L');

$pdf->Cell(12,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(25,7,'Curing Booth:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(40,7,$rowDetail['CurringBooth'],0,1, 'L');

$pdf->SetXY(5,68);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Shift:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(50,7,$rowDetail['Shift'],0,0, 'L');

$pdf->Cell(12,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(25,7,'Batch No.:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(40,7,$rowDetail['BatchNo'],0,1, 'L');

$pdf->SetXY(5,76);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Paster:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(50,7,$rowDetail['PasterLname'].', '.$rowDetail['PasterFname'],0,0, 'L');

$pdf->Cell(12,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(25,7,'Moisture Content:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(20,7,$rowDetail['MoiseContent'].'%',0,1, 'R');

$pdf->SetXY(5,84);

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(20,7,'Stacker:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(50,7,$rowDetail['stackerLname'].', '.$rowDetail['stackerFname'],0,0, 'L');

$pdf->Cell(12,7,'',0,0, 'L');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(25,7,'Sheet No.:',0,0, 'L');

$pdf->SetFont('Helvetica','U',13);
$pdf->Cell(40,7,$rowDetail['SheetNo'],0,1, 'L');



$file = "../../system_file/".$cat_id."print.pdf";
$pdf->Output('F', $file);

echo json_encode($cat_id);
?>