$(document).ready(function () {
    $('#received').slideUp(100)
    $('#testTable').slideUp(100)
    request_tbl(1)
    localStorage.removeItem('hideRowsCheckboxState');
    var checkboxState = localStorage.getItem('hideRowsCheckboxState');
    if (checkboxState === 'checked') {
        // $('#initialMeasureTbl tr:gt(0):lt(2)').addClass('hidden-row');
        $('#initialMeasureTbl tr)').removeClass('hidden-row');
    }
    else{
        $('#initialMeasureTbl tr:gt(1):lt(2)').addClass('hidden-row');
    }

    // Function to handle click on sidebar links
    $('.sidebar-link').on('click', function(e) {
        e.preventDefault(); // Prevent the default behavior of the link

        // Remove 'active' class from all sidebar items
        $('.sidebar-item').removeClass('active');

        // Add 'active' class to the parent li of the clicked link
        $(this).closest('.sidebar-item').addClass('active');
    });

    $('#BtrCard').slideDown(100)
    $('#CirecuitMapCard').slideUp(100)

    setInterval(() => {
        WaterBathStatusCounter()
    }, 5000);

    // $('#SampleLabellingModal').modal('show')
});

function loadContent(id){
    var index = id
     if(index==1){
        $('#BtrCard').slideDown(100)
        $('#CirecuitMapCard').slideUp(100)
     }
     else if(index==2){
        $('#CirecuitMapCard').slideDown(100)
        $('#BtrCard').slideUp(100)
        renderWateBathMapping()
     }
}

$('.btn_summary').on('click', function(){
    var ID = $(this).val()

    if(ID==2 || ID == 1){
        $('#btr').slideDown(100)
        $('#received').slideUp(100)
        $('#testTable').slideUp(100)
        request_tbl(ID)
    }
    else if(ID==3){
        $('#btr').slideUp(100)
        $('#testTable').slideUp(100)
        $('#received').slideDown(100)
        process_tbl(ID)
    }

    else if(ID==4){
        $('#btr').slideUp(100)
        $('#received').slideUp(100)
        $('#testTable').slideDown(100)
        process_tbl(ID)
    }
})

$('.btn_test').on('click', function(){
    var ID = $(this).val()

    alert(ID)
})

const canvas = document.getElementById('canvas');
const camera = document.getElementById('camera');
const decodedDataDiv = document.getElementById('decoded-data');
let scanning = true;
let stream; // Declare a global variable to store the camera stream
 
const scannedQRCodes = new Set();

async function startCamera() {
    try {
        const constraints = {
            video: {
                facingMode: 'environment' // Use the back camera
            }
        };
        // Remove the 'let' keyword here to update the global 'stream' variable
        stream = await navigator.mediaDevices.getUserMedia(constraints);
        camera.srcObject = stream;
    } catch (error) {
        console.error('Error accessing back camera:', error);
    }
}

$('#scan_btn').on('click', function(){
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach((track) => track.stop());
        scanning = true; 
    }

    startCamera();
    $('#scanQR').modal('show');
    
    camera.addEventListener('play', () => {
        const canvasContext = canvas.getContext('2d');
    
        const checkQRCode = async () => {
            if (!scanning) {
                return;
            }
            canvas.width = camera.videoWidth;
            canvas.height = camera.videoHeight;
            canvasContext.drawImage(camera, 0, 0, canvas.width, canvas.height);
            const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);    
    
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                // Check if the QR code is not a duplicate
                decodedDataDiv.textContent = 'QR Code detected: ' + code.data;
                console.log(code.data);
                scanning = false; 
                getValue(code.data)
            }
            
            requestAnimationFrame(checkQRCode);
        };
    
        checkQRCode();
    });
});

$('#closeScannerModal').on('click', function(){
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach((track) => track.stop());
        scanning = true; 
    }

    $('#scanQR').modal('hide');
});

$('#closeScannerModal2').on('click', function(){
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach((track) => track.stop());
        scanning = true; 
    }

    $('#scanQR').modal('hide');
});

function getValue(val){
    var value = val
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach((track) => track.stop());
    }
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'QrReader',
            QRValue:value
        },
        success: function (data) {
            var result = JSON.parse(data.Result)

            if(result ==1){
                $('#scanQR').modal('hide');
                $('#RequestID').val(data.RequestID)
                // $('#RecievingModal').modal('show')
                ViewRequest(data.RequestID)
            }
            else{
                Swal.fire({
                    title: 'QR Scanner',
                    text: 'QR code does not recognize',
                    icon: 'warning',
                    timer: 1000, // Time in milliseconds
                    timerProgressBar: true,
                    showConfirmButton: false
                })
                startCamera()
                scanning = true; 
            }
            
        }
    });
}

function ViewRequest(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'BTR_ViewRequest',
            requestID:requestID
        },
        success: function (data) {
            $('#BTRReceiveViewBody').html(data)
            // ViewBtrButtonControlChange(requestID)
            $('#RecievingModal').modal('show')
        }
    });
}

$('#ReceivedBtn').on('click', function(){
    var RequestID = $('#RequestID_ref').val()
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'Receiving',
            RequestID:RequestID
        },
        success: function (data) {
            var hasReceived = JSON.parse(data.HasReceived)
            if(hasReceived == 0){
                console.log(data.container)
                generateSamplesQR(data.container)
            }
            else{
                Swal.fire({
                    title: 'Received Samples',
                    text: 'Samples already received',
                    icon: 'info',
                    timer: 2000, // Time in milliseconds
                    timerProgressBar: true,
                    showConfirmButton: false
                })
            }
            
        }
    });
})

function ViewBtr(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'BTR_ViewRequest',
            requestID:requestID
        },
        success: function (data) {
            $('#BTRViewBody').html(data)
            ViewBtrButtonControlChange(RequestID)
            $('#ViewBTR').modal('show')
        }
    });
    
}

function ViewBtrButtonControlChange(RequestID){
    var ID = RequestID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'ViewBtrFooterBtnContent',
            ID:ID
        },
        success: function (data) {
            $('#viewBtrFooter').html(data)
        }
    });
}

function generateSamplesQR(samples){
    var data = samples
    var lenght = samples.length
    // alert(lenght)

    for(var i = 0; i < lenght; i ++){
        var TestSampleID = data[i].TestSamplesID
        var TestSampleNo = data[i].TestSamplesNo

        console.log(data)
        console.log(lenght)

        $('#qr').empty();

        $('#qr').qrcode({
            width: 100,
            height: 100,
            text: TestSampleNo
        })

        var canvas = document.querySelector("#qr canvas");
        var dataURL = canvas.toDataURL("image/png");
        // console.log(dataURL);

        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "LabAnalystPhp_repository/index_repo.php",
            data: {
                action:'qr',
                TestSampleID:TestSampleID,
                TestSampleNo:TestSampleNo,
                qrURL:dataURL
            },
            success: function (data) {
                var result = JSON.parse(data.result)
    
                if(result==1){
                    console.log("Insert")
                    console.log(data.imageData)
                }
                else{
                    console.log("Error")
                }
                // $('#my_qr').attr('src',data.imageData);
                // $('#requestID').val(data.requestID)
                // $('#RequestSysID').text(SystemBtrID)
                // $('#QrViewer').modal('show')
                // $('#ViewBTR').modal('hide')
            }
        });
    }

    Swal.fire({
        title: 'Generating Samples Label',
        text: '',
        icon: 'success',
        timer: 3000, // Time in milliseconds
        timerProgressBar: true,
        showConfirmButton: false
    })

    setTimeout(() => {
        displaySample(samples)
    }, 3000);
}

function displaySample(samples){
    var data = samples

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'displaySample',
            data:data
        },
        success: function (data) {
            $('#sample_div').html(data.sample)
            $('#samplesLabelQty').text('TOTAL QUANTITY: '+data.sampleCount)
            $('#RecievingModal').modal('hide')
            $('#SampleLabellingModal').modal('show')
        }
    });
}

function request_tbl(checkID){
    var tabID = checkID

    $('#btr_tbl').DataTable().destroy()
    var dataTable = $('#btr_tbl').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "bSort": true,
        "bInfo":true,
        "searching":true,
        "order" : [],

        "ajax" : {
            url: "LabAnalystPhp_repository/index_repo.php",
            type: "POST",
            data:{
                action:'TestRequest_tbl',
                tabID:tabID
            }
        },

        dom: 'lfrtip',
        buttons: [
            { extend: 'copyHtml5', className: 'btn btn-outline-primary' },
            { extend: 'csvHtml5', className: 'btn btn-outline-warning' },
            { extend: 'excelHtml5', className: 'btn btn-outline-warning' }
            // { extend: 'pdfHtml5', className: 'btn btn-outline-warning' }
            
        ],
        "lengthMenu": [ [5, 15, 30, -1], [5, 15, 30, "All"] ],

        "columnDefs": [
            {
                "targets": 0, // Target the first column (index 0)
                "orderable": true // Enable sorting for the first column
            },
            {
                "targets": 1, // Target the first column (index 0)
                "orderable": false // Enable sorting for the first column
            },
            {
                "targets": 7, // Target the first column (index 0)
                "orderable": false // Enable sorting for the first column
            }
        ]
    })
}

