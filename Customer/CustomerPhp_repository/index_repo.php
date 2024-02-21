<?php
    include "../../Database/db_connection.php";
    include "../../Php_function/functions.php";
    require "../../classes/emailSender.php";

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

        else if($_POST['action']=='disposalData'){
            $query = "SELECT * ";
            $query .="FROM Disposal_tbl ";
            $query .="WHERE IsActive = 1 and IsDeleted = 0 ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){

                $output ='';

                $output .='<option selected disabled value="0">Choose..</option>';

                while($row = odbc_fetch_array($fetch)){

                    $disposal_Id     = $row['DisposalID'];
                    $disposal_txt   = $row['ProcedureTxt'];

                    $output .='<option value="'.$disposal_Id.'">'.$disposal_txt.'</option>';
                }

                echo json_encode($output);
            }

        }

        else if($_POST['action']=='requestorData'){
            $deptID = $_COOKIE['BTL_DepartmentID'];
            $sectionID = $_COOKIE['BTL_SectionID'];
            
            $query = "SELECT emp.EmployeeID, concat(emp.Fname,' ',emp.Lname) as 'Employee Name' ";
            $query .="FROM EmployeeInfo_tbl empInfo ";
            $query .="join Employee_tbl emp ON empInfo.EmployeeID = emp.EmployeeID ";
            $query .="join Section_tbl section ON empInfo.SectionID = section.SectionID ";
            $query .="join Department_tbl dept ON empInfo.DepartmentID = dept.DepartmentID ";
            $query .="WHERE dept.DepartmentID = ".$deptID." and section.SectionID = ".$sectionID." ";
            $query .="and dept.IsActive = 1 and dept.IsDeleted = 0 ";
            $query .="and emp.IsActive = 1 and emp.IsDeleted = 0 ";
            $query .="and section.IsActive = 1 and section.IsDeleted = 0 ";
            $query .="and empInfo.IsActive = 1 and empInfo.IsDeleted = 0 ";
            // $query .="and emp.EmployeeID != ".$_COOKIE['BTL_employeeID']." ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){

                $output ='';

                $output .='<optgroup label="'.$_COOKIE['BTL_Section'].'" style="background-color:#efefef;">';

                while($row = odbc_fetch_array($fetch)){

                    $Employee_Id     = $row['EmployeeID'];
                    $EmployeeName   = $row['Employee Name'];

                    $output .='<option value="'.$Employee_Id.'">'.$EmployeeName.'</option>';
                }

                $output .='</optgroup>';

                echo json_encode($output);
            }

        }

        else if($_POST['action']=='classificationData'){
            
            $query = "SELECT * ";
            $query .="FROM Classification_tbl ";
            $query .="WHERE IsActive = 1 and IsDeleted = 0 ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){

                $output ='';

                $output .='<option selected disabled value="0">Choose..</option>';

                while($row = odbc_fetch_array($fetch)){

                    $classificationID     = $row['ClassificationID'];
                    $classification_txt   = $row['ClassificationTxt'];

                    $output .='<option value="'.$classificationID.'">'.$classification_txt.'</option>';
                }
                echo json_encode($output);
            }

        }

        else if($_POST['action']=='fetchPlateType'){
            $output1 ='';
            $output2 ='';

            $query = "SELECT * ";
            $query .="FROM PlateType_tbl ";
            $query .="WHERE IsActive = 1 and IsDeleted = 0 and PolarityID = 1 ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){
                $output1 .='<option selected disabled value="0">Choose..</option>';
                $output1 .='<option value="Others">Others</option>';
                $output1 .='<option value="N/A">No Available Code</option>';
                while($row = odbc_fetch_array($fetch)){

                    $plateTypeID     = $row['PlateTypeId'];
                    $plateType   = $row['PlateType'];

                    $output1 .='<option value="'.$plateTypeID.'">'.$plateType.'</option>';
                }
                
            }

            $query1 = "SELECT * ";
            $query1 .="FROM PlateType_tbl ";
            $query1 .="WHERE IsActive = 1 and IsDeleted = 0 and PolarityID = 2 ";

            $fetch1 = odbc_exec($connServer, $query1);
            $count1 = odbc_num_rows($fetch1) & 0xffffffff;

            if($fetch1){
                $output2 .='<option selected disabled value="0">Choose..</option>';
                $output2 .='<option value="Others">Others</option>';
                $output2 .='<option value="N/A">No Available Code</option>';
                while($row1 = odbc_fetch_array($fetch1)){

                    $plateTypeID     = $row1['PlateTypeId'];
                    $plateType   = $row1['PlateType'];

                    $output2 .='<option value="'.$plateTypeID.'">'.$plateType.'</option>';
                }
                
            }

            $arr = array(
                'PostivePlateData' => $output2,
                'NegativePlateData' => $output1
            );
            echo json_encode($arr);
        }

        else if($_POST['action']=='batterytypeData'){
            
            $query = "SELECT * ";
            $query .="FROM BatteryType_tbl ";
            $query .="WHERE IsActive = 1 and IsDeleted = 0 ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){

                $output ='';

                $output .='<option selected disabled value="0">Choose..</option>';

                while($row = odbc_fetch_array($fetch)){

                    $BatteryTypeID     = $row['BatteryTypeID'];
                    $BatteryType_txt   = $row['BatteryType'];

                    $output .='<option value="'.$BatteryTypeID.'">'.$BatteryType_txt.'</option>';
                }
                echo json_encode($output);
            }

        }

        else if($_POST['action']=='fetchBrandName'){
            $setter = isset($_POST['setter']) ? $_POST['setter'] : 0;
            $query = "SELECT * ";
            $query .="FROM BatteryBrand_tbl ";
            $query .="WHERE IsActive = 1 and IsDeleted = 0 ";
            $query .="AND IsCompetitor = ".$setter." order by BatteryBrand ASC ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){

                $output ='';

                $output .='<option selected disabled value="0">Choose..</option>';

                while($row = odbc_fetch_array($fetch)){

                    $BatteryTypeID     = $row['BatteryBrand'];
                    $BatteryType_txt   = $row['BatteryBrand'];

                    $output .='<option value="'.$BatteryTypeID.'">'.$BatteryType_txt.'</option>';
                }
                echo json_encode($output);
            }

        }

        else if($_POST['action']=='applicationSelection'){
            $btypeID = 0;

            if(isset($_POST['id'])){
                $btypeID = $_POST['id'];
            }

            $query = "SELECT * ";
            $query .="FROM Application_tbl ";
            $query .="WHERE BatteryTypeID = ".$btypeID." ";
            $query .="AND IsActive = 1 and IsDeleted = 0 ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){

                $output ='';

                if($count > 0){

                    while($row = odbc_fetch_array($fetch)){

                        $ApplicationID     = $row['ApplicationID'];
                        $Application_txt   = $row['ApplicationTxt'];

                        $output .='<option value="'.$ApplicationID.'">'.$Application_txt.'</option>';
                    }
                }
                else{
                    $output .='<option selected disabled value="0">Choose..</option>';
                }

                echo json_encode($output);
            }
        }

        else if($_POST['action']=='BatterySizeAddRequest'){
            $applicationTxt = isset($_POST['text']) ? $_POST['text'] : '';

            $query = "select BatterySizeID, BatterySize ";
            $query .="from BatterySizes_tbl ";
            $query .="where ApplicationTxt = '".$applicationTxt."' ";
            $query .="and IsActive = 1 and IsDeleted = 0  order by BatterySize ASC ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){

                $output ='';

                if($count > 0){

                    while($row = odbc_fetch_array($fetch)){

                        $BatterySizeID     = $row['BatterySizeID'];
                        $BatterySize   = $row['BatterySize'];

                        $output .='<option value="'.$BatterySizeID.'">'.$BatterySize.'</option>';
                    }
                }
                else{
                    $output .='<option selected disabled value="0">Choose..</option>';
                }

                echo json_encode($output);
            }
        }

        //--------------Request -------------
        else if($_POST['action']=='RequestGeneration'){
            $dayNo = date("d");
            $monthNo = date("m");
            $output = 0;
            $lastID = 0;
            $RequestMaxID = 0;
            $UserID = $_COOKIE['BTL_employeeID'];

            if(isset($_POST['last_id'])){
                $lastID = $_POST['last_id'];
            }

            $sql = "select max(RequestID) as maxID from Request_tbl where EmployeeID = ".$UserID." ";
            $result = odbc_exec($connServer, $sql);
            if($result){
                while($row = odbc_fetch_array($result)){
                    $RequestMaxID = $row['maxID'];
                }

                $counter = $RequestMaxID+1;
                

                $data = array('---', $lastID, $UserID, 1);
                $query = "Insert into Request_tbl(RequestSysID, RequisitionID, EmployeeID, PrioritizationID) ";
                $query.="values(?, ?, ?, ?) ";

                $stmt = odbc_prepare($connServer, $query);//prepare the query to accept unknown parameter value
                $query_result = odbc_execute($stmt, $data);//execute and map the parameter with the list of data get 

                $requestLastID = 0;
                $output = 0;
                $counter_requestStatus = 0;
                $requestStatusMaxID = 0;
                $statusID = 1;
                if($query_result){
                    
                    $requestLastID = GetLast_Id($connServer);

                    if(isset($_COOKIE['BTL_requestID'])){
                        setcookie("BTL_requestID","",time()-3600 * 24 * 365, '/');
                    }
                    setcookie("BTL_requestID",$requestLastID,time()+3600 * 24 * 365, '/');

                    $data2 = array($requestLastID, $statusID, '---');
                    $query2 = "Insert into RequestStatus_tbl(RequestID, StatusID, Remarks) ";
                    $query2.= "values(?, ?, ?)";

                    $stmt2 = odbc_prepare($connServer, $query2);//prepare the query to accept unknown parameter value
                    $query2_result = odbc_execute($stmt2, $data2);//execute and map the parameter with the list of data get 

                    if($query2_result){
                        $sql2 = "select count(*) as TotalDraft from Request_tbl r join RequestStatus_tbl rs on r.RequestID = rs.RequestID where rs.StatusID = 1 and r.EmployeeID = ".$UserID." ";
                        $result2 = odbc_exec($connServer, $sql2);

                        if($result2){
                            while($row = odbc_fetch_array($result2)){
                                $requestStatusMaxID = $row['TotalDraft'];
                            }

                            $counter_requestStatus = $requestStatusMaxID + 1;
                            $val = 'D'.$dayNo.$monthNo.'-'.sprintf('%03d', $counter_requestStatus).'';

                            $data_update = array($val, $requestLastID, 1, 0);
                            $update_query = "Update Request_tbl set RequestSysID = ? ";
                            $update_query .= "where RequestID = ? and IsActive = ? and IsDeleted = ? ";
                            $stmt_update = odbc_prepare($connServer, $update_query);//prepare the query to accept unknown parameter value
                            $update_result = odbc_execute($stmt_update, $data_update);//execute and map the parameter with the list 
                            confirmQuery($update_result);
                            if($update_result){
                                $output = 1;
                            }
                            
                        }
                    }
                }
            }

            echo json_encode($output);
            
        }
        //--------------Request end-------------

        //--------------Request Details-------------
        else if($_POST['action']=='addRequestDetails'){
            $DisposalID = 0;
            $ProjectName = "";
            $Objective = "";
            $Classification = 0;
            $ClassificationOther = "";
            $output = 0;
            $last_id = 0;

            if(isset($_POST['DisposalID'])){
                $DisposalID = $_POST['DisposalID'];
            }

            if(isset($_POST['ProjectName'])){
                $ProjectName = $_POST['ProjectName'];
            }

            if(isset($_POST['Objective'])){
                $Objective = $_POST['Objective'];
            }

            if(isset($_POST['Classification'])){
                $Classification = $_POST['Classification'];
            }

            if(isset($_POST['ClassificationOther'])){
                $ClassificationOther = $_POST['ClassificationOther'];
            }

            if(isset($_POST['RfsNo'])){
                $RfsNo = $_POST['RfsNo'];
            }

            if($ClassificationOther==null || $ClassificationOther ==''){
                $ClassificationOther = "---";
            }


            // $output = $DisposalID.' - '.$ProjectName.' - '.$Objective.' - '.$Classification.' - '.$ClassificationOther;

            $data = array(htmlspecialchars($ProjectName), htmlspecialchars($Objective), $DisposalID, $Classification,  htmlspecialchars($ClassificationOther), $RfsNo);

            $query = "insert into Requisition_tbl(ProjectName, TestObjective, DisposalID, ClassificationID, ClassificationOthers, RFS_No) ";
            $query.="values(?,?,?,?,?,?) ";

            $stmt = odbc_prepare($connServer, $query);//prepare the query to accept unknown parameter value

            $results = odbc_execute($stmt, $data);//execute and map the parameter with the list of data get from user input

            if($results){
                $output = 1;
                $last_id = GetLast_Id($connServer);
            }

            $arr = array(
                'result' => $output,
                'last_Id' => $last_id
            );
 
            echo json_encode($arr);
        }
        //--------------Request Details End-------------

        //--------------Requestor-------------
        else if($_POST['action']=='addRequestorDetails'){
            $lastID = 0;
            $selectedRequestor = array();
            $arr = array();
            $output = 0;

            if(isset($_POST['last_id'])){
                $lastID = $_POST['last_id'];
            }

            if(isset($_POST['selectedRequestor'])){
                $selectedRequestor = $_POST['selectedRequestor'];
            }

            $result = "";
            //requestor
            foreach($selectedRequestor as $requestor){
                // $arr[] = $requestor;
                // $result .=$requestor;
                $data = array($lastID, $requestor);
                $query = "insert into Requestor_tbl(RequisitionID, EmployeeID) ";
                $query.="values(?, ?)";

                $stmt = odbc_prepare($connServer, $query);//prepare the query to accept unknown parameter value

                $result = odbc_execute($stmt, $data);//execute and map the parameter with the list of data get

                if($result){
                    $output = 1;
                }
            }

            echo json_encode($result);
            //requestor end
        }
        //--------------Requestor end-------------

        //--------------Battery Details-------------

        else if($_POST['action']=='addRequestBatteryDetails'){
            $lastID = 0;
            $BrandName = "";
            $BatteryType = 0;
            $ApplicationType = 0;
            $BatterySize = 0;
            $BatterySizeOtherCode = '---';
            $BatteryCode = "";
            $ProductionCode = "";
            $realBatterySizeVal = '';

            $PositivePlaeSelectVal = 0;
            $PositivePlateCodeVal = "";
            $PositivePlateQty = 0;

            $NegativePlaeSelectVal = 0;
            $NegativePlateCodeVal = "";
            $NegativePlateQty = 0;

            $RCVal = 0;
            $AHVal = 0;
            $CCAVal = 0;
            $C5Val = 0;
            $CAVal = 0;
            $SGVal = 0;
            $OtherVal = "";

            if(isset($_POST['last_id'])){
                $lastID = $_POST['last_id'];
            }

            if(isset($_POST['BrandName'])){
                $BrandName = $_POST['BrandName'];
            }

            if($BrandName==null || $BrandName ==''){
                $BrandName = "---";
            }

            if(isset($_POST['BatteryType'])){
                $BatteryType = $_POST['BatteryType'];
            }

            if(isset($_POST['ApplicationType'])){
                $ApplicationType = $_POST['ApplicationType'];
            }

            if(isset($_POST['BatterySize'])){
                $BatterySize = $_POST['BatterySize'];
            }

            if(isset($_POST['BatterySizeOther'])){
                $BatterySizeOtherCode = $_POST['BatterySizeOther'];
            }

            if($BatterySize=='Others'){
                $realBatterySizeVal = 0;
            }
            else{
                $realBatterySizeVal = $BatterySize;
            }

            if(isset($_POST['BatteryCode'])){
                $BatteryCode = $_POST['BatteryCode'];
            }

            if($BatteryCode==null || $BatteryCode ==''){
                $BatteryCode = "---";
            }

            if(isset($_POST['ProductionCode'])){
                $ProductionCode = $_POST['ProductionCode'];
            }

            if($ProductionCode==null || $ProductionCode==''){
                $ProductionCode = "---";
            }

            //-----------------------------------------------------------------

            if(isset($_POST['PositivePlaeSelectVal'])){
                $PositivePlateSelectVal = $_POST['PositivePlaeSelectVal'];
            }

            if($PositivePlateSelectVal=='Others' || $PositivePlateSelectVal=='N/A'){
                $PositivePlateSelectVal = 0;
            }

            if(isset($_POST['PositivePlateCodeVal'])){
                $PositivePlateCodeVal = $_POST['PositivePlateCodeVal'];
            }

            if($PositivePlateCodeVal==null || $PositivePlateCodeVal ==null){
                $PositivePlateCodeVal = "---";
            }

            if(isset($_POST['PositivePlateQty'])){
                $PositivePlateQty = $_POST['PositivePlateQty'];
            }

            if($PositivePlateQty==null || $PositivePlateQty==''){
                $PositivePlateQty = 0;
            }

            if(isset($_POST['NegativePlaeSelectVal'])){
                $NegativePlateSelectVal = $_POST['NegativePlaeSelectVal'];
            }

            if($NegativePlateSelectVal=='Others' || $NegativePlateSelectVal=='N/A'){
                $NegativePlateSelectVal = 0;
            }

            if(isset($_POST['NegativePlateCodeVal'])){
                $NegativePlateCodeVal = $_POST['NegativePlateCodeVal'];
            }

            if($NegativePlateCodeVal==null || $NegativePlateCodeVal ==null){
                $NegativePlateCodeVal = "---";
            }

            if(isset($_POST['NegativePlateQty'])){
                $NegativePlateQty = $_POST['NegativePlateQty'];
            }

            if($NegativePlateQty==null || $NegativePlateQty==''){
                $NegativePlateQty = 0;
            }

            //-----------------------------------------------------------------

            if(isset($_POST['RCVal'])){
                $RCVal = $_POST['RCVal'];
            }

            if($RCVal==null || $RCVal == ''){
                $RCVal = 0;
            }

            if(isset($_POST['AHVal'])){
                $AHVal = $_POST['AHVal'];
            }

            if($AHVal==null || $AHVal == ''){
                $AHVal = 0;
            }

            if(isset($_POST['CCAVal'])){
                $CCAVal = $_POST['CCAVal'];
            }

            if($CCAVal==null || $CCAVal == ''){
                $CCAVal = 0;
            }

            if(isset($_POST['C5Val'])){
                $C5Val = $_POST['C5Val'];
            }

            if($C5Val==null || $C5Val == ''){
                $C5Val = 0;
            }

            if(isset($_POST['CAVal'])){
                $CAVal = $_POST['CAVal'];
            }

            if($CAVal==null || $CAVal == ''){
                $CAVal = 0;
            }

            if(isset($_POST['SGVal'])){
                $SGVal = $_POST['SGVal'];
            }

            if($SGVal==null || $SGVal == ''){
                $SGVal = 0;
            }

            if(isset($_POST['OtherVal'])){
                $OtherVal = $_POST['OtherVal'];
            }

            if($OtherVal==null || $OtherVal==''){
                $OtherVal = "---";
            }

            // $output = 'Brand: '.$BrandName.' - Battery type: '.$BatteryType.' - Battery Size: '.$BatterySize.' - Battery Code: '.$BatteryCode.' - Prodeuction Code:'.$ProductionCode;
            $data = array($lastID, $BrandName, $BatteryType, $ApplicationType, $BatteryCode, $realBatterySizeVal, $BatterySizeOtherCode, $ProductionCode, $PositivePlateSelectVal, $PositivePlateCodeVal, $PositivePlateQty, $NegativePlateSelectVal, $NegativePlateCodeVal, $NegativePlateQty, $RCVal, $AHVal, $CCAVal, $C5Val, $CAVal,  $SGVal, $OtherVal);
            $query = "Insert into BatteryDetails_tbl(RequisitionID, BatteryBrand, BatteryTypeID, ApplicationID, BatteryCode, BatterySizeID, BatterySizeOther, ProductionCode , PositivePlateID, PositivePlateOthers, PositivePlateQty, NegativePlateID, NegativePlateOthers, NegativePlateQty, RC, AH, CCA, C5, CA, SG, Others) ";
            $query .="values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

            $stmt = odbc_prepare($connServer, $query);//prepare the query to accept unknown parameter value

            $result = odbc_execute($stmt, $data);//execute and map the parameter with the list of data get

            if($result){
                $output = 1;
            }

            echo json_encode($output);

        }

        else if($_POST['action']=='addRequestBatteryPlateDetails'){
            $lastID = 0;

            $PositivePlaeSelectVal = 0;
            $PositivePlateCodeVal = "";
            $PositivePlateQty = 0;

            $NegativePlaeSelectVal = 0;
            $NegativePlateCodeVal = "";
            $NegativePlateQty = 0;

            if(isset($_POST['last_id'])){
                $lastID = $_POST['last_id'];
            }

            if(isset($_POST['PositivePlaeSelectVal'])){
                $PositivePlateSelectVal = $_POST['PositivePlaeSelectVal'];
            }

            if(isset($_POST['PositivePlateCodeVal'])){
                $PositivePlateCodeVal = $_POST['PositivePlateCodeVal'];
            }

            if($PositivePlateCodeVal==null || $PositivePlateCodeVal ==null){
                $PositivePlateCodeVal = "---";
            }

            if(isset($_POST['PositivePlateQty'])){
                $PositivePlateQty = $_POST['PositivePlateQty'];
            }

            if($PositivePlateQty==null || $PositivePlateQty==''){
                $PositivePlateQty = 0;
            }

            if(isset($_POST['NegativePlaeSelectVal'])){
                $NegativePlateSelectVal = $_POST['NegativePlaeSelectVal'];
            }

            if(isset($_POST['NegativePlateCodeVal'])){
                $NegativePlateCodeVal = $_POST['NegativePlateCodeVal'];
            }

            if($NegativePlateCodeVal==null || $NegativePlateCodeVal ==null){
                $NegativePlateCodeVal = "---";
            }

            if(isset($_POST['NegativePlateQty'])){
                $NegativePlateQty = $_POST['NegativePlateQty'];
            }

            if($NegativePlateQty==null || $NegativePlateQty==''){
                $NegativePlateQty = 0;
            }

            // $output = $PositivePlaeSelectVal.' - '.$PositivePlateCodeVal.' - '.$PositivePlateQty.' - '.$NegativePlaeSelectVal.' - '.$NegativePlateCodeVal.' - '.$NegativePlateQty;

            $data1 = array($PositivePlateSelectVal, $PositivePlateCodeVal, $PositivePlateQty, $NegativePlateSelectVal, $NegativePlateCodeVal, $NegativePlateQty, $lastID);

            $query1 = "update BatteryDetails_tbl set PositivePlateID = ?, PositivePlateOthers = ?, PositivePlateQty = ?,  NegativePlateID = ?, NegativePlateOthers = ?, NegativePlateQty = ? ";
            $query1 .="where RequisitionID = ? ";

            $stmt1 = odbc_prepare($connServer, $query1);//prepare the query to accept unknown parameter value

            $result1 = odbc_execute($stmt1, $data1);//execute and map the parameter with the list of data get

            if($result1){
                $output = 1;
            }
            odbc_free_result($stmt1);

            echo json_encode($output);
        }

        else if($_POST['action']=='addRequestBatteryPowerDetails'){
            $lastID = 0;

            $RCVal = 0;
            $AHVal = 0;
            $CCAVal = 0;
            $C5Val = 0;
            $CAVal = 0;
            $SGVal = 0;
            $OtherVal = "";

            if(isset($_POST['last_id'])){
                $lastID = $_POST['last_id'];
            }

            if(isset($_POST['RCVal'])){
                $RCVal = $_POST['RCVal'];
            }

            if($RCVal==null || $RCVal == ''){
                $RCVal = 0;
            }

            if(isset($_POST['AHVal'])){
                $AHVal = $_POST['AHVal'];
            }

            if($AHVal==null || $AHVal == ''){
                $AHVal = 0;
            }

            if(isset($_POST['CCAVal'])){
                $CCAVal = $_POST['CCAVal'];
            }

            if($CCAVal==null || $CCAVal == ''){
                $CCAVal = 0;
            }

            if(isset($_POST['C5Val'])){
                $C5Val = $_POST['C5Val'];
            }

            if($C5Val==null || $C5Val == ''){
                $C5Val = 0;
            }

            if(isset($_POST['CAVal'])){
                $CAVal = $_POST['CAVal'];
            }

            if($CAVal==null || $CAVal == ''){
                $CAVal = 0;
            }

            if(isset($_POST['SGVal'])){
                $SGVal = $_POST['SGVal'];
            }

            if($SGVal==null || $SGVal == ''){
                $SGVal = 0;
            }

            if(isset($_POST['OtherVal'])){
                $OtherVal = $_POST['OtherVal'];
            }

            $output1 = $RCVal.' - '.$AHVal.' - '.$CCAVal.' - '.$C5Val.' - '.$CAVal.' - '.$SGVal.' - '.$OtherVal;

            $data2 = array($RCVal, $AHVal, $CCAVal, $C5Val, $CAVal,  $SGVal, $OtherVal, $lastID);

            $query2 = "update BatteryDetails_tbl set RC = ?, AH = ?, CCA = ?,  C5 = ?, CA = ?, SG = ?, Others = ? ";
            $query2 .="where RequisitionID = ? ";

            $stmt2 = odbc_prepare($connServer, $query2);//prepare the query to accept unknown parameter value

            $result2 = odbc_execute($stmt2, $data2);//execute and map the parameter with the list of data get

            if($result2){
                $output = 1;
            }
            odbc_free_result($stmt2);

            echo json_encode($output1);
        }
         //--------------Battery Details end-------------

         //--------------Special Instruction------------
         else if($_POST['action']=='AddSpecialInstruction'){
            $Id = isset($_POST['last_id']) ? $_POST['last_id'] : null;
            $InstructionVal = isset($_POST['InstructionField']) ? $_POST['InstructionField'] : null;
            $output = 0;

            $data = array($Id, htmlspecialchars($InstructionVal));
            $query = "Insert into RequestSpecialInstruction_tbl(RequisitionID, SpecialInstruction) ";
            $query .="values (?, ?) ";
            $stmt = odbc_prepare($connServer, $query);
            $result = odbc_execute($stmt, $data);

            if($result){
                $output = 1;
            }

            echo json_encode($output);
         }
         //--------------Special Instruction end--------

         //--------------test plan M-series-----------------
         else if($_POST['action']=='TestPlanDisplay'){
            $Request_ID = 0;
            $output = '';

            if(isset($_POST['id'])){
                $Request_ID = $_POST['id'];
            }

            $sql = "select * from TestPlan_tbl where RequestID = ".$Request_ID." ";
            $sql .= "and IsActive = 1 and IsDeleted = 0 ";
            $result = odbc_exec($connServer, $sql);
            $count = odbc_num_rows($result);

            if($count==0){
                $output.='
                            <div class="container shadow-sm rounded p-2 bg-white mb-2">
                                <div class="text-center">
                                    <h6 class="text-primary">No data to be shown</h6>
                                </div>
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

                        $testPlanID = $row['TestPlanID'];
                        $userTestCategoryID = $row['UserTestCategory'];

                        if($userTestCategoryID==1){
                            $UserTest = "M-Series Test";

                            $output.='
                            <div class="container shadow-sm rounded p-2 bg-white mb-2">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <label class="mt-2"><span style="font-weight:bold;">'.$row['TestPlanNo'].'</span> | QTY: '.$row['TotalQty'].' '.$pcs.'</label></br>
                                        <div class="button">
                                            <!--button type="button" class="btn btn-outline-primary btn-sm" onclick="EditMtest('.$testPlanID.', '.$userTestCategoryID.', '.$Request_ID.')"><i class="bi bi-pencil"></i></button-->

                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="DeleteMtest('.$testPlanID.', '.$Request_ID.')"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-1">
                                <span class="badge bg-light-primary mb-2">
                                    <label class="form-check-label" for="customColorCheck1">'.$UserTest.'</label>
                                </span>';
                                $sql1 = "select tt.Test, tpd.TestQty from TestPlanDetails_tbl tpd ";
                                $sql1.="join TestTable_tbl tt ON tpd.TestTableID = tt.TestTableID ";
                                $sql1.="join TestStandard_tbl ts ON tpd.TestStandardID = ts.TestStandardID ";
                                $sql1.="where tpd.TestPlanID =  ".$testPlanID." ";
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
                                            <p class="" style="font-size:14px;line-height:5px;"><span style="font-weight:bold">'.$row1['Test'].'</span> : '.$row1['TestQty'].' '.$pcs2.'</p>
                                        ';
                                    }
                                }

                                if($row['Remarks'] != null || $row['Remarks'] != ''){
                                    $output.='
                                        <p class="" style="font-size:14px;"><span style="font-weight:bold">Remarks</span> :  '.$row['Remarks'].'</p>
                                    ';
                                }
                                
                    $output.='</div>
                            ';
                        }

                        else if($userTestCategoryID==2){
                            $UserTest = "User Test";

                            $output.='
                            <div class="container shadow-sm rounded p-2 bg-white mb-2">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <label class="mt-2"><span style="font-weight:bold;">'.$row['TestPlanNo'].'</span> | QTY: '.$row['TotalQty'].' '.$pcs.'</label></br>
                                        <div class="button">
                                            <!--button type="button" class="btn btn-outline-primary btn-sm" onclick="EditMtest('.$testPlanID.', '.$userTestCategoryID.', '.$Request_ID.')"><i class="bi bi-pencil"></i></button-->

                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="DeleteMtest('.$testPlanID.', '.$Request_ID.')"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-1">
                                <span class="badge bg-light-primary mb-2">
                                    <label class="form-check-label" for="customColorCheck1">'.$UserTest.'</label>
                                </span>';
                                $sql1 = "select tt.Test, tpd.TestQty from TestPlanDetails_tbl tpd ";
                                $sql1.="join TestSpecialTest_tbl tst ON tpd.TestPlanDetailsID = tst.TestPlanDetailID ";
                                $sql1.="join TestTable_tbl tt ON tst.TestTableID = tt.TestTableID ";
                                $sql1.="where tpd.TestPlanID =  ".$testPlanID." ";
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
                                            <p class="" style="font-size:14px;line-height:5px;"><span style="font-weight:bold">
                                                '.rtrim($data).'
                                            </p>
                                        ';

                                if($row['Remarks'] != null || $row['Remarks'] != ''){
                                    $output.='
                                        <p class="" style="font-size:14px;"><span style="font-weight:bold">Remarks</span> :  '.$row['Remarks'].'</p>
                                    ';
                                }
                                
                    $output.='</div>
                            ';
                        }

                        else if($userTestCategoryID==3){
                            $UserTest = "Selected Test";

                            $output.='
                            <div class="container shadow-sm rounded p-2 bg-white mb-2">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <label class="mt-2"><span style="font-weight:bold;">'.$row['TestPlanNo'].'</span> | QTY: '.$row['TotalQty'].' '.$pcs.'</label></br>
                                        <div class="button">
                                            <!--button type="button" class="btn btn-outline-primary btn-sm" onclick="EditMtest('.$testPlanID.', '.$userTestCategoryID.', '.$Request_ID.')"><i class="bi bi-pencil"></i></button-->

                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="DeleteMtest('.$testPlanID.', '.$Request_ID.')"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-1">
                                <span class="badge bg-light-primary mb-2">
                                    <label class="form-check-label" for="customColorCheck1">'.$UserTest.'</label>
                                </span>';
                                $sql1 = "select tt.Test, tpd.TestQty from TestPlanDetails_tbl tpd ";
                                $sql1.="join TestSpecialTest_tbl tst ON tpd.TestPlanDetailsID = tst.TestPlanDetailID ";
                                $sql1.="join TestTable_tbl tt ON tst.TestTableID = tt.TestTableID ";
                                $sql1.="where tpd.TestPlanID =  ".$testPlanID." ";
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
                                            <p class="" style="font-size:14px;line-height:5px;"><span style="font-weight:bold">
                                                '.rtrim($data).'
                                            </p>
                                        ';

                                if($row['Remarks'] != null || $row['Remarks'] != ''){
                                    $output.='
                                        <p class="" style="font-size:14px;"><span style="font-weight:bold">Remarks</span> :  '.$row['Remarks'].'</p>
                                    ';
                                }
                                
                    $output.='</div>
                            ';
                        }

                        else if($userTestCategoryID==4){
                            $UserTest = "Benchmarking";

                            $output.='
                            <div class="container shadow-sm rounded p-2 bg-white mb-2">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <label class="mt-2"><span style="font-weight:bold;">'.$row['TestPlanNo'].'</span> | QTY: '.$row['TotalQty'].' '.$pcs.'</label></br>
                                        <div class="button">
                                            <!--button type="button" class="btn btn-outline-primary btn-sm" onclick="EditMtest('.$testPlanID.', '.$userTestCategoryID.', '.$Request_ID.')"><i class="bi bi-pencil"></i></button-->

                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="DeleteMtest('.$testPlanID.', '.$Request_ID.')"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-1">
                                <span class="badge bg-light-primary mb-2">
                                    <label class="form-check-label" for="customColorCheck1">'.$UserTest.'</label>
                                </span>';
                                
                    $output.='</div>
                            ';
                        }
                        
                    }
                }

            }

            echo json_encode($output);

         }

         else if($_POST['action']=='fetchMseries'){
            $output = '';
            $query = "select * from TestTable_tbl where TestCategoryID = 1 ";
            $result = odbc_exec($connServer, $query);

            if($result){
                while($row = odbc_fetch_array($result)){
                    $output .= '
                    <tr>
                        <td class="text-center">
                            <div class="form-check">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        class="form-check-input form-check-primary form-check-glow"
                                        name="customCheck" id="'.$row['Test'].$row['TestTableID'].'">
                                        <div class="badges">
                                            <span class="badge bg-light-primary">
                                                <label class="form-check-label" for="'.$row['Test'].'">'.$row['Test'].'</label>
                                            </span>
                                        </div>
                                    
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <select class="form-select" id="MStd'.$row['Test'].'" style="font-size:13px;" disabled>';
                                $query2 = "select * from TestStandard_tbl ";
                                $result2 = odbc_exec($connServer, $query2);
                                if($result2){
                                    while($row2 = odbc_fetch_array($result2)){
                                        if($row2['TestStandardID']==2){
                                            $output .= '<option selected value="'.$row2['TestStandardID'].'" selected>'.$row2['TestStandard'].'</option>';
                                        }
                                        else{
                                            $output .= '<option value="'.$row2['TestStandardID'].'">'.$row2['TestStandard'].'</option>';
                                        }
                                    }
                                }
                                
                    $output .='</select>
                        </td>
                        <td class="text-center">
                            <input type="number" id="MQty'.$row['Test'].'" class="form-control qytfield" style="width:130px;" name="MQty'.$row['Test'].'" style="font-size:13px;" placeholder="" disabled>
                        </td>
                    </tr>
                    ';
                }
            }

            echo json_encode($output);
         }

         else if($_POST['action']=='MtestGenerateTestPlan'){
            $Request_ID = 0;
            $counter = 0;
            $last_id = 0;

            if(isset($_COOKIE['BTL_requestID'])){
                $Request_ID = $_COOKIE['BTL_requestID'];
            }

            $sql = "Select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$Request_ID." ";
            $result = odbc_exec($connServer, $sql);

            if($result){
                $counter = odbc_num_rows($result);
                $TestPlanNo = "Test Plan ".$counter+1;
                $data = array($Request_ID, 1, $TestPlanNo, '---');
                $query = "Insert into TestPlan_tbl(RequestID, UserTestCategory, TestPlanNo, Remarks) values(?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
                $query_result = odbc_execute($stmt, $data);

                $last_id = GetLast_Id($connServer);
            }

            $arr = array(
                "TestPlanID" => $last_id,
                "RequestID" => $Request_ID
            );

            echo json_encode($arr);
         }

         else if($_POST['action']=='MtestGenerateTestPlan2'){
            $Request_ID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $counter = 0;
            $last_id = 0;

            $sql = "Select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$Request_ID." ";
            $result = odbc_exec($connServer, $sql);

            if($result){
                $counter = odbc_num_rows($result);
                $TestPlanNo = "Test Plan ".$counter+1;
                $data = array($Request_ID, 1, $TestPlanNo, '---');
                $query = "Insert into TestPlan_tbl(RequestID, UserTestCategory, TestPlanNo, Remarks) values(?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
                $query_result = odbc_execute($stmt, $data);

                $last_id = GetLast_Id($connServer);
            }

            $arr = array(
                "TestPlanID" => $last_id,
                "RequestID" => $Request_ID
            );

            echo json_encode($arr);
         }

         else if($_POST['action']=='MtestData'){
            $remarks = "";
            $testPlanID = 0;
            $requestID = 0;
            $check = false;
            $totalQty = 0;
            $output = 0;
            $rowIndex = 0;
            $STD = 0;
            $QTY = 0;

            
            if(isset($_POST['rowIndex'])){
                $rowIndex = $_POST['rowIndex'];
            }

            if(isset($_POST['STD'])){
                $STD = $_POST['STD'];
            }

            if(isset($_POST['QTY'])){
                $QTY = $_POST['QTY'];
            }

            if(isset($_POST['remarks'])){
                $remarks = $_POST['remarks'];
            }

            if(isset($_POST['testPlanID'])){
                $testPlanID = $_POST['testPlanID'];
            }

            if(isset($_POST['requestID'])){
                $requestID = $_POST['requestID'];
            }

            // $arrayCount = count($CheckedData);

            for ($i = 0; $i < count($rowIndex); $i++) {
                $MtestID = $rowIndex[$i];
                $MtestSTD = $STD[$i];
                $MtestQTY = $QTY[$i];
                
                $data_insert = array($testPlanID, $MtestID, $MtestSTD, $MtestQTY);
                $query = "INSERT INTO TestPlanDetails_tbl (TestPlanID, TestTableID, TestStandardID, TestQty) ";
                $query .= "VALUES (?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
            
                $result = odbc_execute($stmt, $data_insert);
            
                confirmQuery($result);
            
                if ($result) {
                    $check = true;
                    $totalQty += $MtestQTY;
                }
            }

            if($check){
                $data_update = array($totalQty, $remarks, $testPlanID);
                $update_query = "update TestPlan_tbl set TotalQty = ?, Remarks = ? Where TestPlanID = ? ";
                $stmt_update = odbc_prepare($connServer, $update_query);
                $result_update = odbc_execute($stmt_update, $data_update);

                if($result_update){
                    $output = 1;
                }
            }

            $arr = array(
                "requestID" => $requestID,
                "output" => $output
            );

            echo json_encode($arr);
         }

         else if($_POST['action']=='MtestData2'){
            $remarks = "";
            $testPlanID = 0;
            $requestID = 0;
            $check = false;
            $totalQty = 0;
            $output = 0;
            $rowIndex = 0;
            $STD = 0;
            $QTY = 0;

            
            if(isset($_POST['rowIndex'])){
                $rowIndex = $_POST['rowIndex'];
            }

            if(isset($_POST['STD'])){
                $STD = $_POST['STD'];
            }

            if(isset($_POST['QTY'])){
                $QTY = $_POST['QTY'];
            }

            if(isset($_POST['remarks'])){
                $remarks = $_POST['remarks'];
            }

            if(isset($_POST['testPlanID'])){
                $testPlanID = $_POST['testPlanID'];
            }

            if(isset($_POST['requestID'])){
                $requestID = $_POST['requestID'];
            }

            // $arrayCount = count($CheckedData);

            for ($i = 1; $i < count($rowIndex); $i++) {
                $MtestID = $rowIndex[$i];
                $MtestSTD = $STD[$i];
                $MtestQTY = $QTY[$i];
                
                $data_insert = array($testPlanID, $MtestID, $MtestSTD, $MtestQTY);
                $query = "INSERT INTO TestPlanDetails_tbl (TestPlanID, TestTableID, TestStandardID, TestQty) ";
                $query .= "VALUES (?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
            
                $result = odbc_execute($stmt, $data_insert);
            
                confirmQuery($result);
            
                if ($result) {
                    $check = true;
                    $totalQty += $MtestQTY;
                }
            }

            if($check){
                $data_update = array($totalQty, $remarks, $testPlanID);
                $update_query = "update TestPlan_tbl set TotalQty = ?, Remarks = ? Where TestPlanID = ? ";
                $stmt_update = odbc_prepare($connServer, $update_query);
                $result_update = odbc_execute($stmt_update, $data_update);

                if($result_update){
                    $output = 1;
                }
            }

            $arr = array(
                "requestID" => $requestID,
                "output" => $output
            );

            echo json_encode($arr);
         }
         //--------------test plan M-series end-------------

         //--------------test plan User Test series --------
         else if($_POST['action']=='TestSelection'){
            $output = '';
            $sql = "select * from TestCategory_tbl ";
            $sql.="Where TestCategoryID != 1 ";

            $result = odbc_exec($connServer, $sql);
            confirmQuery($result);
            if($result){
                while($row = odbc_fetch_array($result)){
                    // $output .= '
                    //     <optgroup label="'.$row['TestCategory'].'" class="bg-light" style="font-weight:bold;">
                    // ';

                    $sql2 = "select * from TestTable_tbl ";
                    $sql2.="where TestCategoryID = ".$row['TestCategoryID']." ";
                    $result2 = odbc_exec($connServer, $sql2);
                    if($result2){
                        $desc = "";
                        
                        while($row2 = odbc_fetch_array($result2)){
                            if($row2['DescriptionTxt'] != null || $row2['DescriptionTxt'] != ''){
                                $desc = "(".htmlspecialchars($row2['DescriptionTxt']).")";
                            }

                            $output.='
                                <option value="'.$row2['TestTableID'].'">'.$row2['Test'].' '.$desc.'</option>
                            ';
                            // $output = array(
                            //     'id' => $row2['TestTableID'],
                            //     'text' => $row2['Test']
                            // );
                        }
                    }

                    // $output .= '
                    //     </optgroup>
                    // ';
                }
            }

            echo json_encode($output);
         }

         else if($_POST['action']=='USerTestStepTable'){
            $output = '';
            $output1 = '';
            $setter = 0;
            $selectedValue = array();
            $selectedStepTestArray = array();

            $cyclesQty = isset($_POST['cyclesQty']) ? $_POST['cyclesQty'] : 0;
            $Cycletxt = '';
            if($cyclesQty > 1){
                $Cycletxt = $cyclesQty .' Cycles';
            }
            if(isset($_POST['selectedValue'])){
                $selectedValue = $_POST['selectedValue'];
            }
            $selectedStepTestArray = isset($_POST['selectedStepTestArray']) ? $_POST['selectedStepTestArray']: null;

            $n = 1;
            $id_counter = 0;
            $count = count($selectedValue);
            $countTestArray = count($selectedStepTestArray);

            if($count != 0){
                $setter = 1;

                foreach($selectedValue as $testID){
                    $sql = "select * from TestTable_tbl where TestTableID = ".$testID." ";
                    $result = odbc_exec($connServer, $sql);
    
                    if($result){
                        while($row = odbc_fetch_array($result)){
                            $output1 = '<a class="btn btn-primary">'.$row['Test'].' '.$Cycletxt.' | <i class="bi bi-trash" onclick="deleteTestOption('.$id_counter.')" style="cursor:pointer;"></i> </a>';
                        }
                    }
                }

                foreach ($selectedStepTestArray as $index => $item) {
                    $indexVal = $item['indexVal'];
                    $testID = $item['selectedValue'];

                    $sql = "select * from TestTable_tbl where TestTableID = ".$testID." ";
                    $result = odbc_exec($connServer, $sql);
    
                    if($result){
                        while($row = odbc_fetch_array($result)){
                            $output .= '
                                <tr>
                                    <td class="text-center">
                                        Step <span class="indexVal">'.$n.'</span>
                                        <input type="hidden" value="'.$testID.'" />
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-primary">
                                            <label class="form-check-label" for="customColorCheck1">'.$row['Test'].'</label>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select" id="userTestStd" style="font-size:13px;">';
                                        $sql1 = "select * from TestStandard_tbl ";
                                        $result1 = odbc_exec($connServer, $sql1);
    
                                        if($result1){
                                            while($row1 = odbc_fetch_array($result1)){
                                                if($row1['TestStandardID']==2){
                                                    $output .= '
                                                        <option value="'.$row1['TestStandardID'].'" selected>'.$row1['TestStandard'].'</option>
                                                    ';
                                                }
                                                else{
                                                    $output .= '
                                                        <option value="'.$row1['TestStandardID'].'" >'.$row1['TestStandard'].'</option>
                                                    ';
                                                }
                                                
                                            }
                                        }
                            $output .= '</select>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" id="UserNote" class="form-control UserNotes" style="width:130px;" name="UserNote" style="font-size:13px;" placeholder="">
                                    </td>
                                    <td class="text-center">
                                        <fieldset>
                                            <div class="input-group">
                                                <input type="file" class="form-control attachment" id="attachment"
                                                    aria-describedby="attachment" aria-label="Upload" style="width:250px;">
                                            </div>
                                        </fieldset>
                                    </td>
    
                                    <td class="text-center">
                                    <input type="number" id="UserTemp" class="form-control UserQty" style="width:90px;" name="UserTemp" style="font-size:13px;" placeholder="">
                                    </td>
                                    
                                </tr>
                            ';
                        }
                    }
                    $n++;
                    $id_counter++;
                }
            }
            else{
                $setter = 0;
                $output.='';
            }
            
            $arr = array(
                'output' => $output,
                'setter' => $setter,
                'output1' => $output1
            );
            echo json_encode($arr);
         }

         else if($_POST['action']=='UsertestGenerateTestPlan'){
            $Request_ID = 0;
            $counter = 0;
            $last_id = 0;

            $index = isset($_POST["index"]) ? $_POST["index"] : null;
            $TestID = isset($_POST["TestID"]) ? $_POST["TestID"] : null;
            $Notes = isset($_POST["Notes"]) ? $_POST["Notes"] : null;
            $temp = isset($_POST["temp"]) ? $_POST["temp"] : null;
            $std = isset($_POST["std"]) ? $_POST["std"] : null;
            $files = isset($_FILES["files"]) ? $_FILES["files"] : 0;
            $setter = isset($_POST["setter"]) ? $_POST["setter"] : null;

            $remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : null;
            $quantity = isset($_POST["qty"]) ? $_POST["qty"] : null;

            $filename = "";
            $fileContent = "";

            $fileData = array();

            if(isset($_COOKIE['BTL_requestID'])){
                $Request_ID = $_COOKIE['BTL_requestID'];
            }

            $sql = "Select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$Request_ID." ";
            $result = odbc_exec($connServer, $sql);

            if($result){
                $counter = odbc_num_rows($result);
                $TestPlanNo = "Test Plan ".$counter+1;
                $data = array($Request_ID, 2, $TestPlanNo, $remarks, intval($quantity));
                $query = "Insert into TestPlan_tbl(RequestID, UserTestCategory, TestPlanNo, Remarks, TotalQty) values(?, ?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
                $query_result = odbc_execute($stmt, $data);

                $last_id = GetLast_Id($connServer);
                $setterVal = 0;

                if($query_result){
                    $data_details = array($last_id, 46, intval($quantity), $remarks);
                    $query_details = "Insert into TestPlanDetails_tbl(TestPlanID, TestTableID, TestQty, Remarks) ";
                    $query_details .="values(?, ?, ?, ?) ";

                    $details_stmt = odbc_prepare($connServer, $query_details);
                    $details_result = odbc_execute($details_stmt, $data_details);
                    confirmQuery($details_result);
                    $lastDetails_id = GetLast_Id($connServer);
                    
                    for ($i = 0; $i < count($index); $i++) {
                        $indexVal = $index[$i];
                        $hiddenValue = $TestID[$i];
                        $notesValue = $Notes[$i];
                        $tempValue = $temp[$i];
                        $selectValue = $std[$i];
                        $setterVal = $setter[$i];

                        $data_details2 = array($lastDetails_id, $hiddenValue, floatval($tempValue), $selectValue, $notesValue);
                        $query_details2 = "Insert into TestSpecialTest_tbl(TestPlanDetailID, TestTableID, TestTemperature, TestStandardID, TestNotes) ";
                        $query_details2 .="values(?, ?, ?, ?, ?) ";

                        $details_stmt2 = odbc_prepare($connServer, $query_details2);
                        $details_result2 = odbc_execute($details_stmt2, $data_details2);
                        confirmQuery($details_result2);
                        $lastDetails_id2 = GetLast_Id($connServer);

                    }
                    if($files !=0){
                        $numFiles = count($files["name"]);

                        for ($x = 0; $x < $numFiles; $x++) {
                            $file = $files["tmp_name"][$x];
                            $filename = $files["name"][$x];
                            $fileContent = base64_encode(file_get_contents($file));
                            
                            $targetDirectory = "../../FileUpload/Customer/"; // Directory where the uploaded file will be stored
                            $targetDirectory2 = "FileUpload/Customer/";
                            $namefile = uniqid() . "_" . basename($filename);
                            
                            $dirctoryDB = $targetDirectory2 . $namefile;
                            $targetFile = $targetDirectory . $namefile;
                            
                            $uploadOk = true;
        
                            // Check if the file was uploaded successfully
                            if ($uploadOk) {
                                if (move_uploaded_file($file, $targetFile)) {
                                    // echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                                    $fileData = array($lastDetails_id2, $namefile, $dirctoryDB);

                                    $insertFile = "Insert into TestPlanAttachmentDetails_tbl(TestPlanDetailsID, TestPlanAttachment, TestPlanAttachmentDirectory ) ";
                                    $insertFile.="values(?, ?, ?) ";
                                    $file_stmt = odbc_prepare($connServer, $insertFile);
                                    $fileExecute = odbc_execute($file_stmt, $fileData);

                                } else {
                                    echo "Sorry, there was an error uploading your file.";
                                }
                            }
                        }
                    }
                    
        
                }
            }


            $arr = array(
                "qty" => $quantity,
                "remarks" => $remarks,
                "TableData" => $fileData,
                "TestPlanID" => $last_id,
                "requestID" => $Request_ID
            );

            echo json_encode($arr);
         }

         else if($_POST['action']=='UsertestGenerateTestPlanEdit'){
            $Request_ID = 0;
            $counter = 0;
            $last_id = 0;

            $index = isset($_POST["index"]) ? $_POST["index"] : null;
            $TestID = isset($_POST["TestID"]) ? $_POST["TestID"] : null;
            $Notes = isset($_POST["Notes"]) ? $_POST["Notes"] : null;
            $temp = isset($_POST["temp"]) ? $_POST["temp"] : null;
            $std = isset($_POST["std"]) ? $_POST["std"] : null;
            $files = isset($_FILES["files"]) ? $_FILES["files"] : 0;
            $setter = isset($_POST["setter"]) ? $_POST["setter"] : null;

            $remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : null;
            $quantity = isset($_POST["qty"]) ? $_POST["qty"] : null;
            $Request_ID = isset($_POST["RequestID"]) ? $_POST["RequestID"] : 0;

            $filename = "";
            $fileContent = "";

            $fileData = array();


            $sql = "Select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$Request_ID." ";
            $result = odbc_exec($connServer, $sql);

            if($result){
                $counter = odbc_num_rows($result);
                $TestPlanNo = "Test Plan ".$counter+1;
                $data = array($Request_ID, 2, $TestPlanNo, $remarks, intval($quantity));
                $query = "Insert into TestPlan_tbl(RequestID, UserTestCategory, TestPlanNo, Remarks, TotalQty) values(?, ?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
                $query_result = odbc_execute($stmt, $data);

                $last_id = GetLast_Id($connServer);
                $setterVal = 0;

                if($query_result){
                    $data_details = array($last_id, 46, intval($quantity), $remarks);
                    $query_details = "Insert into TestPlanDetails_tbl(TestPlanID, TestTableID, TestQty, Remarks) ";
                    $query_details .="values(?, ?, ?, ?) ";

                    $details_stmt = odbc_prepare($connServer, $query_details);
                    $details_result = odbc_execute($details_stmt, $data_details);
                    confirmQuery($details_result);
                    $lastDetails_id = GetLast_Id($connServer);
                    
                    for ($i = 0; $i < count($index); $i++) {
                        $indexVal = $index[$i];
                        $hiddenValue = $TestID[$i];
                        $notesValue = $Notes[$i];
                        $tempValue = $temp[$i];
                        $selectValue = $std[$i];
                        $setterVal = $setter[$i];

                        $data_details2 = array($lastDetails_id, $hiddenValue, floatval($tempValue), $selectValue, $notesValue);
                        $query_details2 = "Insert into TestSpecialTest_tbl(TestPlanDetailID, TestTableID, TestTemperature, TestStandardID, TestNotes) ";
                        $query_details2 .="values(?, ?, ?, ?, ?) ";

                        $details_stmt2 = odbc_prepare($connServer, $query_details2);
                        $details_result2 = odbc_execute($details_stmt2, $data_details2);
                        confirmQuery($details_result2);
                        $lastDetails_id2 = GetLast_Id($connServer);

                    }
                    if($files !=0){
                        $numFiles = count($files["name"]);

                        for ($x = 0; $x < $numFiles; $x++) {
                            $file = $files["tmp_name"][$x];
                            $filename = $files["name"][$x];
                            $fileContent = base64_encode(file_get_contents($file));
                            
                            $targetDirectory = "../../FileUpload/Customer/"; // Directory where the uploaded file will be stored
                            $targetDirectory2 = "FileUpload/Customer/";
                            $namefile = uniqid() . "_" . basename($filename);
                            
                            $dirctoryDB = $targetDirectory2 . $namefile;
                            $targetFile = $targetDirectory . $namefile;
                            
                            $uploadOk = true;
        
                            // Check if the file was uploaded successfully
                            if ($uploadOk) {
                                if (move_uploaded_file($file, $targetFile)) {
                                    // echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                                    $fileData = array($lastDetails_id2, $namefile, $dirctoryDB);

                                    $insertFile = "Insert into TestPlanAttachmentDetails_tbl(TestPlanDetailsID, TestPlanAttachment, TestPlanAttachmentDirectory ) ";
                                    $insertFile.="values(?, ?, ?) ";
                                    $file_stmt = odbc_prepare($connServer, $insertFile);
                                    $fileExecute = odbc_execute($file_stmt, $fileData);

                                } else {
                                    echo "Sorry, there was an error uploading your file.";
                                }
                            }
                        }
                    }
                    
        
                }
            }


            $arr = array(
                "qty" => $quantity,
                "remarks" => $remarks,
                "TableData" => $fileData,
                "TestPlanID" => $last_id,
                "requestID" => $Request_ID
            );

            echo json_encode($arr);
         }
         //--------------test plan User Test series end-------

         //--------------test plan select battery test --------
         else if($_POST['action']=='USerTestStepTable2'){
            $output = '';
            $setter = 0;
            $selectedValue = array();

            if(isset($_POST['selectedValue'])){
                $selectedValue = $_POST['selectedValue'];
            }
            $n = 1;
            $count = count($selectedValue);
            if($count != 0){
                $setter = 1;
                foreach($selectedValue as $testID){
                    $sql = "select * from TestTable_tbl where TestTableID = ".$testID." ";
                    $result = odbc_exec($connServer, $sql);
    
                    if($result){
                        while($row = odbc_fetch_array($result)){
                            $output .= '
                                <tr>
                                    <td class="text-center">
                                        <span class="indexVal">'.$n.'</span>
                                        <input type="hidden" value="'.$testID.'" />
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-primary">
                                            <label class="form-check-label" for="customColorCheck1">'.$row['Test'].'</label>
                                        </span>
                                    </td>
                                    
                                </tr>
                            ';
                        }
                        
                    }
                    $n++;
                }
            }
            else{
                $setter = 0;
                $output.='';
            }
            
            $arr = array(
                'output' => $output,
                'setter' => $setter
            );
            echo json_encode($arr);
         }

         else if($_POST['action']=='UsertestGenerateTestPlan2'){
            $Request_ID = 0;
            $counter = 0;
            $last_id = 0;

            $TestID = isset($_POST["TestID"]) ? $_POST["TestID"] : null;

            $remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : null;
            $quantity = isset($_POST["qty"]) ? $_POST["qty"] : null;

            if(isset($_COOKIE['BTL_requestID'])){
                $Request_ID = $_COOKIE['BTL_requestID'];
            }

            $sql = "Select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$Request_ID." ";
            $result = odbc_exec($connServer, $sql);

            if($result){
                $counter = odbc_num_rows($result);
                $TestPlanNo = "Test Plan ".$counter+1;
                $data = array($Request_ID, 3, $TestPlanNo, $remarks, intval($quantity));
                $query = "Insert into TestPlan_tbl(RequestID, UserTestCategory, TestPlanNo, Remarks, TotalQty) values(?, ?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
                $query_result = odbc_execute($stmt, $data);

                $last_id = GetLast_Id($connServer);
                $setterVal = 0;

                if($query_result){
                    $data_details = array($last_id, 46, intval($quantity), $remarks);
                    $query_details = "Insert into TestPlanDetails_tbl(TestPlanID, TestTableID, TestQty, Remarks) ";
                    $query_details .="values(?, ?, ?, ?) ";

                    $details_stmt = odbc_prepare($connServer, $query_details);
                    $details_result = odbc_execute($details_stmt, $data_details);
                    confirmQuery($details_result);
                    $lastDetails_id = GetLast_Id($connServer);

                    for ($i = 0; $i < count($TestID); $i++) {
 
                        $hiddenValue = $TestID[$i];

                        $data_details2 = array($lastDetails_id, $hiddenValue);

                        $query_details2 = "Insert into TestSpecialTest_tbl(TestPlanDetailID, TestTableID) ";
                        $query_details2 .="values(?, ?) ";

                        $details_stmt2 = odbc_prepare($connServer, $query_details2);
                        $details_result2 = odbc_execute($details_stmt2, $data_details2);
                        confirmQuery($details_result2);
                        $lastDetails_id2 = GetLast_Id($connServer);

                    }
        
                }
            }


            $arr = array(
                "qty" => $quantity,
                "remarks" => $remarks,
                "TestPlanID" => $last_id,
                "requestID" => $Request_ID
            );

            echo json_encode($arr);
         }

         else if($_POST['action']=='UsertestGenerateTestPlan2Edit'){
            $Request_ID = 0;
            $counter = 0;
            $last_id = 0;

            $TestID = isset($_POST["TestID"]) ? $_POST["TestID"] : null;

            $remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : null;
            $quantity = isset($_POST["qty"]) ? $_POST["qty"] : null;

            $Request_ID = isset($_POST["RequestID"]) ? $_POST["RequestID"] : 0;

            $sql = "Select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$Request_ID." ";
            $result = odbc_exec($connServer, $sql);

            if($result){
                $counter = odbc_num_rows($result);
                $TestPlanNo = "Test Plan ".$counter+1;
                $data = array($Request_ID, 3, $TestPlanNo, $remarks, intval($quantity));
                $query = "Insert into TestPlan_tbl(RequestID, UserTestCategory, TestPlanNo, Remarks, TotalQty) values(?, ?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
                $query_result = odbc_execute($stmt, $data);

                $last_id = GetLast_Id($connServer);
                $setterVal = 0;

                if($query_result){
                    $data_details = array($last_id, 46, intval($quantity), $remarks);
                    $query_details = "Insert into TestPlanDetails_tbl(TestPlanID, TestTableID, TestQty, Remarks) ";
                    $query_details .="values(?, ?, ?, ?) ";

                    $details_stmt = odbc_prepare($connServer, $query_details);
                    $details_result = odbc_execute($details_stmt, $data_details);
                    confirmQuery($details_result);
                    $lastDetails_id = GetLast_Id($connServer);

                    for ($i = 0; $i < count($TestID); $i++) {
 
                        $hiddenValue = $TestID[$i];

                        $data_details2 = array($lastDetails_id, $hiddenValue);

                        $query_details2 = "Insert into TestSpecialTest_tbl(TestPlanDetailID, TestTableID) ";
                        $query_details2 .="values(?, ?) ";

                        $details_stmt2 = odbc_prepare($connServer, $query_details2);
                        $details_result2 = odbc_execute($details_stmt2, $data_details2);
                        confirmQuery($details_result2);
                        $lastDetails_id2 = GetLast_Id($connServer);

                    }
        
                }
            }


            $arr = array(
                "qty" => $quantity,
                "remarks" => $remarks,
                "TestPlanID" => $last_id,
                "requestID" => $Request_ID
            );

            echo json_encode($arr);
         }
         //--------------test plan select battery test end--------

        //--------------test plan benchmark --------
        else if($_POST['action']=='UsertestGenerateTestPlan3'){
            $Request_ID = 0;
            $counter = 0;
            $last_id = 0;

            $remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : null;
            $quantity = isset($_POST["qty"]) ? $_POST["qty"] : null;

            if(isset($_COOKIE['BTL_requestID'])){
                $Request_ID = $_COOKIE['BTL_requestID'];
            }

            $sql = "Select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$Request_ID." ";
            $result = odbc_exec($connServer, $sql);

            if($result){
                $counter = odbc_num_rows($result);
                $TestPlanNo = "Test Plan ".$counter+1;
                $data = array($Request_ID, 4, $TestPlanNo, $remarks, intval($quantity));
                $query = "Insert into TestPlan_tbl(RequestID, UserTestCategory, TestPlanNo, Remarks, TotalQty) values(?, ?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
                $query_result = odbc_execute($stmt, $data);

                $last_id = GetLast_Id($connServer);
                $setterVal = 0;

                if($query_result){

                    $data_details = array($last_id, 39, 0, 2);
                    $query_details = "Insert into TestPlanDetails_tbl(TestPlanID, TestTableID, TestQty, TestStandardID ) ";
                    $query_details .="values(?, ?, ?, ?) ";

                    $details_stmt = odbc_prepare($connServer, $query_details);
                    $details_result = odbc_execute($details_stmt, $data_details);
                    confirmQuery($details_result);
                    $lastDetails_id = GetLast_Id($connServer);
                }
            }


            $arr = array(
                "qty" => $quantity,
                "remarks" => $remarks,
                "TestPlanID" => $last_id,
                "requestID" => $Request_ID
            );

            echo json_encode($arr);
        }
        else if($_POST['action']=='UsertestGenerateTestPlan3Edit'){
            $Request_ID = 0;
            $counter = 0;
            $last_id = 0;

            $remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : null;
            $quantity = isset($_POST["qty"]) ? $_POST["qty"] : null;

            $Request_ID = isset($_POST["RequestID"]) ? $_POST["RequestID"] : 0;

            $sql = "Select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$Request_ID." ";
            $result = odbc_exec($connServer, $sql);

            if($result){
                $counter = odbc_num_rows($result);
                $TestPlanNo = "Test Plan ".$counter+1;
                $data = array($Request_ID, 4, $TestPlanNo, $remarks, intval($quantity));
                $query = "Insert into TestPlan_tbl(RequestID, UserTestCategory, TestPlanNo, Remarks, TotalQty) values(?, ?, ?, ?, ?) ";
                $stmt = odbc_prepare($connServer, $query);
                $query_result = odbc_execute($stmt, $data);

                $last_id = GetLast_Id($connServer);
                $setterVal = 0;

                if($query_result){

                    $data_details = array($last_id, 39, 0, 2);
                    $query_details = "Insert into TestPlanDetails_tbl(TestPlanID, TestTableID, TestQty, TestStandardID ) ";
                    $query_details .="values(?, ?, ?, ?) ";

                    $details_stmt = odbc_prepare($connServer, $query_details);
                    $details_result = odbc_execute($details_stmt, $data_details);
                    confirmQuery($details_result);
                    $lastDetails_id = GetLast_Id($connServer);
                }
            }


            $arr = array(
                "qty" => $quantity,
                "remarks" => $remarks,
                "TestPlanID" => $last_id,
                "requestID" => $Request_ID
            );

            echo json_encode($arr);
        }
        //--------------test plan benchmark end --------
        //-------------test plan deletion----------
        else if($_POST['action']=='deleteTestPlan'){
            $testPlanID = isset($_POST['id']) ? $_POST['id'] : 0;
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $result = 0;

            $sqlDelete = "update TestPlan_tbl set IsActive = 0, IsDeleted = 1, DateModified = getdate() where TestPlanID = ".$testPlanID." ";
            $execDelete = odbc_exec($connServer, $sqlDelete);
            
            if($execDelete){
                $sqlSort = "select * from TestPlan_tbl where IsActive = 1 and IsDeleted = 0 and RequestID = ".$requestID." ";
                $execSort = odbc_exec($connServer, $sqlSort);
                $n = 1;
                while($rowSort = odbc_fetch_array($execSort)){
                    $testPlanText = 'Test Plan '.$n;
                    $testID = $rowSort['TestPlanID'];
                    $sorting = "update TestPlan_tbl set TestPlanNo = '".$testPlanText."' where TestPlanID = ".$testID;
                    $sortingExec = odbc_exec($connServer, $sorting);
                    $n++;
                }

                $result = 1;
            }

            echo json_encode($result);
        }
        //-------------test plan deletion end------
        //--------------review request --------
        else if($_POST['action']=='ReviewRequest'){
            $requestID = 0;
            $output = '';

            if(isset($_COOKIE['BTL_requestID'])){
                $requestID = $_COOKIE['BTL_requestID'];
            }

            $sql = "select *, negative.PlateType as negativePlate, positive.PlateType as positivePlate  from Request_tbl rt ";
            $sql .= "join Requisition_tbl rqt ON rt.RequisitionID = rqt.RequisitionID ";
            $sql .= "join Disposal_tbl dt ON rqt.DisposalID = dt.DisposalID ";
            $sql .= "join Classification_tbl ct ON rqt.ClassificationID = ct.ClassificationID ";
            $sql .= "join BatteryDetails_tbl bdt ON rqt.RequisitionID = bdt.RequisitionID ";
            $sql .= "join BatteryType_tbl btt ON bdt.BatteryTypeID = btt.BatteryTypeID ";
            $sql .= "join BatterySizes_tbl sizes ON bdt.BatterySizeID = sizes.BatterySizeID ";
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
                    ';
                        if($statusID == 6){
                            $output .='
                            <div class="container shadow-sm rounded p-2 mb-2 ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-outline-primary float-end" onclick="GenerateQR(\'' . $RequstSystemID . '\')">
                                        <i class="bi bi-upc-scan"> </i>
                                        </button>
                                        <i class="float-end text-white">-</i>
                                        <button class="btn btn-outline-primary float-end" onclick="printBTR()">
                                        <i class="bi bi-printer-fill"> </i>
                                        </button>
                                    </div>
                                </div>
                            </div>';
                        }
                        $output .= '
                        <input type="hidden" value="'.$requestID.'" id="RequestID_ref">
                        <div class="card shadow-sm  rounded p-2 mb-2 ">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><label class="text-primary" style="font-weight:bold;">DISPOSAL OF BATTERIES: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['ProcedureTxt'].'</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm  rounded p-2 mb-2">
                            <div class="card-body">
                                <div class="divider divider-center">
                                    <div class="divider-text text-primary">BATTERY SAMPLE CLASSIFICATION</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><label class="text-primary" style="font-weight:bold;">Classification: </label>&nbsp;&nbsp;<span class="text-secondary">'.$row['ClassificationTxt'].'</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="card shadow-sm  rounded p-2 mb-2">
                            <div class="card-body">
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
                        </div>
        
                        <div class="card shadow-sm  rounded p-2 mb-2">
                            <div class="card-body">
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
                        </div>
        
                        <div class="card shadow-sm  rounded p-2 mb-2">
                            <div class="card-body">
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
                    </div>
                    ';
            }

            echo json_encode($output);

        }   
        //--------------review request end-----

        //-------------Submit and update request id---
        else if($_POST['action']=='SaveRequest'){
            $Request_ID = 0;
            $output = 0;
            $employeeID = isset($_COOKIE["BTL_employeeID"]) ? $_COOKIE["BTL_employeeID"] : null;
            if(isset($_COOKIE['BTL_requestID'])){
                $Request_ID = $_COOKIE['BTL_requestID'];
            }

            $testPlanSql = "select * from TestPlan_tbl where RequestID = ".$Request_ID." ";
            $testPlanSql .= "and IsActive = 1 and IsDeleted = 0 ";
            $executeTestPlan = odbc_exec($connServer, $testPlanSql);
            
            if($executeTestPlan){
                $hasTestPlan = odbc_num_rows($executeTestPlan);
                if($hasTestPlan!=0){
                    $remarks = "For Approval";

                    $sql = "Insert into RequestStatus_tbl (RequestID, StatusID, Remarks, EmployeeID) ";
                    $sql.= "values(".$Request_ID.", 2, '".$remarks."', ".$employeeID.") ";
                    
                    $resultInsert = odbc_exec($connServer, $sql);

                    if($resultInsert){
                        $GetCount = 0;
                        $checkIfExist = "if exists(select * from RequestStatus_tbl where RequestID = ".$Request_ID." and    StatusID = 2 and IsActive = 1 and IsDeleted = 0 HAVING COUNT(*) > 1)
                            begin
                                select setter = 1
                            End
                        else
                            begin
                                select setter = 0
                            end";
                        $checkIfExistResult = odbc_exec($connServer, $checkIfExist);
                        $checIfExistRow = odbc_fetch_array($checkIfExistResult);
                        $setter = $checIfExistRow['setter'];

                        if($setter==0){
                            $query = " select count(r.RequestID) as count from Request_tbl r
                            cross apply (select top 1 StatusID from RequestStatus_tbl rq where rq.StatusID = 2 and rq.RequestID = r.RequestID order by rq.DateCreated DESC ) stat ";
                            $queryResult = odbc_exec($connServer, $query);

                            if($queryResult){
                                $dayNo = date("d");
                                $monthNo = date("m");
                                $countResult = odbc_fetch_array($queryResult);

                                if($countResult!=0){
                                    $GetCount = $countResult['count'] + 1;
                                }
                                else{
                                    $GetCount = 1;
                                }

                                $GeneratedID = sprintf('%03d', $GetCount);
                                $val = 'R'.$dayNo.$monthNo.'-'.$GeneratedID.'';

                                $query_update = "update Request_tbl set RequestSysID = '".$val."', DateModified = getdate() ";
                                $query_update.="where RequestID = ".$Request_ID." ";
                                $result = odbc_exec($connServer, $query_update);
                                if($result){
                                    $output = 1;
                                }
                            }
                        }
                        else{
                            $output = 1;
                        } 
                    }
                }
                else{
                    $output = 2;
                }
            }
            echo json_encode($output);

            if($output==1){
                emailSenderFunction($connServer, 1, $Request_ID, $employeeID);
            }
        }
        //-------------Submit and update request id end---

        else if($_POST['action']=='Request_tbl'){
            $employeeID = 0;
            if(isset($_COOKIE['BTL_employeeID'])){
                $employeeID = $_COOKIE['BTL_employeeID'];
            }
            $search = "%".$_POST["search"]["value"]."%";

            $column = array("RequestSysID", "BatteryCode", "ProjectName", "TestObjective", "StatusTxt", "TotalQty", "DateCreated", "" );

            $query = "SELECT r.RequestID, r.RequestSysID, bd.BatteryCode, rs.ProjectName, rs.TestObjective, Status.StatusID, Status.StatusTxt, qty.TotalQty, r.DateCreated ";
            $query .="FROM Request_tbl r ";
            $query .="LEFT JOIN Requisition_tbl rs ON r.RequisitionID = rs.RequisitionID ";
            $query .="LEFT JOIN BatteryDetails_tbl bd ON rs.RequisitionID = bd.RequisitionID ";
            $query .="cross apply (
                        select top 1 res.StatusID, stat.StatusTxt  from RequestStatus_tbl res join Status_tbl stat ON res.StatusID = stat.StatusID where res.RequestID = r.RequestID order by res.DateCreated DESC
                    ) as Status ";
            $query .="cross apply (
                        select COALESCE(sum(TotalQty), 0) as TotalQty from TestPlan_tbl where RequestID = r.RequestID and IsActive = 1 and IsDeleted = 0
                    ) as qty ";
            $query .="WHERE r.EmployeeID = ".$employeeID." and r.IsActive = 1 and r.IsDeleted = 0 ";
            

            if(isset($_POST["search"]["value"])){											
                if(!empty($_POST["search"]["value"])){
                    $query .='AND (Status.StatusTxt LIKE ? ';
                    $query .='OR bd.BatteryCode LIKE ? ';
                    $query .='OR r.RequestSysID LIKE ? ';
                    $query .='OR rs.ProjectName LIKE ? ';
                    $query .='OR rs.TestObjective LIKE ?) ';
                }
            }

            if(isset($_POST["order"])){

                $query .='ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir']. ' ';
            } 

            else{

                $query .='ORDER BY r.DateCreated DESC ';
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
                odbc_execute($result, array($search, $search, $search, $search, $search));
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
                $BatteryCode      = $row['BatteryCode'];
                $ProjectName    = $row['ProjectName'];
                $TestObjective      = $row['TestObjective'];
                $StatusID       = $row['StatusID'];
                $StatusTxt     = $row['StatusTxt'];
                $TotalQty      = $row['TotalQty'];
                $dateAdded = $row['DateCreated'];

                $pcs = "";
                if($TotalQty > 1){
                    $pcs = "pcs";
                }
                else{
                    $pcs = "pc";
                }

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
                else if($StatusID==3){
                    $statusContainer = '<div class="badges">
                                            <span class="badge bg-light-primary">'.$StatusTxt.'</span>
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
                else if($StatusID==8){
                    $statusContainer = '<div class="badges">
                                            <span class="badge bg-light-primary">'.$StatusTxt.'</span>
                                        </div>';
                }

                $sub_array = array();
                $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".$RequestSysID."</span>";
                $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".$BatteryCode."</span>";
                $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".$ProjectName."</span>";
                $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".$TestObjective."</span>";
                $sub_array[] = $statusContainer;
                $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".number_format($TotalQty)." ".$pcs."</span>";
                $sub_array[] = "<span style='white-space:nowrap;font-weight:bold;'>".date('M d, Y',strtotime($dateAdded))."</span>";

                if($StatusID==1 || $StatusID==7){
                    $sub_array[] = '
                    <div class="btn-group mb-0">
                    <button type="button" class="btn btn-sm btn-primary" onclick="EditDraftRequest('.$RequestID.')"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="DeleteDraftRequest('.$RequestID.')"><i class="bi bi-trash"></i></button>
                    </div>';
                }
                else if($StatusID==2 || $StatusID==3 || $StatusID==6 || $StatusID==7 || $StatusID==8){
                    $sub_array[] = '<button type="button" class="btn btn-sm btn-info" onclick="ViewRequest('.$RequestID.')"><i class="bi bi-eye"></i></button>';
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

        else if($_POST['action']=='BTR_ViewRequest'){
            $requestID = 0;
            $output = '';
            $requestStatusID = 0;
    
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
                    if($statusID == 6){
                        $output .='
                        <div class="container shadow-sm rounded p-2 mb-2 ">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-outline-primary float-end" onclick="GenerateQR(\'' . $RequstSystemID . '\')">
                                    <i class="bi bi-upc-scan"> </i>
                                    </button>
                                    <i class="float-end text-white">&nbsp;&nbsp;</i>
                                    <button class="btn btn-outline-primary float-end" onclick="printBTR()">
                                    <i class="bi bi-printer-fill"> </i>
                                    </button>
                                </div>
                            </div>
                        </div>';
                    }
                    $output .= '
                    <input type="hidden" value="'.$requestID.'" id="RequestID_ref">
                    <div class="card shadow-sm rounded p-2 mb-2 ">
                        <div class="row">
                            <div class="col-md-12">
                                <p></p>
                                <p></p>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm rounded p-2 mb-2 ">
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

                    <div class="card shadow-sm rounded p-2 mb-2">
                        <div class="divider divider-center">
                            <div class="divider-text text-primary">REMARKS HISTORY</div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">';
                                
                                $sqlLatestRemarks = " SELECT top 1 rs.*, empLevel.UserLevel,
                                                            CASE
                                                                WHEN Remarks IS NULL OR Remarks = '' THEN
                                                                    (SELECT StatusTxt FROM Status_tbl WHERE StatusID = rs.StatusID)
                                                                ELSE Remarks
                                                            END AS Result,
                                                            
                                                            CASE
                                                                WHEN TRY_CONVERT(int, rs.EmployeeID) IS NULL THEN
                                                                    0
                                                                ELSE TRY_CONVERT(int, rs.EmployeeID)
                                                            END AS EmployeeID_new,
                                                            
                                                            emp.Fname,
                                                            emp.Lname
                                                        FROM RequestStatus_tbl rs
                                                        join EmployeeInfo_tbl info on rs.EmployeeID = info.EmployeeID
                                                        join UserLevel_tbl empLevel on info.UserLevelID = empLevel.UserLevelID
                                                        CROSS APPLY (
                                                            SELECT
                                                                CASE
                                                                    WHEN TRY_CONVERT(int, rs.EmployeeID) IS NULL THEN 0
                                                                    ELSE TRY_CONVERT(int, rs.EmployeeID)
                                                                END AS EmployeeID_cond
                                                        ) AS empCond
                                                        CROSS APPLY (
                                                            SELECT
                                                                Fname,
                                                                Lname
                                                            FROM Employee_tbl
                                                            WHERE EmployeeID = empCond.EmployeeID_cond
                                                        ) AS emp
                                                        WHERE RequestID = ".$requestID." AND StatusID != 1
                                                            AND rs.IsActive = 1 AND rs.IsDeleted = 0
                                                        ORDER BY DateCreated DESC ";
                                $sqlLatestRemarksResult = odbc_exec($connServer, $sqlLatestRemarks);
                                $sqlLatestRemarksCount = odbc_num_rows($sqlLatestRemarksResult);
                                if($sqlLatestRemarksCount !=0){
                                    while($RemarksRow = odbc_fetch_array($sqlLatestRemarksResult)){
                                        $fname = GetNameInitials($RemarksRow['Fname']);
                                        $Lname = GetNameInitials($RemarksRow['Lname']);
                                        $nameInitials = $fname.$Lname;
                                        $requestStatusID = $RemarksRow['RequestStatusID'];
                                        $concatName = $RemarksRow['Fname']." ".$RemarksRow['Lname'];
                                        $output .= '<div class="avatar bg-warning me-3">
                                                        <span class="avatar-content">'.$nameInitials.'</span>
                                                    </div>
                                                    <div class="badge bg-light-primary p-2" style="text-align: left;font-weight:bold;font-size:12px;">
                                                        <span style="font-size:11px;font-weight:normal;">'.$concatName.' | '.$RemarksRow['UserLevel'].', Thursday 9:03 AM</span> </br>
                                                        <hr style="padding-top: 3px;padding-bottom: 7px;margin: 0;">
                                                        '.$RemarksRow['Result'].'
                                                    </div>';
                                    }
                                    
                                }
                                
                $output .= '</p>';
                            $RemarksCountSql = "select count(*) as remarksCount from RequestStatus_tbl where RequestID = ".$requestID." and IsActive = 1 and IsDeleted = 0 ";
                            $RemarkCountResult = odbc_exec($connServer, $RemarksCountSql);
                            if($RemarkCountResult){
                                $countRemarksRow = odbc_fetch_array($RemarkCountResult);
                                $countRemarks = $countRemarksRow['remarksCount'];
                                // echo $countRemarks;
                                if($countRemarks > 2){
                                    $output .= '<p>
                                                    <button class="btn btn-outline-primary btn-sm float-end" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                        View more 
                                                    </button>
                                                </p>';
                                }
                            }
                            
                $output .= '<div class="collapse" id="collapseExample">';
                            $sqlAllRemarks = "SELECT rs.*, empLevel.UserLevel,
                                                CASE
                                                    WHEN Remarks IS NULL OR Remarks = '' THEN
                                                        (SELECT StatusTxt FROM Status_tbl WHERE StatusID = rs.StatusID)
                                                    ELSE Remarks
                                                END AS Result,
                                                
                                                CASE
                                                    WHEN TRY_CONVERT(int, rs.EmployeeID) IS NULL THEN
                                                        0
                                                    ELSE TRY_CONVERT(int, rs.EmployeeID)
                                                END AS EmployeeID_new,
                                                
                                                emp.Fname,
                                                emp.Lname
                                                FROM RequestStatus_tbl rs
                                                join EmployeeInfo_tbl info on rs.EmployeeID = info.EmployeeID
                                                join UserLevel_tbl empLevel on info.UserLevelID = empLevel.UserLevelID
                                            CROSS APPLY (
                                                SELECT
                                                    CASE
                                                        WHEN TRY_CONVERT(int, rs.EmployeeID) IS NULL THEN 0
                                                        ELSE TRY_CONVERT(int, rs.EmployeeID)
                                                    END AS EmployeeID_cond
                                            ) AS empCond
                                            CROSS APPLY (
                                                SELECT
                                                    Fname,
                                                    Lname
                                                FROM Employee_tbl
                                                WHERE EmployeeID = empCond.EmployeeID_cond
                                            ) AS emp
                                            WHERE rs.RequestID = ".$requestID." AND StatusID != 1 and RequestStatusID != ".$requestStatusID."
                                                AND rs.IsActive = 1 AND rs.IsDeleted = 0
                                            ORDER BY DateCreated DESC ";
                            $allRemarksResult = odbc_exec($connServer, $sqlAllRemarks);
                            $sqlLatestRemarksAllCount = odbc_num_rows($allRemarksResult);
                            if($sqlLatestRemarksAllCount != 0){
                                while($AllRemarksRow = odbc_fetch_array($allRemarksResult)){
                                    $fname = GetNameInitials($AllRemarksRow['Fname']);
                                    $Lname = GetNameInitials($AllRemarksRow['Lname']);
                                    $nameInitials = $fname.$Lname;
                                    $requestStatusID = $AllRemarksRow['RequestStatusID'];
                                    $concatName = $AllRemarksRow['Fname']." ".$AllRemarksRow['Lname'];
                                    $output .= '<div class="avatar bg-warning me-3">
                                                    <span class="avatar-content">'.$nameInitials.'</span>
                                                </div>
                                                <div class="badge bg-light-primary p-2 mb-3" style="text-align: left;font-weight:bold;font-size:12px;">
                                                    <span style="font-size:11px;font-weight:normal;">'.$concatName.' | '.$AllRemarksRow['UserLevel'].', Thursday 9:03 AM</span> </br>
                                                    <hr style="padding-top: 3px;padding-bottom: 7px;margin: 0;">
                                                    '.$AllRemarksRow['Result'].'
                                                </div>
                                                
                                                </br>';
                                }
                            }

                $output .= ' </div>
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
                                <button type="button" class="btn btn-sm btn-light-secondary"
                                    data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
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

        else if($_POST['action']=="qr"){
            $SystemBtrID = "";
            $dataQRUrl = "";
            $result_result = 0;

            if(isset($_POST['SystemBtrID'])){
                $SystemBtrID = $_POST['SystemBtrID'];
            }

            if(isset($_POST['qrURL'])){
                $dataQRUrl = $_POST['qrURL'];
            }

            $QRname = $SystemBtrID.'.png';
            $subfolder = '../../RequestQRuploads/';

            $sql = "SELECT * from RequestQr_tbl where RequestID = (select RequestID from Request_tbl where RequestSysID = '".$SystemBtrID."' )  and IsActive = 1 and IsDeleted = 0 ";
            $resultSql = odbc_exec($connServer, $sql);
            $count = odbc_num_rows($resultSql);

            if($count==0){
                $query = "INSERT INTO RequestQr_tbl(RequestID, RequestQrName) ";
                $query .="SELECT RequestID, '".$QRname."' FROM  Request_tbl ";
                $query .="WHERE RequestSysID = '".$SystemBtrID."' ";

                $result = odbc_exec($connServer, $query);

                if($result){
                    $result_result = 1;
                    file_put_contents($subfolder.$QRname,file_get_contents($dataQRUrl));
                }
                else{
                    $result_result = 2;
                }
            }

            $requestID_holder = 0;
            $requestSql = "select * from Request_tbl where RequestSysID = '".$SystemBtrID."' ";
            $requestSqlResult = odbc_exec($connServer, $requestSql);
            $rowRequest = odbc_fetch_array($requestSqlResult);
            $requestID_holder = $rowRequest['RequestID'];

            $arr = array(
                'result' => $result_result,
                'imageData' =>$dataQRUrl,
                'requestID' => $requestID_holder
            );
            echo json_encode($arr);
        }

        else if($_POST['action']=="FetchEditDisposal"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID']: 0;
            $sql ="select DisposalID from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            where req.RequestID = ".$requestID;
            $execute = odbc_exec($connServer, $sql);
            $row = odbc_fetch_array($execute);

            $disposalID = $row['DisposalID'];

            echo json_encode($disposalID);
        }

        else if($_POST['action']=="FetchFieldEditDetails"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID']: 0;

            $projectName = '';
            $objective = '';
            $prodCode = '';
            $brand = '';
            $batCode = '';
            $positivePlateQty = 0;
            $negativePlateQty = 0;
            $RC = 0;
            $AH = 0;
            $CCA = 0;
            $C5 = 0;
            $CA = 0;
            $SG = 0;
            $Others = '';

            $sql = "select requi.ProjectName, requi.TestObjective,
            details.ProductionCode, details.BatteryBrand, details.BatteryCode,
            details.PositivePlateQty, details.NegativePlateQty,
            details.RC, details.AH, details.CCA, details.C5, details.CA, details.SG, details.Others
            from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            where req.RequestID = ".$requestID;
            $result = odbc_exec($connServer, $sql);
            if($result){
                $row = odbc_fetch_array($result);

                $projectName ='<span class="badge bg-light-primary">'.$row['ProjectName'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'ProjectName\', \'Project Name\', 0 )"><i class="bi bi-pencil-square"></i></span></span>';

                $objective ='<span class="badge bg-light-primary">'.$row['TestObjective'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'TestObjective\', \'Test Objective\', 0 )"><i class="bi bi-pencil-square"></i></span></span>';

                $prodCode ='<span class="badge bg-light-primary">'.$row['ProductionCode'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'ProductionCode\', \'Production Code\', 0 )"><i class="bi bi-pencil-square"></i></span></span>';

                $brand ='<span class="badge bg-light-primary">'.$row['BatteryBrand'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'BatteryBrand\', \'Brand\', 0 )"><i class="bi bi-pencil-square"></i></span></span>';

                $batCode ='<span class="badge bg-light-primary">'.$row['BatteryCode'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'BatteryCode\', \'Battery Code\', 0 )"><i class="bi bi-pencil-square"></i></span></span>';

                $positivePlateQty ='<span class="badge bg-light-primary">'.$row['PositivePlateQty'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'PositivePlateQty\', \'Positive Plate Quantity\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';

                $negativePlateQty ='<span class="badge bg-light-primary">'.$row['NegativePlateQty'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'NegativePlateQty\', \'Negative Plate Quantity\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';

                $RC ='<span class="badge bg-light-primary">'.$row['RC'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'RC\', \'RC\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';

                $AH ='<span class="badge bg-light-primary">'.$row['AH'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'AH\', \'AH\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';

                $CCA ='<span class="badge bg-light-primary">'.$row['CCA'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'CCA\', \'CCA\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';

                $C5 ='<span class="badge bg-light-primary">'.$row['C5'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'C5\', \'C5\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';

                $CA ='<span class="badge bg-light-primary">'.$row['CA'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'CA\', \'CA\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';

                $SG ='<span class="badge bg-light-primary">'.$row['SG'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'SG\', \'SG\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';

                $Others ='<span class="badge bg-light-primary">'.$row['Others'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeField('.$requestID.', \'Others\', \'Others\', 1 )"><i class="bi bi-pencil-square"></i></span></span>';
            }

            $arr = array(
                'projectName' => $projectName,
                'testObjective' => $objective,
                'productionCode' => $prodCode,
                'batteryBrand' => $brand,
                'batteryCode' => $batCode,
                'positivePlateQty' => $positivePlateQty,
                'negativePlateQty' => $negativePlateQty,
                'RC' => $RC,
                'AH' => $AH,
                'CCA' => $CCA,
                'C5' => $C5,
                'CA' => $CA,
                'SG' => $SG,
                'others' => $Others
            );

            echo json_encode($arr);
        }

        else if($_POST['action']=="SaveEditField"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $fieldTxt = isset($_POST['fieldTxt']) ? $_POST['fieldTxt'] : 0;
            $value = isset($_POST['value']) ? $_POST['value'] : 0;
            $output = 0;
            if($fieldTxt=='ProjectName' || $fieldTxt=='TestObjective'){
                $sql = "select RequisitionID from Request_tbl where RequestID = ".$requestID;
                $result = odbc_exec($connServer, $sql);
                if($result){
                    $row = odbc_fetch_array($result);
                    $requisitionID = $row['RequisitionID'];
                    $data = array($value, $requisitionID);
                    $query = "update Requisition_tbl set ".$fieldTxt." = ? where RequisitionID = ? ";
                    $stmt = odbc_prepare($connServer, $query);
                    $execute = odbc_execute($stmt, $data);
                    if($execute){
                        $output = 1;
                    }
                }
            }
            else{
                $sql = "
                select requi.RequisitionID from Request_tbl req
                join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
                join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
                where req.RequestID = ".$requestID ;
                $result = odbc_exec($connServer, $sql);
                if($result){
                    $row = odbc_fetch_array($result);
                    $requisitionID = $row['RequisitionID'];
                    $data = array($value, $requisitionID);
                    $query = "update BatteryDetails_tbl set ". $fieldTxt." = ? where RequisitionID = ? ";
                    $stmt = odbc_prepare($connServer, $query);
                    $execute = odbc_execute($stmt, $data);

                    if($execute){
                        $output = 1;
                    }
                }
            }
            echo  json_encode($output);
        }

        else if($_POST['action']=="FetchEditRequestor"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID']: 0;
            $output = '';
            $sql ="select requestor.RequestorID, requestor.RequisitionID, emp.EmployeeID, concat(emp.Fname,' ',emp.Lname) as 'EmployeeName' from Requestor_tbl requestor
            join Employee_tbl emp ON requestor.EmployeeID = emp.EmployeeID
            join Request_tbl request ON requestor.RequisitionID = request.RequisitionID
            where requestor.IsActive = 1 and requestor.IsDeleted = 0 and request.RequestID = ".$requestID;
            $execute = odbc_exec($connServer, $sql);

            while($row = odbc_fetch_array($execute)){
                $output .= '<span class="badge bg-light-primary">'.$row['EmployeeName'].' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="RemoveRequestor('.$row['RequestorID'].', '.$requestID.')">X</span></span>';
            }

            $output .= '<span class="badge rounded-pill bg-light-primary" onclick="AddRequestorModal('.$requestID.')"><i class="bi bi-plus"></i></span>';

            echo json_encode($output);
        }

        else if($_POST['action']=="RemoveRequestor"){
            $requestoID = isset($_POST['requestorID']) ? $_POST['requestorID']: 0;
            $result = 0;
            $sql = "update Requestor_tbl set IsActive = 0, IsDeleted = 1, DateModified = getdate() where RequestorID = ".$requestoID;
            $execute = odbc_exec($connServer, $sql);

            if($execute){
                $result = 1;
            }

            echo  json_encode($result);
        }

        else if($_POST['action']=="fetchRequestorEditData"){
            $deptID = $_COOKIE['BTL_DepartmentID'];
            $sectionID = $_COOKIE['BTL_SectionID'];
            
            $query = "SELECT emp.EmployeeID, concat(emp.Fname,' ',emp.Lname) as 'Employee Name' ";
            $query .="FROM EmployeeInfo_tbl empInfo ";
            $query .="join Employee_tbl emp ON empInfo.EmployeeID = emp.EmployeeID ";
            $query .="join Section_tbl section ON empInfo.SectionID = section.SectionID ";
            $query .="join Department_tbl dept ON empInfo.DepartmentID = dept.DepartmentID ";
            $query .="WHERE dept.DepartmentID = ".$deptID." and section.SectionID = ".$sectionID." ";
            $query .="and dept.IsActive = 1 and dept.IsDeleted = 0 ";
            $query .="and emp.IsActive = 1 and emp.IsDeleted = 0 ";
            $query .="and section.IsActive = 1 and section.IsDeleted = 0 ";
            $query .="and empInfo.IsActive = 1 and empInfo.IsDeleted = 0 ";
            // $query .="and emp.EmployeeID != ".$_COOKIE['BTL_employeeID']." ";

            $fetch = odbc_exec($connServer, $query);
            $count = odbc_num_rows($fetch) & 0xffffffff;

            if($fetch){
                $output ='';
                while($row = odbc_fetch_array($fetch)){

                    $Employee_Id     = $row['EmployeeID'];
                    $EmployeeName   = $row['Employee Name'];

                    $output .='<div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        class="form-check-input2 form-check-primary form-check-glow"
                                        value="'.$Employee_Id.'">
                                    <label class="form-check-label" for="customColorCheck1">'.$EmployeeName.'</label>
                                </div>';
                }
                echo json_encode($output);
            }
        }

        else if($_POST['action']=="EditAddSelectedRequestor"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID']:0;
            $checkedValues = isset($_POST['checkedValues']) ? $_POST['checkedValues']:0;

            $count = count($checkedValues);
            $output = 0;
            $requisitionID = 0;

            $sql = "select RequisitionID from Request_tbl where RequestID = ".$requestID;
            $result = odbc_exec($connServer, $sql);
            $row = odbc_fetch_array($result);
            $requisitionID = $row['RequisitionID'];

            if($count!=0){
                $output = 1;
                for($i=0; $i < $count; $i++){
                    $EmployeeID = $checkedValues[$i];
                    $data = array($requisitionID, $EmployeeID);
                    $query = "insert into Requestor_tbl(RequisitionID, EmployeeID) values(?, ?) ";
                    $stmt = odbc_prepare($connServer, $query);
                    $result = odbc_execute($stmt, $data);
                }
            }
            echo json_encode($checkedValues);
        }

        else if($_POST['action']=="FetchClassification"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;

            $sql = "select class.ClassificationTxt from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            join Classification_tbl class ON requi.ClassificationID = class.ClassificationID
            where req.RequestID = ".$requestID;

            $result = odbc_exec($connServer, $sql);
            $row = odbc_fetch_array($result);
            $classification = $row['ClassificationTxt'];

            $output = '<span class="badge bg-light-primary">'.$classification.' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangeClassfication('.$requestID.')"><i class="bi bi-arrow-left-right"></i></span></span>';

            echo json_encode($output);
        }

        else if($_POST['action']=="ChangeClassification"){
            $output = '';
            $sql = "select * from Classification_tbl ";
            $result = odbc_exec($connServer, $sql);
            while($row = odbc_fetch_array($result)){
                if($row['ClassificationTxt'] != 'OTHERS'){
                    $output .= '
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                            class="form-check-input formCheck2 form-check-primary form-check-glow"
                            value="'.$row['ClassificationID'].'">
                        <label class="form-check-label" for="customColorCheck1">'.$row['ClassificationTxt'].'</label>
                    </div>
                    ';
                }
            }

            echo json_encode($output);
        }

        else if($_POST['action']=="SelectedClassification"){
            $classificationID = isset($_POST['checkedValue']) ? $_POST['checkedValue'] : 0;
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $output = 0;

            $sql = "
            select requi.RequisitionID from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join Classification_tbl class ON requi.ClassificationID = class.ClassificationID
            where req.RequestID = ".$requestID ;
            $result = odbc_exec($connServer, $sql);
            if($result){
                $row = odbc_fetch_array($result);
                $requisitionID = $row['RequisitionID'];
                $data = array($classificationID, $requisitionID);
                $query = "update Requisition_tbl set ClassificationID = ? where RequisitionID = ? ";
                $stmt = odbc_prepare($connServer, $query);
                $execute = odbc_execute($stmt, $data);

                if($execute){
                    $output = 1;
                }
            }
            $arr = array(
                'output' => $output,
                'ID' => $requestID
            );
            echo  json_encode($arr);
        }

        else if($_POST['action']=="EditBType"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $output = '';
            $sql = "select requi.RequisitionID, details.BatteryTypeID, btype.BatteryType from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            join BatteryType_tbl btype ON details.BatteryTypeID = btype.BatteryTypeID
            where req.RequestID = ".$requestID;
            $result = odbc_exec($connServer, $sql);
            if($result){
                $row = odbc_fetch_array($result);
                $BattID = $row['BatteryTypeID'];
                $BattType = $row['BatteryType'];

                $output .= '<option selected value="'.$BattID.'">'.$BattType.'</option>';

                $sql2 = "select * from BatteryType_tbl";
                $result2 = odbc_exec($connServer, $sql2);
                
                if($result2){
                    while($row2 = odbc_fetch_array($result2)){
                        $bTypeID = $row2['BatteryTypeID'];
                        $bType = $row2['BatteryType'];

                        if($bTypeID != $BattID){
                            $output .= '<option value="'.$bTypeID.'">'.$bType.'</option>';
                        }
                    }
                }
            }
            echo json_encode($output);
        }

        else if($_POST['action']=="fetchApplication"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $output = '';
            $sql = "select requi.RequisitionID, details.ApplicationID, app.ApplicationTxt from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            join Application_tbl app ON details.ApplicationID = app.ApplicationID
            where req.RequestID = ".$requestID;
            $result = odbc_exec($connServer, $sql);
            if($result){
                $row = odbc_fetch_array($result);
                $ApplicationID = $row['ApplicationID'];
                $Application = $row['ApplicationTxt'];

                $output .= '<option selected value="'.$ApplicationID.'">'.$Application.'</option>';
            }

            echo json_encode($output);
        }

        else if($_POST['action']=="UpdateAndRefetchApplication"){
            $BattTypeID = isset($_POST['BattTypeID']) ? $_POST['BattTypeID'] : 0;
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $output = '';

            $sql = "
            select requi.RequisitionID from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            where req.RequestID = ".$requestID ;
            $result = odbc_exec($connServer, $sql);
            if($result){
                $row = odbc_fetch_array($result);
                $requisitionID = $row['RequisitionID'];
                $data = array($BattTypeID, $requisitionID);
                $query = "update BatteryDetails_tbl set BatteryTypeID = ? where RequisitionID = ? ";
                $stmt = odbc_prepare($connServer, $query);
                $execute = odbc_execute($stmt, $data);

                if($execute){
                    $output = 1;
                    $sql2 = "select * from Application_tbl where BatteryTypeID = ".$BattTypeID;
                    $execute = odbc_exec($connServer, $sql2);
                    if($execute){
                        $output .= '<option value="0">Choose</option>';
                        while($row2 = odbc_fetch_array($execute)){
                            $ApplicationID = $row2['ApplicationID'];
                            $Application = $row2['ApplicationTxt'];

                            $output .= '<option value="'.$ApplicationID.'">'.$Application.'</option>';
                        }
                    }
                }
            }
            $arr = array(
                'output' => $output,
                'ID' => $requestID
            );
            echo  json_encode($arr);
        }

        else if($_POST['action']=="fetchBatterySizesData"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $output = '';
            $sql = "select sizes.BatterySize, sizes.BatterySizeID from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            join BatterySizes_tbl sizes ON details.BatterySizeID = sizes.BatterySizeID
            where req.RequestID = ".$requestID;
            $result = odbc_exec($connServer, $sql);
            if($result){
                $row = odbc_fetch_array($result);
                $batterySizeID = $row['BatterySizeID'];
                $batterySize = $row['BatterySize'];

                $output .= '<option selected value="'.$batterySizeID.'">'.$batterySize.'</option>';
            }
            
            echo json_encode($output);
        }

        else if($_POST['action']=="UpdateAndRefetchBattSize"){
            $ApplicationID = isset($_POST['ApplicationID']) ? $_POST['ApplicationID'] : 0;
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $output = '';

            $sql = "
            select requi.RequisitionID, details.ApplicationID, app.ApplicationTxt from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            join Application_tbl app ON details.ApplicationID = app.ApplicationID
            where req.RequestID = ".$requestID;

            $result = odbc_exec($connServer, $sql);
            if($result){
                $row = odbc_fetch_array($result);
                $requisitionID = $row['RequisitionID'];
                $ApplicationTxt = $row['ApplicationTxt'];
                $data = array($ApplicationID, $requisitionID);
                $query = "update BatteryDetails_tbl set ApplicationID = ? where RequisitionID = ? ";
                $stmt = odbc_prepare($connServer, $query);
                $execute = odbc_execute($stmt, $data);

                if($execute){
                    $output = 1;
                    $sql2 = "select * from BatterySizes_tbl where ApplicationTxt = '".$ApplicationTxt."' ";

                    $execute = odbc_exec($connServer, $sql2);
                    if($execute){
                        $output .= '<option value="0">Choose</option>';
                        while($row2 = odbc_fetch_array($execute)){
                            $BatterySizeID = $row2['BatterySizeID'];
                            $BatterySize = $row2['BatterySize'];
                            $output .= '<option value="'.$BatterySizeID.'">'.$BatterySize.'</option>';
                        }
                    }
                }
            }
            $arr = array(
                'output' => $output,
                'ID' => $requestID
            );
            echo  json_encode($arr);
        }

        else if($_POST['action']=="EditBatterySize"){
            $BatterySizeID = isset($_POST['BatterySizeID']) ? $_POST['BatterySizeID'] : 0;
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $result = 0;
            $sql = "select requi.RequisitionID from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            where req.RequestID = ".$requestID;
            $result = odbc_exec($connServer, $sql);

            if($result){
                $row = odbc_fetch_array($result);
                $requisitionID = $row['RequisitionID'];
                $data = array($BatterySizeID, $requisitionID);
                $query = "update BatteryDetails_tbl set BatterySizeID = ? where RequisitionID = ? ";
                $stmt = odbc_prepare($connServer, $query);
                $execute = odbc_execute($stmt, $data);
                if($execute){
                    $result = 1;
                }
            }

            echo json_encode($result);
        }

        else if($_POST['action']=="FetchPositivePlate"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;

            $sql = "select details.PositivePlateID, details.PositivePlateOthers, plate.PlateType from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            join PlateType_tbl plate ON details.PositivePlateID = plate.PlateTypeId
            where req.RequestID = ".$requestID;

            $result = odbc_exec($connServer, $sql);
            $row = odbc_fetch_array($result);
            $PlateTypeID = $row['PositivePlateID'];
            $Plate = '';
            if($PlateTypeID!=0){
                $Plate = $row['PlateType'];
            }
            else{
                $Plate = $row['PositivePlateOthers'];
            }

            $output = '<span class="badge bg-light-primary">'.$Plate.' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangePlateType('.$requestID.', 2)"><i class="bi bi-arrow-left-right"></i></span></span>';

            echo json_encode($output);
        }

        else if($_POST['action']=="FetchNegativePlate"){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;

            $sql = "select details.NegativePlateID, details.NegativePlateOthers, plate.PlateType from Request_tbl req
            join Requisition_tbl requi ON req.RequisitionID = requi.RequisitionID
            join BatteryDetails_tbl details ON requi.RequisitionID = details.RequisitionID
            join PlateType_tbl plate ON details.NegativePlateID = plate.PlateTypeId
            where req.RequestID = ".$requestID;

            $result = odbc_exec($connServer, $sql);
            $row = odbc_fetch_array($result);
            $PlateTypeID = $row['NegativePlateID'];
            $Plate = '';
            if($PlateTypeID!=0){
                $Plate = $row['PlateType'];
            }
            else{
                $Plate = $row['NegativePlateOthers'];
            }
            
            $output = '<span class="badge bg-light-primary">'.$Plate.' <span class="badge bg-transparent text-primary" style="cursor:pointer;" onclick="ChangePlateType('.$requestID.', 1)"><i class="bi bi-arrow-left-right"></i></span></span>';

            echo json_encode($output);
        }

        else if($_POST['action']=="ChangePlateType"){
            $polarityID = isset($_POST['polarityID']) ? $_POST['polarityID'] : 0;
            $plateTitle = '';
            if($polarityID==1){
                $plateTitle = 'Select Negative Plate Type';
            }
            else{
                $plateTitle = 'Select Positive Plate Type';
            }
            $output = '';
            $sql = "select * from PlateType_tbl where PolarityID = ".$polarityID;
            $result = odbc_exec($connServer, $sql);
            while($row = odbc_fetch_array($result)){
                $output .= '
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                            class="form-check-input formCheck3 form-check-primary form-check-glow"
                            value="'.$row['PlateTypeId'].'">
                        <label class="form-check-label" for="customColorCheck1">'.$row['PlateType'].'</label>
                    </div>
                    ';
            }
            $arr = array(
                'Title' => $plateTitle,
                'output' => $output
            );
            echo json_encode($arr);
        }

        else if($_POST['action']=="SelectedPlateType"){
            $PlateTypeID = isset($_POST['checkedValue']) ? $_POST['checkedValue'] : 0;
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $PolarityID = isset($_POST['PolarityID']) ? $_POST['PolarityID'] : 0;
            $output = 0;

            $sql = "
            select RequisitionID from Request_tbl where RequestID = ".$requestID ;
            $result = odbc_exec($connServer, $sql);
            if($result){
                $row = odbc_fetch_array($result);
                $requisitionID = $row['RequisitionID'];
                $data = array($PlateTypeID, $requisitionID);
                $query = "update BatteryDetails_tbl  ";
                if($PolarityID==1){
                    $query .= "set NegativePlateID = ?  ";
                }
                else{
                    $query .= "set PositivePlateID = ?  ";
                }
                
                $query .= "where RequisitionID = ? ";
                $stmt = odbc_prepare($connServer, $query);
                $execute = odbc_execute($stmt, $data);

                if($execute){
                    $output = 1;
                }
            }
            $arr = array(
                'output' => $output,
                'ID' => $requestID
            );
            echo  json_encode($arr);
        }

        //-------------Submit and update editted request id---
        else if($_POST['action']=='SaveDraftEditRequest'){
            $employeeID = $_COOKIE['BTL_employeeID'];
            $Request_ID = isset($_POST['RequestID']) ? $_POST['RequestID'] : 0;
            $output = 0;

            $testPlanSql = "select * from TestPlan_tbl where RequestID = ".$Request_ID." ";
            $testPlanSql .= "and IsActive = 1 and IsDeleted = 0 ";
            $executeTestPlan = odbc_exec($connServer, $testPlanSql);
            
            if($executeTestPlan){
                $hasTestPlan = odbc_num_rows($executeTestPlan);
                if($hasTestPlan!=0){
                    $remarks = "For Approval";

                    $sql = "Insert into RequestStatus_tbl (RequestID, StatusID, Remarks, EmployeeID) ";
                    $sql.= "values(".$Request_ID.", 2, '".$remarks."', ".$employeeID.") ";
                    
                    $resultInsert = odbc_exec($connServer, $sql);

                    if($resultInsert){
                        $GetCount = 0;
                        $checkIfExist = "if exists(select * from RequestStatus_tbl where RequestID = ".$Request_ID." and    StatusID = 2 and IsActive = 1 and IsDeleted = 0 HAVING COUNT(*) > 1)
                            begin
                                select setter = 1
                            End
                        else
                            begin
                                select setter = 0
                            end";
                        $checkIfExistResult = odbc_exec($connServer, $checkIfExist);
                        $checIfExistRow = odbc_fetch_array($checkIfExistResult);
                        $setter = $checIfExistRow['setter'];

                        if($setter==0){
                            $query = " select count(r.RequestID) as count from Request_tbl r
                            cross apply (select top 1 StatusID from RequestStatus_tbl rq where rq.StatusID = 2 and rq.RequestID = r.RequestID order by rq.DateCreated DESC ) stat ";
                            $queryResult = odbc_exec($connServer, $query);

                            if($queryResult){
                                $dayNo = date("d");
                                $monthNo = date("m");
                                $countResult = odbc_fetch_array($queryResult);

                                if($countResult!=0){
                                    $GetCount = $countResult['count'] + 1;
                                }
                                else{
                                    $GetCount = 1;
                                }

                                $GeneratedID = sprintf('%03d', $GetCount);
                                $val = 'R'.$dayNo.$monthNo.'-'.$GeneratedID.'';

                                $query_update = "update Request_tbl set RequestSysID = '".$val."', DateModified = getdate() ";
                                $query_update.="where RequestID = ".$Request_ID." ";
                                $result = odbc_exec($connServer, $query_update);
                                if($result){
                                    $output = 1;
                                }
                            }
                        }
                        else{
                            $output = 1;
                        } 
                    }
                }
                else{
                    $output = 2;
                }
            }

            echo json_encode($output);
        }
        //-------------Submit and update editted request id end---
        else if($_POST['action']=='DeleteDraftRequest'){
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : 0;
            $output = 0;

            $sql = "update Request_tbl set IsActive = 0, IsDeleted = 1, DateModified = getdate() where RequestID = ".$requestID;
            $result = odbc_exec($connServer, $sql);

            if($result){
                $output = 1;
            }

            echo json_encode($output);
        }
    }

?>