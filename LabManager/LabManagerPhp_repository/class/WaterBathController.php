<?php
    class WaterBathController {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function generateWaterBathOutput($WaterBathID, $cellRange) {
            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $wb1sqlDesc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID DESC";
            $wb1resultDesc = odbc_exec($this->conn, $wb1sqlDesc);
            $wb1CountDesc = odbc_num_rows($wb1resultDesc);

            $selectBathTitle = "SELECT wb.WaterBathNo, c.Circuit ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            $dataDesc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }
                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            while ($dcsRow = odbc_fetch_array($wb1resultDesc)) {
                $value1 = $dcsRow['WaterBathCellNo'];
                $value2 = $dcsRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataDesc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['Circuit'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">Water Bath ' . $selectBathTitleRow['WaterBathNo'] . '</span></br>
                            <span style="font-size:13px;">' . $dataAsc[0]['CellNo'] . ' to ' . $dataDesc[0]['CellNo'] . '</span>
                        </div>';

            $countASC = count($dataAsc);
            $countDESC = count($dataDesc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                                        <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                    </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                        $outputWb1 .= '<div class="cell text-center bg-primary"  style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                        <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                    </div>';
                }
                else{
                        $outputWb1 .= '<div class="cell text-center bg-danger"  style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                        <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                    </div>';
                }
                
                if($dataDesc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cell(' . $dataDesc[$x]['CellID'] . ')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                    </div>';
                        $outputWb1 .= '</div>';
                }
                else if($dataDesc[$x]['statusID']==2){
                    $outputWb1 .= '<div class="cell text-center bg-primary "  style="cursor:pointer;" onclick="cell(' . $dataDesc[$x]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                </div>';
                    $outputWb1 .= '</div>';
                }   
                else{
                    $outputWb1 .= '<div class="cell text-center bg-danger"   style="cursor:pointer;" onclick="cell(' . $dataDesc[$x]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                </div>';
                    $outputWb1 .= '</div>';
                }
                
            }

            return $outputWb1;
        }

        public function generateWaterBathOutputWithBlank($WaterBathID,  $cellRange){
            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $selectBathTitle = "SELECT wb.WaterBathNo, c.Circuit ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['Circuit'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">Water Bath ' . $selectBathTitleRow['WaterBathNo'] . '</span></br>
                            <span style="font-size:13px;">' . $dataAsc[0]['CellNo'] . '</span>
                        </div>';

            $countASC = count($dataAsc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                    $outputWb1 .= '<div class="cell text-center bg-primary" style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                }
                else{
                    $outputWb1 .= '<div class="cell text-center bg-danger" style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                }
                
                $outputWb1 .= '<div class="cell text-center" style="cursor:pointer;" >
                                <span class="text-primary font-bold" style="font-size:13px;"></span>
                            </div>';
                $outputWb1 .= '</div>';
            }

            return $outputWb1;
        }

        public function generateWaterBathOutputRCTandRCN($WaterBathID1, $WaterBathID2, $cellRange){
            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID1
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $selectBathTitle = "SELECT c.CircuitTestDesc, c.CircuitDesc ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID1 ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['CircuitDesc'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['CircuitTestDesc'] . '</span></br>
                            <span style="font-size:13px;"></span>
                        </div>';

            $countASC = count($dataAsc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                                    <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                else{
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cell(' . $dataAsc[$x]['CellID'] . ')">
                                    <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                
                $outputWb1 .= '<div class="cell-bitrode text-center" style="cursor:pointer;" >
                                <span class="text-primary font-bold" style="font-size:13px;"></span>
                            </div>';
                $outputWb1 .= '</div>';
            }

            $outputWb1 .= '<div class="row">
                                <div class="cell-divider text-center">
                                    <span class="text-primary font-bold" style="font-size:13px;"></span>
                                </div>
                                <div class="cell-divider text-center">
                                    <span class="text-primary font-bold" style="font-size:13px;"></span>
                                </div>
                            </div>';

            $wb1sqlAsc2 = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID2
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc2 = odbc_exec($this->conn, $wb1sqlAsc2);
            $wb1CountAsc2 = odbc_num_rows($wb1resultAsc2);

            $wb1sqlDesc2 = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID2
                        ORDER BY WaterBathCellNoID DESC";
            $wb1resultDesc2 = odbc_exec($this->conn, $wb1sqlDesc2);
            $wb1CountDesc2 = odbc_num_rows($wb1resultDesc2);

            $dataAsc2 = array();
            $dataDesc2 = array();
            while ($ascRow2 = odbc_fetch_array($wb1resultAsc2)) {
                $value1 = $ascRow2['WaterBathCellNo'];
                $value2 = $ascRow2['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc2, $data);
            }

            while ($dcsRow2 = odbc_fetch_array($wb1resultDesc2)) {
                $value1 = $dcsRow2['WaterBathCellNo'];
                $value2 = $dcsRow2['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataDesc2, $data);
            }

            $countASC2 = count($dataAsc);

            for ($y = 0; $y < $countASC2; $y++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc2[$y]['statusID']==1){
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cell(' . $dataAsc2[$y]['CellID'] . ')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc2[$y]['statusID']==2){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cell(' . $dataAsc2[$y]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                            </div>';
                }
                else{
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cell(' . $dataAsc2[$y]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                            </div>';
                }
                
                if($dataDesc2[$y]['statusID']==1){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cell(' . $dataDesc2[$y]['CellID'] . ')">
                                <span class="text-primary font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                    $outputWb1 .= '</div>';
                }
                else if($dataDesc2[$y]['statusID']==2){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cell(' . $dataDesc2[$y]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                    $outputWb1 .= '</div>';
                }
                else {
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cell(' . $dataDesc2[$y]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                    $outputWb1 .= '</div>';
                }
                
            }

            return $outputWb1;
        }

        public function generateSampleLocationBathOutput($WaterBathID, $cellRange, $WaterBAthCellNoID, $TestSampleID){

            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $wb1sqlDesc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID DESC";
            $wb1resultDesc = odbc_exec($this->conn, $wb1sqlDesc);
            $wb1CountDesc = odbc_num_rows($wb1resultDesc);

            $selectBathTitle = "SELECT wb.WaterBathNo, c.Circuit ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            $dataDesc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }
                
                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            while ($dcsRow = odbc_fetch_array($wb1resultDesc)) {
                $value1 = $dcsRow['WaterBathCellNo'];
                $value2 = $dcsRow['WaterBathCellNoID'];
                $value = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataDesc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['Circuit'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">Water Bath ' . $selectBathTitleRow['WaterBathNo'] . '</span></br>
                            <span style="font-size:13px;">' . $dataAsc[0]['CellNo'] . ' to ' . $dataDesc[0]['CellNo'] . '</span>
                        </div>';

            $countASC = count($dataAsc);
            $countDESC = count($dataDesc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                        <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                    </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                        if($dataAsc[$x]['CellID'] == $WaterBAthCellNoID){
                            $outputWb1 .= '<div class="cell text-center bg-success"  style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                        }
                        else{
                            $outputWb1 .= '<div class="cell text-center bg-primary"  style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                        }
                        
                }
                else{
                        $outputWb1 .= '<div class="cell text-center bg-danger"  style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                        <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                    </div>';
                }

                
                if($dataDesc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cellTransfer(' . $dataDesc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                    </div>';
                        $outputWb1 .= '</div>';
                }
                else if($dataDesc[$x]['statusID']==2){
                    if($dataDesc[$x]['CellID'] == $WaterBAthCellNoID){
                        $outputWb1 .= '<div class="cell text-center bg-success "  style="cursor:pointer;" onclick="cellTransfer(' . $dataDesc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                </div>';
                        $outputWb1 .= '</div>';
                    }
                    else{
                        $outputWb1 .= '<div class="cell text-center bg-primary "  style="cursor:pointer;" onclick="cellTransfer(' . $dataDesc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                </div>';
                        $outputWb1 .= '</div>';
                    }
                    
                }   
                else{
                    $outputWb1 .= '<div class="cell text-center bg-danger"   style="cursor:pointer;" onclick="cellTransfer(' . $dataDesc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                </div>';
                    $outputWb1 .= '</div>';
                }
                
            }
            return $outputWb1;
        }

        public function generateSampleLocationBathWithBlank($WaterBathID, $cellRange, $WaterBAthCellNoID, $TestSampleID){
            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $selectBathTitle = "SELECT wb.WaterBathNo, c.Circuit ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['Circuit'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">Water Bath ' . $selectBathTitleRow['WaterBathNo'] . '</span></br>
                            <span style="font-size:13px;">' . $dataAsc[0]['CellNo'] . '</span>
                        </div>';

            $countASC = count($dataAsc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                    if($dataAsc[$x]['CellID'] == $WaterBAthCellNoID){
                        $outputWb1 .= '<div class="cell text-center bg-success" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                    }
                    else{
                        $outputWb1 .= '<div class="cell text-center bg-primary" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                    }
                    
                }
                else{
                    $outputWb1 .= '<div class="cell text-center bg-danger" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                }
                
                $outputWb1 .= '<div class="cell text-center" style="cursor:pointer;" >
                                <span class="text-primary font-bold" style="font-size:13px;"></span>
                            </div>';
                $outputWb1 .= '</div>';
            }

            return $outputWb1;
        }

        public function generateSampleLocationBathOutputRCTandRCN($WaterBathID1, $WaterBathID2, $cellRange, $WaterBAthCellNoID, $TestSampleID){
            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID1
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $selectBathTitle = "SELECT c.CircuitTestDesc, c.CircuitDesc ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID1 ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['CircuitDesc'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['CircuitTestDesc'] . '</span></br>
                            <span style="font-size:13px;"></span>
                        </div>';

            $countASC = count($dataAsc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                    if($dataAsc[$x]['CellID'] == $WaterBAthCellNoID){
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-success" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                    <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                    }
                    else{
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                    <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                    }
                    
                }
                else{
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc[$x]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                    <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                
                $outputWb1 .= '<div class="cell-bitrode text-center" style="cursor:pointer;" >
                                <span class="text-primary font-bold" style="font-size:13px;"></span>
                            </div>';
                $outputWb1 .= '</div>';
            }

            $outputWb1 .= '<div class="row">
                                <div class="cell-divider text-center">
                                    <span class="text-primary font-bold" style="font-size:13px;"></span>
                                </div>
                                <div class="cell-divider text-center">
                                    <span class="text-primary font-bold" style="font-size:13px;"></span>
                                </div>
                            </div>';

            $wb1sqlAsc2 = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID2
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc2 = odbc_exec($this->conn, $wb1sqlAsc2);
            $wb1CountAsc2 = odbc_num_rows($wb1resultAsc2);

            $wb1sqlDesc2 = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID2
                        ORDER BY WaterBathCellNoID DESC";
            $wb1resultDesc2 = odbc_exec($this->conn, $wb1sqlDesc2);
            $wb1CountDesc2 = odbc_num_rows($wb1resultDesc2);

            $dataAsc2 = array();
            $dataDesc2 = array();
            while ($ascRow2 = odbc_fetch_array($wb1resultAsc2)) {
                $value1 = $ascRow2['WaterBathCellNo'];
                $value2 = $ascRow2['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc2, $data);
            }

            while ($dcsRow2 = odbc_fetch_array($wb1resultDesc2)) {
                $value1 = $dcsRow2['WaterBathCellNo'];
                $value2 = $dcsRow2['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataDesc2, $data);
            }

            $countASC2 = count($dataAsc);

            for ($y = 0; $y < $countASC2; $y++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc2[$y]['statusID']==1){
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc2[$y]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc2[$y]['statusID']==2){
                    if($dataAsc2[$y]['CellID'] == $WaterBAthCellNoID){
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-success" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc2[$y]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                            </div>';
                    }
                    else{
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc2[$y]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                            </div>';
                    }
                    
                }
                else{
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cellTransfer(' . $dataAsc2[$y]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                            </div>';
                }
                
                if($dataDesc2[$y]['statusID']==1){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cellTransfer(' . $dataDesc2[$y]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-primary font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                    $outputWb1 .= '</div>';
                }
                else if($dataDesc2[$y]['statusID']==2){
                    if($dataDesc2[$y]['CellID'] == $WaterBAthCellNoID){
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-success" style="cursor:pointer;" onclick="cellTransfer(' . $dataDesc2[$y]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                        $outputWb1 .= '</div>';
                    }
                    else{
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cellTransfer(' . $dataDesc2[$y]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                        $outputWb1 .= '</div>';
                    }
                    
                }
                else {
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cellTransfer(' . $dataDesc2[$y]['CellID'] . ', '.$WaterBAthCellNoID.', '.$TestSampleID.')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                    $outputWb1 .= '</div>';
                }
                
            }

            return $outputWb1;
        }


        //---------------Mapping waterbaths function-----------
        public function generateWaterBathOutputMapping($WaterBathID, $cellRange) {
            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $wb1sqlDesc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID DESC";
            $wb1resultDesc = odbc_exec($this->conn, $wb1sqlDesc);
            $wb1CountDesc = odbc_num_rows($wb1resultDesc);

            $selectBathTitle = "SELECT wb.WaterBathNo, c.Circuit ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            $dataDesc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }
                
                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            while ($dcsRow = odbc_fetch_array($wb1resultDesc)) {
                $value1 = $dcsRow['WaterBathCellNo'];
                $value2 = $dcsRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataDesc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['Circuit'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">Water Bath ' . $selectBathTitleRow['WaterBathNo'] . '</span></br>
                            <span style="font-size:13px;">' . $dataAsc[0]['CellNo'] . ' to ' . $dataDesc[0]['CellNo'] . '</span>
                        </div>';

            $countASC = count($dataAsc);
            $countDESC = count($dataDesc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                                        <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                    </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                        $outputWb1 .= '<div class="cell text-center bg-primary"  style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                        <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                    </div>';
                }
                else{
                        $outputWb1 .= '<div class="cell text-center bg-danger"  style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                        <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                    </div>';
                }
                
                if($dataDesc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cellMap(' . $dataDesc[$x]['CellID'] . ')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                    </div>';
                        $outputWb1 .= '</div>';
                }
                else if($dataDesc[$x]['statusID']==2){
                    $outputWb1 .= '<div class="cell text-center bg-primary "  style="cursor:pointer;" onclick="cellMap(' . $dataDesc[$x]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                </div>';
                    $outputWb1 .= '</div>';
                }   
                else{
                    $outputWb1 .= '<div class="cell text-center bg-danger"   style="cursor:pointer;" onclick="cellMap(' . $dataDesc[$x]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc[$x]['CellNo'] . '</span>
                                </div>';
                    $outputWb1 .= '</div>';
                }
                
            }

            return $outputWb1;
        }

        public function generateWaterBathOutputWithBlankMapping($WaterBathID,  $cellRange){
            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $selectBathTitle = "SELECT wb.WaterBathNo, c.Circuit ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['Circuit'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">Water Bath ' . $selectBathTitleRow['WaterBathNo'] . '</span></br>
                            <span style="font-size:13px;">' . $dataAsc[0]['CellNo'] . '</span>
                        </div>';

            $countASC = count($dataAsc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                        $outputWb1 .= '<div class="cell text-center bg-light" style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                    $outputWb1 .= '<div class="cell text-center bg-primary" style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                }
                else{
                    $outputWb1 .= '<div class="cell text-center bg-danger" style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                            </div>';
                }
                
                $outputWb1 .= '<div class="cell text-center" style="cursor:pointer;" >
                                <span class="text-primary font-bold" style="font-size:13px;"></span>
                            </div>';
                $outputWb1 .= '</div>';
            }

            return $outputWb1;
        }

        public function generateWaterBathOutputRCTandRCNMapping($WaterBathID1, $WaterBathID2, $cellRange){
            $wb1sqlAsc = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID1
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc = odbc_exec($this->conn, $wb1sqlAsc);
            $wb1CountAsc = odbc_num_rows($wb1resultAsc);

            $selectBathTitle = "SELECT c.CircuitTestDesc, c.CircuitDesc ";
            $selectBathTitle .= "FROM WaterBath_tbl wb ";
            $selectBathTitle .= "JOIN Circuit_tbl c ON wb.CircuitID = c.CircuitID ";
            $selectBathTitle .= "WHERE c.IsActive = 1 and c.IsDeleted = 0 and ";
            $selectBathTitle .= "wb.IsActive = 1 and wb.IsDeleted = 0 and wb.WaterBathID = $WaterBathID1 ";
            $selectBathTitleResult = odbc_exec($this->conn, $selectBathTitle);
            $selectBathTitleRow = odbc_fetch_array($selectBathTitleResult);

            $dataAsc = array();
            while ($ascRow = odbc_fetch_array($wb1resultAsc)) {
                $value1 = $ascRow['WaterBathCellNo'];
                $value2 = $ascRow['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc, $data);
            }

            $outputWb1 = '<div class="container text-center">
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['CircuitDesc'] . '</span></br>
                            <span class="text-prmary font-bold" style="font-size:13px;">' . $selectBathTitleRow['CircuitTestDesc'] . '</span></br>
                            <span style="font-size:13px;"></span>
                        </div>';

            $countASC = count($dataAsc);

            for ($x = 0; $x < $countASC; $x++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc[$x]['statusID']==1){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc[$x]['statusID']==2){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                                    <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                else{
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cellMap(' . $dataAsc[$x]['CellID'] . ')">
                                    <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc[$x]['CellNo'] . '</span>
                                </div>';
                }
                
                $outputWb1 .= '<div class="cell-bitrode text-center" style="cursor:pointer;" >
                                <span class="text-primary font-bold" style="font-size:13px;"></span>
                            </div>';
                $outputWb1 .= '</div>';
            }

            $outputWb1 .= '<div class="row">
                                <div class="cell-divider text-center">
                                    <span class="text-primary font-bold" style="font-size:13px;"></span>
                                </div>
                                <div class="cell-divider text-center">
                                    <span class="text-primary font-bold" style="font-size:13px;"></span>
                                </div>
                            </div>';

            $wb1sqlAsc2 = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID 
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID2
                        ORDER BY WaterBathCellNoID ASC";
            $wb1resultAsc2 = odbc_exec($this->conn, $wb1sqlAsc2);
            $wb1CountAsc2 = odbc_num_rows($wb1resultAsc2);

            $wb1sqlDesc2 = "SELECT TOP $cellRange WaterBathCellNo, WaterBathCellNoID
                        FROM WaterBathCellNo_tbl
                        WHERE WaterBathID = $WaterBathID2
                        ORDER BY WaterBathCellNoID DESC";
            $wb1resultDesc2 = odbc_exec($this->conn, $wb1sqlDesc2);
            $wb1CountDesc2 = odbc_num_rows($wb1resultDesc2);

            $dataAsc2 = array();
            $dataDesc2 = array();
            while ($ascRow2 = odbc_fetch_array($wb1resultAsc2)) {
                $value1 = $ascRow2['WaterBathCellNo'];
                $value2 = $ascRow2['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataAsc2, $data);
            }

            while ($dcsRow2 = odbc_fetch_array($wb1resultDesc2)) {
                $value1 = $dcsRow2['WaterBathCellNo'];
                $value2 = $dcsRow2['WaterBathCellNoID'];
                $value3 = 1;
                $sqlStat = "Select top 1 StatCategory.WaterBathCellStatusCategoryID, StatCategory.WaterBathCellStatusCategory
                from WaterBathCellStatus_tbl wbStat
                join WaterBathCellNo_tbl cell2 ON wbstat.WaterBathCellNoID = cell2.WaterBathCellNoID
                join WaterBathCellStatusCategory_tbl StatCategory ON wbstat.WaterBathCellStatusCategoryID = StatCategory.WaterBathCellStatusCategoryID
                where cell2.WaterBathCellNoID = ".$value2."
                order by wbStat.DateCreated DESC ";
                $resultStat = odbc_exec($this->conn, $sqlStat);
                $count = odbc_num_rows($resultStat);
                $rowStat = odbc_fetch_array($resultStat);
                if($count!=0){
                    $value3 = $rowStat['WaterBathCellStatusCategoryID'];
                }

                $data = array(
                    'CellNo' => $value1,
                    'CellID' => $value2,
                    'statusID' => $value3
                );

                array_push($dataDesc2, $data);
            }

            $countASC2 = count($dataAsc);

            for ($y = 0; $y < $countASC2; $y++) {
                $outputWb1 .= '<div class="row">';
                if($dataAsc2[$y]['statusID']==1){
                        $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cellMap(' . $dataAsc2[$y]['CellID'] . ')">
                                    <span class="text-primary font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                                </div>';
                }
                else if($dataAsc2[$y]['statusID']==2){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cellMap(' . $dataAsc2[$y]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                            </div>';
                }
                else{
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cellMap(' . $dataAsc2[$y]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataAsc2[$y]['CellNo'] . '</span>
                            </div>';
                }
                
                if($dataDesc2[$y]['statusID']==1){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-light" style="cursor:pointer;" onclick="cellMap(' . $dataDesc2[$y]['CellID'] . ')">
                                <span class="text-primary font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                    $outputWb1 .= '</div>';
                }
                else if($dataDesc2[$y]['statusID']==2){
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-primary" style="cursor:pointer;" onclick="cellMap(' . $dataDesc2[$y]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                    $outputWb1 .= '</div>';
                }
                else {
                    $outputWb1 .= '<div class="cell-bitrode text-center bg-danger" style="cursor:pointer;" onclick="cellMap(' . $dataDesc2[$y]['CellID'] . ')">
                                <span class="text-white font-bold" style="font-size:13px;">' . $dataDesc2[$y]['CellNo'] . '</span>
                            </div>';
                    $outputWb1 .= '</div>';
                }
                
            }

            return $outputWb1;
        }
    }

?>