function process_tbl(checkID){
    var tabID = checkID
    if(tabID == 3){
        $('#Ongoing_tbl').DataTable().destroy()
        var dataTable = $('#Ongoing_tbl').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "bSort": true,
            "bInfo":true,
            "searching":true,
            "order" : [],

            "ajax" : {
                url: "LabAnalystPhp_repository/index_repo.php",
                type: "POST",
                data:{
                    action:'process_tbl',
                    tabID:tabID
                }
            },

            dom: 'lfrtip',
            buttons: [
                { extend: 'copyHtml5', className: 'btn btn-outline-warning' },
                { extend: 'csvHtml5', className: 'btn btn-outline-warning' },
                { extend: 'excelHtml5', className: 'btn btn-outline-warning' }
                // { extend: 'pdfHtml5', className: 'btn btn-outline-warning' }
                
            ],
            "lengthMenu": [ [5, 15, 30, -1], [5, 15, 30, "All"] ],

            "columnDefs": [
                {
                    "targets": 3, // Target the first column (index 0)
                    "orderable": false // Enable sorting for the first column
                },
                {
                    "targets": 6, // Target the first column (index 0)
                    "orderable": false // Enable sorting for the first column
                },
                {
                    "targets": 7, // Target the first column (index 0)
                    "orderable": false // Enable sorting for the first column
                },
                {
                    "targets": 8, // Target the first column (index 0)
                    "orderable": false // Enable sorting for the first column
                }
            ],
            
        })
    }
    else if (tabID == 4){
        $('#Testing_tbl').DataTable().destroy()
        var dataTable = $('#Testing_tbl').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "bSort": true,
            "bInfo":true,
            "searching":true,
            "order" : [],

            "ajax" : {
                url: "LabAnalystPhp_repository/index_repo.php",
                type: "POST",
                data:{
                    action:'process_tbl',
                    tabID:tabID
                }
            },

            dom: 'lfrtip',
            buttons: [
                { extend: 'copyHtml5', className: 'btn btn-outline-warning' },
                { extend: 'csvHtml5', className: 'btn btn-outline-warning' },
                { extend: 'excelHtml5', className: 'btn btn-outline-warning' }
                // { extend: 'pdfHtml5', className: 'btn btn-outline-warning' }
                
            ],
            "lengthMenu": [ [5, 15, 30, -1], [5, 15, 30, "All"] ],

            "columnDefs": [
                {
                    "targets": 3, // Target the first column (index 0)
                    "orderable": false // Enable sorting for the first column
                },
                {
                    "targets": 6, // Target the first column (index 0)
                    "orderable": false // Enable sorting for the first column
                },
                {
                    "targets": 7, // Target the first column (index 0)
                    "orderable": false // Enable sorting for the first column
                },
                {
                    "targets": 8, // Target the first column (index 0)
                    "orderable": false // Enable sorting for the first column
                }
            ],
            
        })
    }
    
}

function circuitAllocate(sampleID, sampleNo){
    var testSampleID = sampleID
    var testSampleNo = sampleNo
    // alert(testSampleID)
    WaterBathStatusCounter()
    $('#TestSampleID').val(testSampleID)
    $('#TestSampleNo').val(testSampleNo)
    renderWateBath()
    $('#CircuitAllocation').modal('show')
}

function transferwAllocate(sampleID, sampleNo){
    var SampleID = sampleID
    var SampleNo = sampleNo
    WaterBathStatusCounter()
    renderWateBathTransfer(SampleID)
    $('#CircuitAllocation').modal('show')
}

function renderWateBathTransfer(id){
    var SampleID = id
    $('#transferSampleID').val(SampleID)
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'transferWaterBath',
            SampleID:SampleID
        },
        success: function (data) {
            $('#wb1').html(data.wb1)
            $('#wb2').html(data.wb2)
            $('#wb4').html(data.wb3)
            $('#wb5').html(data.wb4)
            $('#wb6').html(data.wb5)
            $('#wb7').html(data.wb6)
            $('#wb8').html(data.wb7)
            $('#wb10').html(data.wb8)
            $('#wb11').html(data.wb9)
            $('#wb12').html(data.wb10)
            $('#wb13').html(data.wb11)
            $('#wb14').html(data.wb12)
            $('#wb15').html(data.wb13)
            $('#wb16').html(data.wb14)
            $('#wb17').html(data.wb15)
            $('#wb18').html(data.wb16)
            $('#wb19').html(data.wb17)
            $('#wb20').html(data.wb18)
            $('#wb9').html(data.wb19)
            $('#wbRcnRct').html(data.WaterBathRcnRct)
            
            // alert(data.test)
        }
    });
}
var data_holder = [];
function TestPlan(sampleID, testTableId, UserTestID, battTypeID, RequestID){
    var testSampleID = sampleID
    var testTableID = testTableId
    var UserTestStat = UserTestID
    var Batterytype = battTypeID
    var RequestId = RequestID

    // alert(testSampleID, testTableID, UserTestStat, Batterytype, RequestId)
    
    fetchTestPlan(testSampleID, testTableID, UserTestStat, Batterytype, RequestId)
    $('#TestPlanTesting').modal('show')
}

function fetchTestPlan(data1, data2, data3, data4, data5){
    var testSampleID = data1
    var testTableID = data2
    var UserTestStat = data3
    var BatteryType = data4
    var RequestID = data5
    let ganttChart;
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'FetchPTPschedule',
            testSampleID:testSampleID,
            testTableID:testTableID,
            UserTestStat:UserTestStat
        },
        success: function (data) {
            // alert(data)
            if (ganttChart) {
                ganttChart.destroy();
            }
            let tasks = data;
            ganttChart = new Gantt("#gantt", tasks, {
                on_click: function (task) {
                    console.log(task);
                },
                on_date_change: function(task, start, end) {
                    console.log(task, start, end);
                },
                on_progress_change: function(task, progress) {
                    console.log(task, progress);
                }
                // on_view_change: function(mode) {
                //     console.log(mode);
                // }
            });

            $('#TestPlanTesting').on('shown.bs.modal', function () {
                ganttChart.change_view_mode('Day')
                document.querySelector(".chart-controls #day-btn").addEventListener("click", () => {
                    ganttChart.change_view_mode("Day");
                })
                document.querySelector(".chart-controls #week-btn").addEventListener("click", () => {
                    ganttChart.change_view_mode("Week");
                })
                document.querySelector(".chart-controls #month-btn").addEventListener("click", () => {
                    ganttChart.change_view_mode("Month");
                })
            });

            data_holder = data
        }
    });
    if(UserTestStat !=3){
        $('#btnTestChange').addClass('d-none')
    }
    else{
        $('#btnTestChange').removeClass('d-none')
    }
    $('#RequestID_holder').val(RequestID)
    $('#BatteryTypeLMMF').val(BatteryType)
    $('#TestSampleIDTestPlan').val(testSampleID)
    $('#userStat').val(UserTestStat)
    $('#testTableID_holder').val(testTableID)
}

var sortedTestData = [];
$('#btnTestChange').on('click', function(){
    var testSampleID = $('#TestSampleIDTestPlan').val()
    var requestID = $('#RequestID_holder').val()
    var testTableID = $('#testTableID_holder').val()
    var batteryType = $('#BatteryTypeLMMF').val()
    var userStat = $('#userStat').val()

    $('#TestSampleIDTestPlanEdit').val(testSampleID)
    $('#RequestID_holderEdit').val(requestID)
    $('#testTableID_holderEdit').val(testTableID)
    $('#BatteryTypeLMMFEdit').val(batteryType)
    $('#userStatEdit').val(userStat)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'fetchSelectedTest',
            testSampleID:testSampleID
        },
        success: function (data) {
            $('#SelectedTestDiv').html(data)
        }
    });

    $('ul.inline').sortable({
        update: function(event, ui) {
        //   console.log(JSON.stringify($(this).sortable('serialize')));
        //   sortedTestData = JSON.stringify($(this).sortable('serialize'))
            sortedTestData = []
            $(this).find('li').each(function () {
                var hiddenValue = $(this).find('input[type="hidden"]').val();
                sortedTestData.push(hiddenValue);
            });
        }
    }); 
    $('#EditSelectedTest').modal('show')
})

$('#updateTest').on('click', function(){
    var testSampleID = $('#TestSampleIDTestPlanEdit').val()
    var requestID = $('#RequestID_holderEdit').val()
    var testTableID = $('#testTableID_holderEdit').val()
    var batteryType = $('#BatteryTypeLMMFEdit').val()
    var userStat = $('#userStatEdit').val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'UpdateSelectedTest',
            sortedTestData:sortedTestData,
            testSampleID:testSampleID
        },
        success: function (data) {
            fetchTestPlan(testSampleID, testTableID, userStat, batteryType, requestID)
        }
    });

    console.log(sortedTestData)
    
})

function updateTask(taskId, testSampleID, NewStartDate, NewEndDate, testTableID, UserTestStat){
    // alert(taskId + ' ' + testSampleID+ ' ' + NewStartDate + ' ' + NewEndDate)
    var taskID = taskId
    var sampleID = testSampleID
    var startDate = NewStartDate
    var endDate = NewEndDate
    var tableTestID = testTableID
    var userTestStat = UserTestStat

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'updateTask',
            taskID:taskID,
            sampleID:sampleID,
            startDate:startDate,
            endDate:endDate
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                alert('Updated')
                fetchTestPlan(sampleID, tableTestID, userTestStat)
            }
            else{
                alert('Something went wrong')
            }
        }
    });
}

$('#StartTest').on('click', function(){
    var TestSampleID = $('#TestSampleIDTestPlan').val()
    var BatteryType = $('#BatteryTypeLMMF').val()
    var requestID = $('#RequestID_holder').val()
    var userStatID = $('#userStat').val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'StartTest',
            TestSampleID:TestSampleID,
            BatteryType:BatteryType,
            data_holder:data_holder,
            requestID:requestID,
            userStatID:userStatID
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                process_tbl(3)
                Swal.fire('info', 'Testing Start.', 'info');
                $('#TestPlanTesting').modal('hide')
            }
            else{
                Swal.fire('Warning', 'Please provide samples initial measurement to proceed.', 'warning');
            }
        }
    });
})

function InitialRequirements(sampleID, batteryTypeID){
    var testSampleID = sampleID
    var battTypeID = batteryTypeID
    // alert(testSampleID + ' ' + battTypeID)
    if(battTypeID == 2){
        $('#textTitle').text('INITIAL MEASUREMENTS: DC BATTERY (ACTIVATION)')
        $('#LM_Div').slideDown(100)
        $('#MF_Div').slideUp(100)
    }
    else{
        $('#textTitle').text('INITIAL MEASUREMENTS WET-CHARGED BATTERY')
        $('#LM_Div').slideUp(100)
        $('#MF_Div').slideDown(100)
    }
    $('#InitialMeasurement').on('shown.bs.modal', function () {
        var checkboxState = localStorage.getItem('hideRowsCheckboxState');
        if (checkboxState === 'checked') {
            // $('#initialMeasureTbl tr:gt(0):lt(2)').addClass('hidden-row');
            $('#initialMeasureTbl tr)').removeClass('hidden-row');
        }
        else{
            $('#initialMeasureTbl tr:gt(1):lt(2)').addClass('hidden-row');
        }
    });
    $('#SampleID').val(testSampleID)
    $('#BatteryTypeID').val(battTypeID)
    $('#InitialMeasurement').modal('show')
}

//get all the forms detail from tab 4 Battery testing table, the reference of all function forms
function TestDataInput(ptpScheduleID, sampleID, currentTest, testTableID, formCategoryID, TestSampleSysID, WaterBathCellNoID, statusID, IsHaveRow){
    var ptpScheduleId = ptpScheduleID
    var sampleId = sampleID
    var currentTestTxt = currentTest
    var testTableId = testTableID
    var formCategoryId = formCategoryID
    var formTitle = ''
    var testSampleSysText = TestSampleSysID
    var waterBathCellNoID = WaterBathCellNoID
    var TestStatusID = statusID
    var HaveRow = IsHaveRow
    // alert(HaveRow)
    // Get the current date
    var currentDate = new Date();
    // alert(TestStatusID)
    // Array of month names
    var monthNames = [
      "January", "February", "March",
      "April", "May", "June",
      "July", "August", "September",
      "October", "November", "December"
    ];

    // Format the date as 'Month day, year'
    var formattedDate = monthNames[currentDate.getMonth()] + ' ' + currentDate.getDate() + ', ' + currentDate.getFullYear();
    
    // alert(formCategoryId)

    if(formCategoryId==1){
        formTitle = 'BATTERY CAPACITY'
        CapacityTestForm(ptpScheduleId, sampleId, currentTestTxt, testTableId, formCategoryId, formTitle, testSampleSysText, formattedDate, waterBathCellNoID, TestStatusID, HaveRow)
    }
    else if(formCategoryId==2){
        formTitle = 'HIGH RATE DISCHARGE TEST'
        HRDTTestForm(ptpScheduleId, sampleId, currentTestTxt, testTableId, formCategoryId, formTitle, testSampleSysText, formattedDate, waterBathCellNoID, TestStatusID, HaveRow)
    }
    else if(formCategoryId==4){
        formTitle = 'WATER CONSUMPTION TEST'
        WCTTestForm(sampleId, currentTestTxt, testTableId, formCategoryId, formTitle, testSampleSysText, formattedDate)
    }
    else if(formCategoryId==6){
        formTitle = 'VIBRATION TEST'
        VTTestForm(sampleId, currentTestTxt, testTableId, formCategoryId, formTitle, testSampleSysText, formattedDate)
    }
}
//get all the forms detail from tab 4 Battery testing table, the reference of all function forms end

function EquipmentNo(waterBathCellNoID){
    var CellNoID_raw = waterBathCellNoID
    var CellNoID = setNullToZero(CellNoID_raw)
    var equipment = '';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action: 'equipmentNo',
            CellNoID:CellNoID
        },
        success: function (data) {
            $('#TestDataEquipmentCapacity').val(data.CircuitLocation)
            $('#TestDataEquipmentCapacityID').val(data.WaterBathCellNoID)
        }
    });
}

function CapacityTestForm(ptpScheduleID, sampleID, currentTest, testTableID, formCatID, formTitleText, testSampleTxt, formTestDate, waterBathCellNoID, statusID, IsHaveRow){
    var ptpTestScheduleID = ptpScheduleID
    var sampleId = sampleID
    var currentTestTxt = currentTest
    var testTableId = testTableID
    var formCategoryId = formCatID
    var formTitle = formTitleText
    var testSampleSysText = testSampleTxt
    var currentDate = formTestDate
    var waterBathCellNoId = waterBathCellNoID
    var TestStatusID = statusID
    var HaveRow = IsHaveRow
    
    console.log(sampleId, currentTestTxt, testTableId, formCategoryId, testSampleSysText, TestStatusID)
    $('#formCategoryId').val(formCategoryId)
    $('#sampleId').val(sampleId)
    $('#testTableId').val(testTableId)
    $('#textTestTitle').text('Testing Form: ' + formTitle)
    $('#TestDataBatNo').val(testSampleSysText)
    $('#TestDataTestDate').val(currentDate)
    $('#TestDataTestType').val(currentTestTxt)
    $('#ptpTestScheduleID').val(ptpTestScheduleID)
    $('#TestDataStatusID').val(TestStatusID)
    // $('#TestDataEquipmentCapacity').val(equipment)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'fetchCapacityForm',
            ptpTestScheduleID:ptpTestScheduleID,
            sampleId:sampleId,
            currentTestTxt:currentTestTxt,
            testTableId:testTableId,
            formCategoryId:formCategoryId,
            testSampleSysText:testSampleSysText,
            currentDate:currentDate,
            waterBathCellNoId:waterBathCellNoId,
            TestStatusID:TestStatusID,
            HaveRow:HaveRow
        },
        success: function (data) {
            $('.testFormDivCapacity').html(data.content)
            $('#CapacityActionBtn').html(data.actionBtn)
            EquipmentNo(waterBathCellNoId)
            // process_tbl(4)
            $('#TestDataInputFormCapacity').modal('show')
        }
    });
    
}

function SubmitCapacity(){
    var formCatId = $('#formCategoryId').val()
    var ptpScheduleID = $('#ptpTestScheduleID').val()
    var TestStatusID = $('#TestDataStatusID').val()
    var sampleID = $('#sampleId').val()
    var testTableID = $('#testTableId').val()
    var cycleNo = $('#TestDataCycleNoData').val()
    var dataFileName = $('#CapacityDataFileName').val()

    var waterBathCellNoID = $('#TestDataEquipmentCapacityID').val()
    var dischargeCurrent = $('#TestDataDischargeA').val()
    var cuttOffVoltage = $('#TestDataCutOffV').val()

    var ocv = $('#PreCapacityOCV').val()
    var dischargeTimeMins = $('#PostCapacityDischargeTime').val()
    var cca = $('#CapacityCCA').val()
    var PostCapacity1SG1 = $('#Capacity1SG1').val()
    var PostCapacity2SG2 = $('#Capacity1SG2').val()
    var PreCapacity1SG1 = $('#Capacity2SG1').val()
    var PreCapacity2SG2 = $('#Capacity2SG2').val()
    var CapacityRemarks = $('#CapacityFormRemarks').val()
    // console.log(formCatId, sampleID, testTableID, cycleNo, dataFileName, waterBathCellNoID, dischargeCurrent, cuttOffVoltage, ocv, dischargeTimeMins, cca, PostCapacity1SG1, PostCapacity2SG2, PreCapacity1SG1, PreCapacity2SG2)

    var dataForm = new FormData();
    dataForm.append('action', 'submitCapcityForm')
    dataForm.append('formaCatID', formCatId)
    dataForm.append('ptpTestScheduleID', ptpScheduleID)
    dataForm.append('sampleID', sampleID)
    dataForm.append('testTableID', testTableID)
    dataForm.append('cycleNo', cycleNo)
    dataForm.append('dataFileName', dataFileName)
    dataForm.append('waterBathCellNoID', waterBathCellNoID)
    dataForm.append('dischargeCurrent', dischargeCurrent)
    dataForm.append('cuttOffVoltage', cuttOffVoltage)
    dataForm.append('ocv', ocv)
    dataForm.append('dischargeTimeMins', dischargeTimeMins)
    dataForm.append('cca', cca)
    dataForm.append('PostCapacity1SG1', PostCapacity1SG1)
    dataForm.append('PostCapacity2SG2', PostCapacity2SG2)
    dataForm.append('PreCapacity1SG1', PreCapacity1SG1)
    dataForm.append('PreCapacity2SG2', PreCapacity2SG2)
    dataForm.append('CapacityRemarks', CapacityRemarks)

    // Log FormData entries
    var IsProceed = true;
    for (let entry of dataForm.entries()) {
        console.log(entry[0] + ':', entry[1]);
        if(entry[1] === null || entry[1] === 0 || entry[1].trim() === ""){
            Swal.fire('Data Validation', 'Field cannot be empty, please put n/a if not applicable.', 'warning');
            IsProceed = false;
            break;
        }
    }

    if(IsProceed){
        Swal.fire({
            title: 'Confirmation',
            text: 'Do you want to submit this Test Data?',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Proceed',
          }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        url: "LabAnalystPhp_repository/index_repo.php",
                        data: dataForm,
                        success: function (data) {
                            var result = JSON.parse(data)
                            if(result==1){
                                Swal.fire('success', 'Data has been saved.', 'success');
                                $('#TestDataInputFormCapacity').modal('hide')
                            }
                            else{
                                Swal.fire('error', 'something went wrong.', 'error');
                            }
                            process_tbl(4)
                        }
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {

                }
         });   
    }
}

function ReviewCapacityBtn(testDataInputID, ptpScheduleID){
    var testDataInputId = testDataInputID
    var ptpTestScheduleID = ptpScheduleID
    var CapacityRemarks = $('#CapacityFormRemarks').val()
    
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'submitReviewedCapacityform',
            testDataInputId:testDataInputId,
            CapacityRemarks:CapacityRemarks,
            ptpTestScheduleID:ptpTestScheduleID
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire('info', 'Test Sample Reviewed.', 'info');
            }
            else{
                alert('something went wrong')
            }
            process_tbl(4)
            $('#TestDataInputFormCapacity').modal('hide')
        }
    });
}

function RejectCapacityStatBtn(testDataInputID, ptpScheduleID,  formCategoryID){
    var testDataInputId = testDataInputID
    var ptpTestScheduleID = ptpScheduleID
    var formCategoryId = formCategoryID
    var CapacityRemarks = $('#CapacityFormRemarks').val()
    
    $('#RejectTestDataInputID').val(testDataInputId)
    $('#RejectPTPTestDataInputID').val(ptpTestScheduleID)
    $('#RejectFormCategoryID').val(formCategoryId)
    $('#RejectRemarks').val()
    $('#RejectModal').modal('show')
}

$('#Select_Reject_Retest').on('click', function(){
    var TestDataInputID =  $('#RejectTestDataInputID').val()
    var PtpTestScheduleID = $('#RejectPTPTestDataInputID').val()
    var formCategoryID = $('#RejectFormCategoryID').val()
    var RetestRemarks = $('#RejectRemarks').val()
    
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'submitRetestStatCapacityform',
            testDataInputId:TestDataInputID,
            PtpTestScheduleID:PtpTestScheduleID,
            formCategoryID:formCategoryID,
            CapacityRemarks:RetestRemarks
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire('info', 'Sample Retest.', 'info');
            }
            else{
                Swal.fire('error', 'something went wrong.', 'error');
            }
            process_tbl(4)
            $('#RejectModal').modal('hide')
            if(formCategoryID==1){
                $('#TestDataInputFormCapacity').modal('hide')
            }
            else if(formCategoryID==2){
                $('#TestDataInputFormHRDT').modal('hide')
            }
        }
    });
})

function RetestCapacityBtn(testDataInputID){
    var testDataInputId = testDataInputID

    var formCatId = $('#formCategoryId').val()
    var ptpScheduleID = $('#ptpTestScheduleID').val()
    var TestStatusID = $('#TestDataStatusID').val()
    var sampleID = $('#sampleId').val()
    var testTableID = $('#testTableId').val()
    var cycleNo = $('#TestDataCycleNoData').val()
    var dataFileName = $('#CapacityDataFileName').val()

    var waterBathCellNoID = $('#TestDataEquipmentCapacityID').val()
    var dischargeCurrent = $('#TestDataDischargeA').val()
    var cuttOffVoltage = $('#TestDataCutOffV').val()

    var ocv = $('#PreCapacityOCV').val()
    var dischargeTimeMins = $('#PostCapacityDischargeTime').val()
    var cca = $('#CapacityCCA').val()
    var PostCapacity1SG1 = $('#Capacity1SG1').val()
    var PostCapacity2SG2 = $('#Capacity1SG2').val()
    var PreCapacity1SG1 = $('#Capacity2SG1').val()
    var PreCapacity2SG2 = $('#Capacity2SG2').val()
    var CapacityRemarks = $('#CapacityFormRemarks').val()
    // console.log(formCatId, sampleID, testTableID, cycleNo, dataFileName, waterBathCellNoID, dischargeCurrent, cuttOffVoltage, ocv, dischargeTimeMins, cca, PostCapacity1SG1, PostCapacity2SG2, PreCapacity1SG1, PreCapacity2SG2)

    var dataForm = new FormData();
    dataForm.append('action', 'submitRetestCapacityForm')
    dataForm.append('formaCatID', formCatId)
    dataForm.append('testDataInputId', testDataInputId)
    dataForm.append('ptpTestScheduleID', ptpScheduleID)
    dataForm.append('sampleID', sampleID)
    dataForm.append('testTableID', testTableID)
    dataForm.append('cycleNo', cycleNo)
    dataForm.append('dataFileName', dataFileName)
    dataForm.append('waterBathCellNoID', waterBathCellNoID)
    dataForm.append('dischargeCurrent', dischargeCurrent)
    dataForm.append('cuttOffVoltage', cuttOffVoltage)
    dataForm.append('ocv', ocv)
    dataForm.append('dischargeTimeMins', dischargeTimeMins)
    dataForm.append('cca', cca)
    dataForm.append('PostCapacity1SG1', PostCapacity1SG1)
    dataForm.append('PostCapacity2SG2', PostCapacity2SG2)
    dataForm.append('PreCapacity1SG1', PreCapacity1SG1)
    dataForm.append('PreCapacity2SG2', PreCapacity2SG2)
    dataForm.append('CapacityRemarks', CapacityRemarks)

    // Log FormData entries
    // alert(testDataInputId)
    var IsProceed = true;
    for (let entry of dataForm.entries()) {
        console.log(entry[0] + ':', entry[1]);
        if(entry[1] === null || entry[1] === 0 || entry[1].trim() === ""){
            Swal.fire('Data Validation', 'Field cannot be empty, please put n/a if not applicable.', 'warning');
            IsProceed = false;
            break;
        }
    }

    if(IsProceed){
        Swal.fire({
            title: 'Confirmation',
            text: 'Do you want to submit this Retest Data?',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Proceed',
          }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        url: "LabAnalystPhp_repository/index_repo.php",
                        data: dataForm,
                        success: function (data) {
                            var result = JSON.parse(data)
                            if(result==1){
                                Swal.fire('success', 'Data has been saved.', 'success');
                            }
                            else{
                                Swal.fire('error', 'something went wrong.', 'error');
                            }
                            process_tbl(4)
                            $('#TestDataInputFormCapacity').modal('hide')
                        }
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {

                }
         });
        
    }
}

$('#Select_Reject_ChangeData').on('click', function(){
    var TestDataInputID =  $('#RejectTestDataInputID').val()
    var PtpTestScheduleID = $('#RejectPTPTestDataInputID').val()
    var ChangeDataRemarks = $('#RejectRemarks').val()
    var formCategoryID = $('#RejectFormCategoryID').val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'submitChangeDataStatCapacityform',
            TestDataInputID:TestDataInputID,
            PtpTestScheduleID:PtpTestScheduleID,
            ChangeDataRemarks:ChangeDataRemarks
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire('info', 'Sample Test Data Changed.', 'info');
            }
            else{
                Swal.fire('error', 'something went wrong.', 'error');
            }
            process_tbl(4)
            $('#RejectModal').modal('hide')
            if(formCategoryID==1){
                $('#TestDataInputFormCapacity').modal('hide')
            }
            else if(formCategoryID==2){
                $('#TestDataInputFormHRDT').modal('hide')
            }
        }
    });
})

function SubmitChangesCapacityBtn(testDataInputID, ptpScheduleID){
    var testDataInputId = testDataInputID
    var ptpScheduleid = ptpScheduleID

    var formCatId = $('#formCategoryId').val()
    var sampleID = $('#sampleId').val()
    var testTableID = $('#testTableId').val()
    var cycleNo = $('#TestDataCycleNoData').val()
    var dataFileName = $('#CapacityDataFileName').val()

    var waterBathCellNoID = $('#TestDataEquipmentCapacityID').val()
    var dischargeCurrent = $('#TestDataDischargeA').val()
    var cuttOffVoltage = $('#TestDataCutOffV').val()

    var ocv = $('#PreCapacityOCV').val()
    var dischargeTimeMins = $('#PostCapacityDischargeTime').val()
    var cca = $('#CapacityCCA').val()
    var PostCapacity1SG1 = $('#Capacity1SG1').val()
    var PostCapacity2SG2 = $('#Capacity1SG2').val()
    var PreCapacity1SG1 = $('#Capacity2SG1').val()
    var PreCapacity2SG2 = $('#Capacity2SG2').val()
    var CapacityRemarks = $('#CapacityFormRemarks').val()
    // console.log(formCatId, sampleID, testTableID, cycleNo, dataFileName, waterBathCellNoID, dischargeCurrent, cuttOffVoltage, ocv, dischargeTimeMins, cca, PostCapacity1SG1, PostCapacity2SG2, PreCapacity1SG1, PreCapacity2SG2, CapacityRemarks)
    var dataForm = new FormData();
    dataForm.append('action', 'submitChangeDataCapacityForm')
    dataForm.append('formaCatID', formCatId)
    dataForm.append('testDataInputId', testDataInputId)
    dataForm.append('ptpTestScheduleID', ptpScheduleid)
    dataForm.append('sampleID', sampleID)
    dataForm.append('testTableID', testTableID)
    dataForm.append('cycleNo', cycleNo)
    dataForm.append('dataFileName', dataFileName)
    dataForm.append('waterBathCellNoID', waterBathCellNoID)
    dataForm.append('dischargeCurrent', dischargeCurrent)
    dataForm.append('cuttOffVoltage', cuttOffVoltage)
    dataForm.append('ocv', ocv)
    dataForm.append('dischargeTimeMins', dischargeTimeMins)
    dataForm.append('cca', cca)
    dataForm.append('PostCapacity1SG1', PostCapacity1SG1)
    dataForm.append('PostCapacity2SG2', PostCapacity2SG2)
    dataForm.append('PreCapacity1SG1', PreCapacity1SG1)
    dataForm.append('PreCapacity2SG2', PreCapacity2SG2)
    dataForm.append('CapacityRemarks', CapacityRemarks)

    var IsProceed = true;
    for (let entry of dataForm.entries()) {
        console.log(entry[0] + ':', entry[1]);
        if(entry[1] === null || entry[1] === 0 || entry[1].trim() === ""){
            Swal.fire('Data Validation', 'Field cannot be empty, please put n/a if not applicable.', 'warning');
            IsProceed = false;
            break;
        }
    }

    if(IsProceed){
        Swal.fire({
            title: 'Confirmation',
            text: 'Do you want to submit the changed data?',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Proceed',
          }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        url: "LabAnalystPhp_repository/index_repo.php",
                        data: dataForm,
                        success: function (data) {
                            var result = JSON.parse(data)
                            if(result==1){
                                Swal.fire('success', 'Data has been saved.', 'success');
                            }
                            else{
                                Swal.fire('error', 'something went wrong.', 'error');
                            }
                            process_tbl(4)
                            $('#TestDataInputFormCapacity').modal('hide')
                        }
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {

                }
         });
    }
}

function CapacityChangeDataApproval(testDataInputID, ptpScheduleID){
    var testDataInputId = testDataInputID
    var ptpScheduleid = ptpScheduleID
    var CapacityRemarks = $('#CapacityFormRemarks').val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'submitChangeDataStatCapacityform',
            TestDataInputID:testDataInputId,
            PtpTestScheduleID:ptpScheduleid,
            ChangeDataRemarks:CapacityRemarks
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire('info', 'Sample Test Data Changed.', 'info');
            }
            else{
                Swal.fire('error', 'something went wrong.', 'error');
            }
            process_tbl(4)
            $('#TestDataInputFormCapacity').modal('hide')
        }
    });
}

function ApprovalCapacityBtn(testDataInputID, ptpScheduleID){
    var testDataInputId = testDataInputID
    var ptpScheduleid = ptpScheduleID
    var CapacityRemarks = $('#CapacityFormRemarks').val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'submitApprovedCapacityform',
            testDataInputId:testDataInputId,
            CapacityRemarks:CapacityRemarks,
            ptpScheduleid:ptpScheduleid
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire('success', 'Test has been approved.', 'success');
            }
            else{
                Swal.fire('error', 'something went wrong.', 'error');
            }
            process_tbl(4)
            $('#TestDataInputFormCapacity').modal('hide')
        }
    });
}


//HRDT Forms Modal,  Discharge Profile,  and TestResult

function HRDTTestForm(ptpScheduleID, sampleID, currentTest, testTableID, formCatID, formTitleText, testSampleTxt, formTestDate, waterBathCellNoID, statusID, IsHaveRow){
    var ptpTestScheduleID = ptpScheduleID
    var sampleId = sampleID
    var currentTestTxt = currentTest
    var testTableId = testTableID
    var formCategoryId = formCatID
    var formTitle = formTitleText
    var testSampleSysText = testSampleTxt
    var currentDate = formTestDate
    var waterBathCellNoId = waterBathCellNoID
    var TestStatusID = statusID
    var HaveRow = IsHaveRow
    console.log(sampleId, currentTestTxt, testTableId, formCategoryId, testSampleSysText, TestStatusID)
    $('#textTestTitleHRDT').text('Testing Form: ' + formTitle)
    $('#TestDataBatNoHRDT').val(testSampleSysText)
    $('#TestDataTestDateHRDT').val(currentDate)
    $('#TestDataTestTypeHRDT').val(currentTestTxt)
    // $('#TestDataInputFormHRDT').modal('show')
    // alert(HaveRow)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'fetchHRDTForm',
            ptpTestScheduleID:ptpTestScheduleID,
            sampleId:sampleId,
            currentTestTxt:currentTestTxt,
            testTableId:testTableId,
            formCategoryId:formCategoryId,
            testSampleSysText:testSampleSysText,
            currentDate:currentDate,
            waterBathCellNoId:waterBathCellNoId,
            TestStatusID:TestStatusID,
            HaveRow:HaveRow
        },
        success: function (data) {
            $('.testFormDivHRDT').html(data.content)
            $('#HRDTActionBtn').html(data.actionBtn)
            $('#TestDataInputFormHRDT').modal('show')
            HRDTDischargeProfile_tbl(ptpTestScheduleID, TestStatusID)
            HRDTTestResult_tbl(ptpTestScheduleID, TestStatusID)
        }
    });
    
}

// function HRDTSaveDetailBtn(formCatID, sampleID, testTableID, ptpTestScheduleID){
//     var formCategoryId = formCatID
//     var sampleId = sampleID
//     var testTableId = testTableID
//     var ptpTestScheduleId = ptpTestScheduleID
//     var DataFileName = $('#HRDTDataFileName').val()
//     var EquipmentNo = $('#HRDTTestDataEqipment').val()
//     var BatteryTemp = $('#HRDTBatTemp').val()
//     var OCV = $('#HRDTOCV').val()
//     var CCA = $('#HRDTCCA').val()
//     var IR = $('#HRDTIR').val()

//     console.log(formCategoryId,  sampleId, testTableId, ptpTestScheduleId, DataFileName, EquipmentNo, BatteryTemp, OCV, CCA, IR)

//     var formData = new FormData();
//     formData.append('action', 'HRDTSaveDetails')
//     formData.append('formCategoryId', formCategoryId)
//     formData.append('sampleId', sampleId)
//     formData.append('testTableId', testTableId)
//     formData.append('ptpTestScheduleId', ptpTestScheduleId)
//     formData.append('EquipmentNo', EquipmentNo)
//     formData.append('BatteryTemp', BatteryTemp)
//     formData.append('OCV', OCV)
//     formData.append('IR', IR)
//     formData.append('DataFileName', DataFileName)

//     var IsProceed = true;
//     for (let entry of formData.entries()) {
//         console.log(entry[0] + ':', entry[1]);
//         if(entry[1] === null || entry[1] === 0 || entry[1].trim() === ""){
//             Swal.fire('Data Validation', 'Field cannot be empty, please put n/a if not applicable.', 'warning');
//             IsProceed = false;
//             break;
//         }
//     }

//     if(IsProceed){
//         Swal.fire({
//             title: 'Confirmation',
//             text: 'Do you want to save this test details?',
//             icon: 'question',
//             showCancelButton: true,
//             cancelButtonText: 'Cancel',
//             confirmButtonText: 'Save',
//           }).then(result => {
//                 if (result.isConfirmed) {
//                     $.ajax({
//                         type: "POST",
//                         dataType: "JSON",
//                         contentType: false,
//                         processData: false,
//                         url: "LabAnalystPhp_repository/index_repo.php",
//                         data: formData,
//                         success: function (data) {
//                             var result = JSON.parse(data.result)
//                             if(result==1){
//                                 Swal.fire('success', 'Data has been saved.', 'success');
//                                 $('#HRDTSaveDetailsBtn').addClass('d-none')
//                                 $('#HRDTEditDetailsBtn').removeClass('d-none')
//                                 // alert(result)
//                             }
//                             else{
//                                 Swal.fire('error', 'something went wrong.', 'error');
//                             }
//                         }
//                     });

//                 } else if (result.dismiss === Swal.DismissReason.cancel) {

//                 }
//          });
//     }
// }

function HRDTSaveRetestDetailBtn(formCatID, sampleID, testTableID, ptpTestScheduleID){
    var formCategoryId = formCatID
    var sampleId = sampleID
    var testTableId = testTableID
    var ptpTestScheduleId = ptpTestScheduleID
    var DataFileName = $('#HRDTDataFileName').val()
    var EquipmentNo = $('#HRDTTestDataEqipment').val()
    var BatteryTemp = $('#HRDTBatTemp').val()
    var OCV = $('#HRDTOCV').val()
    var CCA = $('#HRDTCCA').val()
    var IR = $('#HRDTIR').val()

    console.log(formCategoryId,  sampleId, testTableId, ptpTestScheduleId, DataFileName, EquipmentNo, BatteryTemp, OCV, CCA, IR)

    var formData = new FormData();
    formData.append('action', 'HRDTSaveDetails')
    formData.append('formCategoryId', formCategoryId)
    formData.append('sampleId', sampleId)
    formData.append('testTableId', testTableId)
    formData.append('ptpTestScheduleId', ptpTestScheduleId)
    formData.append('EquipmentNo', EquipmentNo)
    formData.append('BatteryTemp', BatteryTemp)
    formData.append('OCV', OCV)
    formData.append('IR', IR)
    formData.append('DataFileName', DataFileName)

    var IsProceed = true;
    for (let entry of formData.entries()) {
        console.log(entry[0] + ':', entry[1]);
        if(entry[1] === null || entry[1] === 0 || entry[1].trim() === ""){
            Swal.fire('Data Validation', 'Field cannot be empty, please put n/a if not applicable.', 'warning');
            IsProceed = false;
            break;
        }
    }

    if(IsProceed){
        Swal.fire({
            title: 'Confirmation',
            text: 'Do you want to save this test details?',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Save',
          }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        url: "LabAnalystPhp_repository/index_repo.php",
                        data: formData,
                        success: function (data) {
                            var result = JSON.parse(data.result)
                            if(result==1){
                                Swal.fire('success', 'Data has been saved.', 'success');
                                $('#HRDTSaveDetailsBtn').addClass('d-none')
                                $('#HRDTEditDetailsBtn').removeClass('d-none')
                                // alert(result)
                            }
                            else{
                                Swal.fire('error', 'something went wrong.', 'error');
                            }
                        }
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {

                }
         });
    }
}

function HRDTDischargeProfileModalBtn(TestPtpScheduleID){ 
    //This function will check if their is already set testDataInput or not
    //if not it will add the data and prompt a data validation
    var PtpScheduleID = TestPtpScheduleID
    var StatusID = $('#StatusID').val()
    // alert(PtpScheduleID)
    $('#HRDTTestPTPScheduleIDShowProfile').val(PtpScheduleID)
    $('#HRDTStatusIDDischargeProfile').val(StatusID )

    var forcatID = $('#formCategoryId').val()
    var sampleId = $('#sampleId').val()
    var testTableID = $('#testTableId').val()
    var cycleNo = 1
    var DataFileName = $('#HRDTDataFileName').val()

    var EquipmentNo = $('#HRDTTestDataEqipment').val()
    var BatteryTemp = $('#HRDTBatTemp').val()
    var OCV = $('#HRDTOCV').val()
    var CCA = $('#HRDTCCA').val()
    var IR = $('#HRDTIR').val()
    // alert(PtpScheduleID + " " + forcatID  + " " + sampleId + " " + testTableID + " " + DataFileName + " " + EquipmentNo + " " + BatteryTemp + " " +  OCV + " " + CCA + " " + IR)
    var formData = new FormData();
    formData.append('action', 'HRDTSaveDetails')
    formData.append('formCategoryId', forcatID)
    formData.append('sampleId', sampleId)
    formData.append('testTableId', testTableID)
    formData.append('ptpTestScheduleId',  PtpScheduleID)
    formData.append('EquipmentNo', EquipmentNo)
    formData.append('BatteryTemp', BatteryTemp)
    formData.append('OCV', OCV)
    formData.append('CCA', CCA)
    formData.append('IR', IR)
    formData.append('DataFileName', DataFileName)

    var IsProceed = true;
    for (let entry of formData.entries()) {
        console.log(entry[0] + ':', entry[1]);
        if(entry[1] === null || entry[1] === 0 || entry[1].trim() === ""){
            Swal.fire('Data Validation', 'Field cannot be empty, please put n/a if not applicable.', 'warning');
            IsProceed = false;
            break;
        }
    }

    if(IsProceed){
        $.ajax({
            type: "POST",
            dataType: "JSON",
            contentType: false,
            processData: false,
            url: "LabAnalystPhp_repository/index_repo.php",
            data: formData,
            success: function (data) {
                var result = JSON.parse(data.result)
                if(result==1){
                    // Swal.fire('success', 'Data has been saved.', 'success');
                    // $('#HRDTSaveDetailsBtn').addClass('d-none')
                    // $('#HRDTEditDetailsBtn').removeClass('d-none')
                    // alert(result)
                    process_tbl(4)
                    $('#HRDTDischargeProfile').modal('show')
                }
                else{
                    Swal.fire('error', 'something went wrong.', 'error');
                }
            }
        });
    }

}

$('#HRDTDischargeProfileBtnSave').on('click', function(e){
    e.preventDefault()
    // alert('hello')
    var TestPtpScheduleId = $('#HRDTTestPTPScheduleIDShowProfile').val()
    var DischargeCurrent = $('#HRDTCurrentShowProfiles').val()
    // var hours = parseInt($('#hour-icon').val()) || 0
    // var minutes = parseInt($('#minutes-icon').val()) || 0
    var seconds = parseInt($('#seconds-icon').val()) || 0
    // var TotalMinutes = hours * 60 + minutes + seconds / 60;
    var status = $('#HRDTStatusIDDischargeProfile').val()
    console.log(TestPtpScheduleId, DischargeCurrent, seconds)
    // alert(TestPtpScheduleId)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'DischargeProfileData',
            TestPtpScheduleId:TestPtpScheduleId,
            DischargeCurrent:DischargeCurrent,
            seconds:seconds
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                HRDTDischargeProfile_tbl(TestPtpScheduleId, status)
                $('#HRDTDischargeProfile').modal('hide')
            }
        }
    });
})

function DischargeProfileEdit(id, ptpScheduleID){
    var DCHProfileID = id
    var PtpScheduleID = ptpScheduleID
    $('#HRDTTestPTPScheduleIDShowProfileEdit').val(PtpScheduleID)
    $('#DCHProfileID').val(DCHProfileID)
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data:{
            action:'FetchDCHProfileEdit',
            DCHProfileID:DCHProfileID
        },
        success: function (data) {
            $('#HRDTCurrentShowProfilesEdit').val(data.DCHVal)
            $('#seconds-iconEdit').val(data.seconds)
            $('#HRDTDischargeProfileEdit').modal('show')
        }
    });
    
}

$('#HRDTDischargeProfileBtnUpdate').on('click', function(e){
    e.preventDefault()
    var current = $('#HRDTCurrentShowProfilesEdit').val()
    var seconds = $('#seconds-iconEdit').val()
    var TestPTPScheduleID = $('#HRDTTestPTPScheduleIDShowProfileEdit').val()
    var DCHProfileID = $('#DCHProfileID').val()

})

function HRDTDischargeProfile_tbl(ptpscheduleID, statusid){
    var TestPTPScheduleId = ptpscheduleID
    var StatusID = statusid
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'HRDTDichargeProfileTbl',
            TestPTPScheduleId:TestPTPScheduleId,
            StatusID:StatusID
        },
        success: function (data) {
            $('#HRDTDischargeProfile_tbl').html(data)
        }
    });
}

function HRDTTestResultModalBtn(TestPtpScheduleID){ 
    //This function will check if their is already set testDataInput or not
    //if not it will add the data and prompt a data validation
    var PtpScheduleID = TestPtpScheduleID
    var StatusID = $('#StatusID').val()
    $('#HRDTTestPTPScheduleIDTestResult').val(PtpScheduleID)
    $('#HRDTStatusIDTestResult').val(StatusID)

    var forcatID = $('#formCategoryId').val()
    var sampleId = $('#sampleId').val()
    var testTableID = $('#testTableId').val()
    var cycleNo = 1
    var DataFileName = $('#HRDTDataFileName').val()

    var EquipmentNo = $('#HRDTTestDataEqipment').val()
    var BatteryTemp = $('#HRDTBatTemp').val()
    var OCV = $('#HRDTOCV').val()
    var CCA = $('#HRDTCCA').val()
    var IR = $('#HRDTIR').val()
    // alert(PtpScheduleID + " " + forcatID  + " " + sampleId + " " + testTableID + " " + DataFileName + " " + EquipmentNo + " " + BatteryTemp + " " +  OCV + " " + CCA + " " + IR)
    var formData = new FormData();
    formData.append('action', 'HRDTSaveDetails')
    formData.append('formCategoryId', forcatID)
    formData.append('sampleId', sampleId)
    formData.append('testTableId', testTableID)
    formData.append('ptpTestScheduleId',  PtpScheduleID)
    formData.append('EquipmentNo', EquipmentNo)
    formData.append('BatteryTemp', BatteryTemp)
    formData.append('OCV', OCV)
    formData.append('CCA', CCA)
    formData.append('IR', IR)
    formData.append('DataFileName', DataFileName)

    var IsProceed = true;
    for (let entry of formData.entries()) {
        console.log(entry[0] + ':', entry[1]);
        if(entry[1] === null || entry[1] === 0 || entry[1].trim() === ""){
            Swal.fire('Data Validation', 'Field cannot be empty, please put n/a if not applicable.', 'warning');
            IsProceed = false;
            break;
        }
    }

    if(IsProceed){
        $.ajax({
            type: "POST",
            dataType: "JSON",
            contentType: false,
            processData: false,
            url: "LabAnalystPhp_repository/index_repo.php",
            data: formData,
            success: function (data) {
                var result = JSON.parse(data.result)
                if(result==1){
                    // Swal.fire('success', 'Data has been saved.', 'success');
                    // $('#HRDTSaveDetailsBtn').addClass('d-none')
                    // $('#HRDTEditDetailsBtn').removeClass('d-none')
                    // alert(result)
                    process_tbl(4)
                    $('#HRDTTestResultForm').modal('show')
                }
                else{
                    Swal.fire('error', 'something went wrong.', 'error');
                }
            }
        });
    }
}

$('#TestResultBtnSave').on('click', function(e){
    e.preventDefault()
    // alert('hello')
    var TestPTPScheduleId = $('#HRDTTestPTPScheduleIDTestResult').val()
    var Voltage = $('#voltage-icon').val()
    var Seconds = $('#time-icon').val()

    var status = $('#HRDTStatusIDTestResult').val()

    console.log(TestPTPScheduleId, Voltage, Seconds)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'TestResultData',
            TestPTPScheduleId:TestPTPScheduleId,
            Voltage:Voltage,
            Seconds:Seconds
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                HRDTTestResult_tbl(TestPTPScheduleId, status)
                $('#HRDTTestResultForm').modal('hide')
            }
        }
    });
})

function TestResultEdit(id, ptpScheduleID){
    var TestResultID = id
    var PtpScheduleID = ptpScheduleID
    $('#HRDTTestPTPScheduleIDTestResultEdit').val(PtpScheduleID)
    $('#HRDTestResultID').val(TestResultID)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data:{
            action:'FetchTestResultEdit',
            TestResultID:TestResultID
        },
        success: function (data) {
            $('#voltage-iconEdit').val(data.Voltage)
            $('#time-iconEdit').val(data.seconds)
            $('#HRDTTestResultFormEdit').modal('show')
        }
    });
    
}

function HRDTTestResult_tbl(ptpscheduleID, statusid){
    var TestPTPScheduleId = ptpscheduleID
    var StatusID = statusid
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'HRDTTestResultTbl',
            TestPTPScheduleId:TestPTPScheduleId,
            StatusID:StatusID
        },
        success: function (data) {
            $('#HRDTTestResult_tbl').html(data)
        }
    });
}

function SubmitHRDT(){
    var TestDataInputId = $('#TestDataInputiID').val()
    var TestPTPScheduleID = $('#ptpTestScheduleID').val()
    var HRDTRemarks = $('#HRDTFormRemarks').val()
    // alert(TestDataInputId + ' - ' + TestPTPScheduleID + ' - ' + HRDTRemarks)
    Swal.fire({
        title: 'Confirmation',
        text: 'Do you want to submit this Test Data?',
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Proceed',
      }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "LabAnalystPhp_repository/index_repo.php",
                    data: {
                        action:'SubmitHRDTData',
                        TestDataInputId:TestDataInputId,
                        TestPTPScheduleID:TestPTPScheduleID,
                        HRDTRemarks:HRDTRemarks
                    },
                    success: function (data) {
                        var result = JSON.parse(data)
                        if(result==1){
                            Swal.fire('success', 'Data has been saved.', 'success');
                            
                        }
                        else{
                            Swal.fire('error', 'something went wrong.', 'error');
                        }
                        process_tbl(4)
                        $('#TestDataInputFormHRDT').modal('hide')
                    }
                });

            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
     });  
}

function ReviewHRDTBtn(testDataInputID){
    var testDataInputId = testDataInputID
    var HRDTRemarks = $('#HRDTFormRemarks').val()
    
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'submitReviewedHRDTform',
            testDataInputId:testDataInputId,
            HRDTRemarks:HRDTRemarks
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire('success', 'Test form has been reviewed.', 'success');
                $('#TestDataInputFormHRDT').modal('hide')
            }
            else{
                Swal.fire('error', 'something went wrong.', 'error');
            }
            process_tbl(4)
            $('#TestDataInputFormHRDT').modal('hide')
        }
    });
}

function SubmitChangesHRDTBtn(testDataInputID, ptpScheduleID){
    var testDataInputId = testDataInputID
    var ptpScheduleid = ptpScheduleID
    var HRDTRemarks = $('#HRDTFormRemarks').val()

    //Test Details USer Input
    var EquipmentNo = $('#HRDTTestDataEqipment').val()
    var BatteryTemp = $('#HRDTBatTemp').val()
    var OCV = $('#HRDTOCV').val()
    var CCA = $('#HRDTCCA').val()
    var IR = $('#HRDTIR').val()
    var DataFileName = $('#HRDTDataFileName').val()
    //Test Details USer Input End

    // alert(EquipmentNo + " - " + BatteryTemp + " - " + OCV + " - " + CCA + " - " + IR + " - " + DataFileName)

    // alert(testDataInputId + " - " + ptpScheduleid + " - " + HRDTRemarks)

    var formData = new FormData();
    formData.append('action', 'submitDataChangedHRDTform')
    formData.append('testDataInputId', testDataInputId)
    formData.append('ptpScheduleid', ptpScheduleid)
    formData.append('EquipmentNos', EquipmentNo)
    formData.append('BatteryTemp', BatteryTemp)
    formData.append('OCV', OCV)
    formData.append('CCA', CCA)
    formData.append('IR', IR)
    formData.append('DataFileName', DataFileName)
    formData.append('HRDTRemarks', HRDTRemarks)

    var IsProceed = true;
    for (let entry of formData.entries()) {
        console.log(entry[0] + ':', entry[1]);
        if(entry[1] === null || entry[1] === 0 || entry[1].trim() === ""){
            Swal.fire('Data Validation', 'Field cannot be empty, please put n/a if not applicable.', 'warning');
            IsProceed = false;
            break;
        }
    }

    if(IsProceed){
        Swal.fire({
            title: 'Confirmation',
            text: 'Do you want to submit this data?',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Save',
          }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        url: "LabAnalystPhp_repository/index_repo.php",
                        data: formData,
                        success: function (data) {
                            var result = JSON.parse(data)
                            if(result==1){
                                Swal.fire('success', 'Test has been updated.', 'success');
                            }
                            else{
                                Swal.fire('error', 'something went wrong.', 'error');
                            }
                            process_tbl(4)
                            $('#TestDataInputFormHRDT').modal('hide')
                        }
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {

                }
         });
    }

}

function ApprovalHRDTBtn(testDataInputID, ptpScheduleID){
    var testDataInputId = testDataInputID
    var ptpScheduleid = ptpScheduleID
    var HRDTRemarks = $('#HRDTFormRemarks').val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'submitApprovedHRDTform',
            testDataInputId:testDataInputId,
            HRDTRemarks:HRDTRemarks,
            ptpScheduleid:ptpScheduleid
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire('success', 'Test has been approved.', 'success');
            }
            else{
                Swal.fire('error', 'something went wrong.', 'error');
            }
            process_tbl(4)
            $('#TestDataInputFormHRDT').modal('hide')
        }
    });
}

//HRDT Forms Modal Discharge Profile and TestResult End


function VTTestForm(sampleID, currentTest, testTableID, formCatID, formTitleText, testSampleTxt, formTestDate){
    var sampleId = sampleID
    var currentTestTxt = currentTest
    var testTableId = testTableID
    var formCategoryId = formCatID
    var formTitle = formTitleText
    var testSampleSysText = testSampleTxt
    var currentDate = formTestDate
    
    $('#textTestTitleVT').text('Testing Form: ' + formTitle)
    $('#TestDataBatNoVT').val(testSampleSysText)
    $('#TestDataTestDateVT').val(currentDate)
    $('#TestDataTestTypeVT').val(currentTestTxt)
    $('#TestDataInputFormVT').modal('show')
}

//VT forms modal add test observation
$('#ShowAddTestObservationBtn').on('click', function(){
    $('#VTAddObservationTestForm').modal('show')
})
//VT forms modal add test observation end

// WCT form modal
function WCTTestForm(ptpScheduleID, sampleID, currentTest, testTableID, formCatID, formTitleText, testSampleTxt, formTestDate, waterBathCellNoID, statusID, IsHaveRow){
    var ptpTestScheduleID = ptpScheduleID
    var sampleId = sampleID
    var currentTestTxt = currentTest
    var testTableId = testTableID
    var formCategoryId = formCatID
    var formTitle = formTitleText
    var testSampleSysText = testSampleTxt
    var currentDate = formTestDate
    var waterBathCellNoId = waterBathCellNoID
    var TestStatusID = statusID
    var HaveRow = IsHaveRow
    console.log(sampleId, currentTestTxt, testTableId, formCategoryId, testSampleSysText, TestStatusID)
    $('#textTestTitleHRDT').text('Testing Form: ' + formTitle)
    $('#TestDataBatNoHRDT').val(testSampleSysText)
    $('#TestDataTestDateHRDT').val(currentDate)
    $('#TestDataTestTypeHRDT').val(currentTestTxt)
    // $('#TestDataInputFormHRDT').modal('show')
    // alert(HaveRow)

    $('#TestDataInputFormWCT').modal('show')
    
}
// WCT form modal End

//HLET Modal Forms
$('#ShowAddHLETBtn').on('click', function(){
    $('#HLETTestForm').modal('show')
})
//HLET Modal Forms end

//LLET Modal Forms
$('#ShowAddLLETBtn').on('click', function(){
    $('#LLETTestForm').modal('show')
})
//LLET Modal Forms end

//DOD17p5 Modal Forms
$('#ShowAddDOD17p5Btn').on('click', function(){
    $('#DOD17p5TestForm').modal('show')
})
//DOD17p5 Modal Forms end

//DOD50 Modal Forms
$('#ShowAddDOD50Btn').on('click', function(){
    $('#DOD50TestForm').modal('show')
})
//DOD50 Modal Forms end

function TestForms(sampleID, batteryTypeID){
    var testSampleID = sampleID
    var battTypeID = batteryTypeID
    $('#TestForms').modal('show')
}

$('#flexSwitchCheckDefault').change(function() {
    if ($(this).is(':checked')) {
        // Hide rows 2 and 3
        $('#initialMeasureTbl tr').removeClass('hidden-row');
        // Store the checkbox state in localStorage
        localStorage.setItem('hideRowsCheckboxState', 'checked');
      } else {
        // Show all rows
        $('#initialMeasureTbl tr:gt(1):lt(2)').addClass('hidden-row');
        // Remove the checkbox state from localStorage
        localStorage.removeItem('hideRowsCheckboxState');
      }
})

$('#SubmitInitialData').on('click', function(){
    var sampleID = $('#SampleID').val()
    var batteryTypeID = $('#BatteryTypeID').val()
    // alert(sampleID+' - '+batteryTypeID)
    if(batteryTypeID==2){
        // alert('LM')
        var LM_weight_raw = $('#LM_weight').val()
        var SG_raw = $('#LM_SG').val()
        var OCV5s_raw = $('#LM_OCV5').val()
        var OCV30s_raw = $('#LM_OCV30').val()
        var TimeTo12V =  $('#TimeTo12V').val()
        var weightAfterActivation_raw = $('#WeightAfterActivation').val()

        var LM_weight = setNullToZero(LM_weight_raw)
        var SG = setNullToZero(SG_raw)
        var OCV5s = setNullToZero(OCV5s_raw)
        var OCV30s = setNullToZero(OCV30s_raw)
        var weightAfterActivation = setNullToZero(weightAfterActivation_raw)
        var dataArray = [];

        // Iterate over each row in the table
        $('#initialMeasureTbl tbody tr').each(function () {
            var rowData = {};
            // Iterate over each input field in the row
            $(this).find('input').each(function () {
                var id = $(this).attr('id');
                var value = $(this).val();
                rowData[id] = setNullToZero(value);
            });

            // Add the row data to the array
            dataArray.push(rowData);
        });
        
        console.log(LM_weight, SG, OCV5s, OCV30s, weightAfterActivation, TimeTo12V)
        console.log(dataArray)
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "LabAnalystPhp_repository/index_repo.php",
            data: {
                action:'LM_InitialMeasurement',
                sampleID:sampleID,
                batteryTypeID:batteryTypeID,
                LM_weight:LM_weight,
                SG:SG,
                OCV5s:OCV5s,
                OCV30s:OCV30s,
                weightAfterActivation:weightAfterActivation,
                TimeTo12V:TimeTo12V,
                ActivationData:dataArray
            },
            success: function (data) {
                var result = JSON.parse(data)
                if(result==1){
                    $('#initialMeasureTbl tbody tr input').val('');
                    $('#LM_weight').val('')
                    $('#LM_SG').val()
                    $('#LM_OCV5').val()
                    $('#LM_OCV30').val()
                    $('#TimeTo12V').val()
                    $('#WeightAfterActivation').val()
                    process_tbl(3)
                    $('#InitialMeasurement').modal('hide')
                }
            }
        });
    }
    else{
        // alert('MF or Lithium')
        var mf_weight_raw = $('#MF_weight').val();
        var mf_ocv_raw = $('#MF_OCV').val()
        var mf_ir_raw = $('#MF_IR').val()
        var mf_cca_raw = $('#MF_CCA').val()

        var mf_weight = setNullToZero(mf_weight_raw)
        var mf_ocv = setNullToZero(mf_ocv_raw)
        var mf_ir = setNullToZero(mf_ir_raw)
        var mf_cca = setNullToZero(mf_cca_raw)
        
        var dataArray = [];

        // Iterate over each row in the table
        $('.row-cell').each(function () {
            var rowData = {};

            var RawDataSG = $(this).find('.Cell_SG').val();
            var RawDataTemp = $(this).find('.Cell_Temp').val();
            rowData.CellId = $(this).find('.CellId').val();
            rowData.Cell_SG = setNullToZero(RawDataSG);
            rowData.Cell_Temp = setNullToZero(RawDataTemp);

            // Add the row data to the array
            dataArray.push(rowData);
        });

        // Log the array to the console
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "LabAnalystPhp_repository/index_repo.php",
            data: {
                action:'MF_InitialMeasurement',
                sampleID:sampleID,
                batteryTypeID:batteryTypeID,
                mf_weight:mf_weight,
                mf_ocv:mf_ocv,
                mf_ir:mf_ir,
                mf_cca:mf_cca,
                CellData:dataArray
            },
            success: function (data) {
                var result = JSON.parse(data)
                if(result==1){
                    $('#MF_weight').val('');
                    $('#MF_OCV').val('')
                    $('#MF_IR').val('')
                    $('#MF_CCA').val('')
                    $('.Cell_SG, .row-cell .Cell_Temp').val('');
                    process_tbl(3)
                    $('#InitialMeasurement').modal('hide')
                }
            }
        });
    }
})

function ViewSamples(sampleID, waterBathCellNoID){
    var TestSampleID = sampleID
    var CellNoID_raw = waterBathCellNoID
    var CellNoID = setNullToZero(CellNoID_raw)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'ReprintSamples',
            TestSampleID:TestSampleID,
            CellNoID:CellNoID
        },
        success: function (data) {
            $('.rounded-rectangle').html(data)
            $('#ViewSamples').modal('show')
        }
    });
}

$('#PrintInterface').on('click', function(){
    $('#SampleLabellingModal').on('hidden.bs.modal', function (e) {
        window.open('../print.php', '_blank');
    });
    $('#SampleLabellingModal').modal('hide');
})

$('#printSamples').on('click', function(){
    var SampleID = $('#SampleID_holder').val()
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'printSamples',
            SampleID:SampleID
        },
        success: function (data) {
            
            Swal.fire({
                title: 'Printing samples',
                text: 'Samples is now ready for printing in print interface',
                icon: 'success',
                timer: 2000, // Time in milliseconds
                timerProgressBar: true,
                showConfirmButton: false
            })

            $('#ViewSamples').on('hidden.bs.modal', function (e) {
                window.open('../reprint.php', '_blank');
            });
            $('#ViewSamples').modal('hide')
            
        }
    });
})

function setNullToZero(value){
    var SetterVal = value
    var NewVal = (SetterVal !== null && SetterVal !== undefined && SetterVal !== '') ? parseFloat(SetterVal) : 0;
    return NewVal;
}

function renderWateBath(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'waterBaths'
        },
        success: function (data) {
            $('#wb1').html(data.WaterBath1)
            $('#wb2').html(data.WaterBath2)
            $('#wb4').html(data.WaterBath4)
            $('#wb5').html(data.WaterBath5)
            $('#wb6').html(data.WaterBath6)
            $('#wb7').html(data.WaterBath7)
            $('#wb8').html(data.WaterBath8)
            $('#wb10').html(data.WaterBath10)
            $('#wb11').html(data.WaterBath11)
            $('#wb12').html(data.WaterBath12)
            $('#wb13').html(data.WaterBath13)
            $('#wb14').html(data.WaterBath14)
            $('#wb15').html(data.WaterBath15)
            $('#wb16').html(data.WaterBath16)
            $('#wb17').html(data.WaterBath17)
            $('#wb18').html(data.WaterBath18)
            $('#wb19').html(data.WaterBath19)
            $('#wb20').html(data.WaterBath20)
            $('#wb9').html(data.WaterBath9)
            $('#wbRcnRct').html(data.WaterBathRcnRct)
        }
    });
}

function renderWateBathMapping(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'waterBathsMapping'
        },
        success: function (data) {
            $('#wb1_map').html(data.WaterBath1)
            $('#wb2_map').html(data.WaterBath2)
            $('#wb4_map').html(data.WaterBath4)
            $('#wb5_map').html(data.WaterBath5)
            $('#wb6_map').html(data.WaterBath6)
            $('#wb7_map').html(data.WaterBath7)
            $('#wb8_map').html(data.WaterBath8)
            $('#wb10_map').html(data.WaterBath10)
            $('#wb11_map').html(data.WaterBath11)
            $('#wb12_map').html(data.WaterBath12)
            $('#wb13_map').html(data.WaterBath13)
            $('#wb14_map').html(data.WaterBath14)
            $('#wb15_map').html(data.WaterBath15)
            $('#wb16_map').html(data.WaterBath16)
            $('#wb17_map').html(data.WaterBath17)
            $('#wb18_map').html(data.WaterBath18)
            $('#wb19_map').html(data.WaterBath19)
            $('#wb20_map').html(data.WaterBath20)
            $('#wb9_map').html(data.WaterBath9)
            $('#wbRcnRct_map').html(data.WaterBathRcnRct)
        }
    });
}

function cell(id){
    var CellID = id
    var TestSamplesID = $('#TestSampleID').val()
    var TestSampleNo = $('#TestSampleNo').val()
    
    $('#CellID').val(CellID)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'fetchCellData',
            CellID:CellID
        },
        success: function(data) {
            var statusID = JSON.parse(data.status)
            $('#AllocationDiv').html(data.output)
            if(statusID==2 || statusID==3){
                $('#AllocateBtn').prop('disabled', true)
                $('#testSamplesText').text('')
            }
            
            else{
                $('#AllocateBtn').prop('disabled', false)
                $('#testSamplesText').text('Sample ID.: ' + TestSampleNo)
            }

            $('#AllocationModal').modal('show')
        }
    });

}

function cellMap(id){
    var CellID = id
    cellMapDisplay(CellID)
    $('#CircuitMappingModal').modal('show')
}

function cellMapDisplay(id){
    var CellID = id
    $('#MapCellID').val(CellID)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'fetchCellData',
            CellID:CellID
        },
        success: function(data) {
            var statusID = JSON.parse(data.status)
            $('#MapStatusCellID').val(statusID)
            $('#mappingDiv').html(data.output)
            if(statusID==2){
                $('#MapBtn').text('In Use')
                $('#MapBtn').prop('disabled', true)
                // $('#testSamplesText').text('')
            }
            else if(statusID==3){
                $('#MapBtn').text('Repaired')
                $('#MapBtn').prop('disabled', false)
                
                // $('#testSamplesText').text('')
            }
            else{
                $('#MapBtn').text('Defective')
                $('#MapBtn').prop('disabled', false)
                $('#testSamplesText').text('Sample ID.: ' + TestSampleNo)
            }
        }
    });
}

$('#MapBtn').on('click', function(){
    var CellID = $('#MapCellID').val()
    var StatusID = $('#MapStatusCellID').val()
    var Remarks = $('#AllocationRemarks').val()
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'defective',
            CellID:CellID,
            StatusID:StatusID,
            Remarks:Remarks
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                renderWateBathMapping()
                cellMapDisplay(CellID)
            }
        }
    });
})

function WaterBathStatusCounter(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {action:'WBCardCounter'},
        success: function (data) {
            $('#MapVacant').text(data.vacant)
            $('#MapInUse').text(data.use)
            $('#MapDefect').text(data.defective)

            $('#circuitVacant').text(data.vacant)
            $('#circuitUSe').text(data.use)
            $('#circuitDefect').text(data.defective)
        }
    });
}

function cellTransfer(cellID, TransferCellID, sampleID){
    var cellBathID = cellID
    var transferCellID = TransferCellID
    var transferSampleID = sampleID

    $('#cellBathID').val(cellBathID)
    $('#transferCellID').val(transferCellID)

    cellTransferDetails(cellBathID, transferCellID, transferSampleID)
    $('#TransferAllocationModal').modal('show')
}

function cellTransferDetails(id1, id2, id3){
    var cellBathID = id1
    var transferCellID = id2
    var transferSampleID = id3
    $('#transferSampleID2').val(transferSampleID)
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'transferCellDetails',
            cellBathID:cellBathID,
            transferCellID:transferCellID
        },
        success: function (data){
            var CheckTransfer = JSON.parse(data.transferCheck)
            $('#TransferCellDiv').html(data.output)

            if(CheckTransfer==1){
                $('#TransferBtn').prop('disabled', true)
            }
            else{
                $('#TransferBtn').prop('disabled', false)
            }

        }
    });
}

$('#TransferBtn').on('click', function(){
    var TestSamplesID = $('#transferSampleID2').val()
    var cellBathID = $('#cellBathID').val()
    var transferCellID = $('#transferCellID').val()

    // alert(TestSamplesID)
    // alert(cellBathID+' '+transferCellID)
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'transferCell',
            cellBathID:cellBathID,
            transferCellID:transferCellID
        },
        success: function (data) {
            // alert(data)
            cellTransferDetails(cellBathID, transferCellID)
            renderWateBathTransfer(TestSamplesID)
        }
    });
})

$('#AllocateBtn').on('click', function(){
    var CellID = $('#CellID').val()
    var TestSamplesID = $('#TestSampleID').val()
    var Remarks = $('#AllocationRemarks').val()
    // alert(CellID + ' ' + TestSamplesID)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabAnalystPhp_repository/index_repo.php",
        data: {
            action:'samplesAllocation',
            CellID:CellID,
            TestSamplesID:TestSamplesID,
            Remarks:Remarks
        },
        success: function (data){
            var result = JSON.parse(data)
            if(result==1){
                renderWateBath()
                process_tbl(3)
                $('#AllocationModal').modal('hide')
            }
            else if(result==2){
                Swal.fire('Circuit Warning', 'Sample was already allocated.', 'warning');
            }
        }
    });
})

$('#prioritizationLegend').on('click', function(e){
    e.preventDefault()
    $('#legendPrioritization').modal('show')
})

function logout(){
    Swal.fire({
        title: 'Do you want to logout?',
        // text: 'Request details will save automatically',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Logout',
        cancelButtonText: 'Cancel',
        }).then(result => {
        if (result.isConfirmed) {
            // Handle the case when the user clicks the "Save" button
            // This block will only execute if the condition is true and the action is successful
    
        //   Swal.fire('Success', 'Your request details have been saved as draft.', 'success');
        $.ajax({
            type: "POST",
            url: "LabAnalystPhp_repository/index_repo.php",
            data: {
                action:'logout'
            },
            dataType: "JSON",
            success: function (data) {
                var result = JSON.parse(data)
                if(result==1){
                    location.href='../index.php'; 
                }
            }
        });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Handle the case when the user clicks the "Cancel" button
        //   Swal.fire('Cancelled', 'Your request details have not been saved.', 'info');
        }
    });
    
}