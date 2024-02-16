$(document).ready(function(){
    // $('#btr').fadeOut()
    WaterBathStatusCounter()
    $('#my-tooltip-button').tooltip();
    $('#my-tooltip-priority').tooltip();
   
     // Function to handle click on sidebar links
     $('.sidebar-link').on('click', function(e) {
        e.preventDefault(); // Prevent the default behavior of the link

        // Remove 'active' class from all sidebar items
        $('.sidebar-item').removeClass('active');

        // Add 'active' class to the parent li of the clicked link
        $(this).closest('.sidebar-item').addClass('active');
    });

    setInterval(() => {
        WaterBathStatusCounter()
    }, 5000);
    $('#cardStat').slideDown(100)
    $('#BtrCard').slideDown(100)
    $('#CirecuitMapCard').slideUp(100)
    $('#ongoing').slideUp(100)
    BatteryRequest_tbl()
})

function loadContent(id){
    var index = id
     if(index==1){
        $('#cardStat').slideDown(100)
        $('#BtrCard').slideDown(100)
        $('#CirecuitMapCard').slideUp(100)
     }
     else if(index==2){
        $('#CirecuitMapCard').slideDown(100)
        $('#BtrCard').slideUp(100)
        $('#cardStat').slideUp(100)
        renderWateBathMapping()
     }
}

function BatteryRequest_tbl(){
    $('#TestRequest_tbl').DataTable().destroy()
    var dataTable = $('#TestRequest_tbl').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "bSort": true,
        "bInfo":true,
        "searching":true,
        "order" : [],

        "ajax" : {
            url: "LabManagerPhp_repository/index_repo.php",
            type: "POST",
            data:{
                action:'TestRequest_tbl'
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
                "targets": 1, // Target the first column (index 0)
                "orderable": false // Enable sorting for the first column
            },
            {
                "targets": 7, // Target the first column (index 0)
                "orderable": false // Enable sorting for the first column
            },
            {
                "targets": 9, // Target the first column (index 0)
                "orderable": false // Enable sorting for the first column
            }
        ],
    })
}

$('.btn-check').on('click', function(){
    var ID = $(this).val()

    if(ID==1){
        $('#btr').slideUp(100)
        $('#ongoing').slideDown(100)
    }
    else{
        $('#ongoing').slideUp(100)
        $('#btr').slideDown(100)
        BatteryRequest_tbl()
    }
})

function ViewBtr(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabManagerPhp_repository/index_repo.php",
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

function ApprovedBtr(RequestID){
    var requestID = RequestID
    $('#setterApproval').val(1)
    $('#RequestId_holder').val(requestID)
    fetchPrioritization()
    $('#ApprovedRemarks').val('')
    $('#ApprovalModal').modal('show')
}

function Revision(){
    var RequestID = $('#RequestID_ref').val()
    $('#RequestId_Revise_holder').val(RequestID)
    $('#RevisionBTR').val('')
    $('#RevisionModal').modal('show')
    $('#ViewBTR').modal('hide')
}

function Approve(){
    var RequestID = $('#RequestID_ref').val()
    $('#ViewBTR').modal('hide')
    $('#setterApproval').val(2)
    $('#RequestId_holder').val(RequestID)
    fetchPrioritization()
    $('#ApprovedRemarks').val('')
    $('#ApprovalModal').modal('show')
}

$('#close_revision').on('click', function(){
    $('#RevisionModal').modal('hide')
    $('#ViewBTR').modal('show')
})

$('#close_revision2').on('click', function(){
    $('#RevisionModal').modal('hide')
    $('#ViewBTR').modal('show')
})

$('#ApproveModalClose').on('click', function(){
    var setter = $('#setterApproval').val()

    if(setter==2){
        $('#ApprovalModal').modal('hide')
        $('#ViewBTR').modal('show')
    }
    else{
        $('#ApprovalModal').modal('hide')
    }
})

$('#ApproveModalClose2').on('click', function(){
    var setter = $('#setterApproval').val()

    if(setter==2){
        $('#ApprovalModal').modal('hide')
        $('#ViewBTR').modal('show')
    }
    else{
        $('#ApprovalModal').modal('hide')
    }
})

$('#BtrApproveBtn').on('click', function(){
    var RequestID = $('#RequestId_holder').val()
    var PriorityID = $('#prioritization').val()
    var Remarks = $('#ApprovedRemarks').val()
    // alert(RequestID + ' - '+ PriorityID+' - '+Remarks)
    if(PriorityID==null || PriorityID==''){
        alert('select prioritization')
    }
    else{
        Swal.fire({
            title: 'Would you like to proceed?',
            // text: 'Request details will save automatically',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Proceed',
            cancelButtonText: 'Cancel',
            }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "LabManagerPhp_repository/index_repo.php",
                    data: {
                        action:'ApproveBtr',
                        RequestID:RequestID,
                        PriorityID:PriorityID,
                        Remarks:Remarks
                    },
                    success: function (data) {
                        var result = JSON.parse(data);

                        if(result==1){
                            Swal.fire({
                                title: 'Succesfull',
                                text: 'Request successfully approved',
                                icon: 'success',
                                timer: 1000, // Time in milliseconds
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then((result) => {
                                // This code will run when the timer completes
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    // location.href='index.php';
                                    $('#ApprovalModal').modal('hide')
                                    $('#ViewBTR').modal('hide')
                                    BatteryRequest_tbl()
                                }
                            });
                        }
                        else{
                            Swal.fire({
                                title: 'Error',
                                text: 'Something went wrong',
                                icon: 'error',
                                timer: 1000, // Time in milliseconds
                                timerProgressBar: true,
                                showConfirmButton: false
                            })
                        }
                    }
                });
                
            } else if (result.dismiss === Swal.DismissReason.cancel) {
    
            }
        });
    }
    
})

$('#BtrRevisionBtn').on('click', function(){
    var RequestID = $('#RequestId_Revise_holder').val()
    var Remarks = $('#RevisionBTR').val()
    if(Remarks === null || Remarks === 0 || Remarks.trim() === ""){
        Swal.fire('Remarks', 'Remarks cannot be empty.', 'warning');
    }
    else{
        Swal.fire({
            title: 'Would you like to proceed?',
            // text: 'Request details will save automatically',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Proceed',
            cancelButtonText: 'Cancel',
            }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "LabManagerPhp_repository/index_repo.php",
                    data: {
                        action:'RevisedBtr',
                        RequestID:RequestID,
                        Remarks:Remarks
                    },
                    success: function (data) {
                        var result = JSON.parse(data);
    
                        if(result==1){
                            Swal.fire({
                                title: 'Succesfull',
                                text: 'Request successfully updated',
                                icon: 'success',
                                timer: 1000, // Time in milliseconds
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then((result) => {
                                // This code will run when the timer completes
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    // location.href='index.php';
                                    $('#RevisionModal').modal('hide')
                                    $('#ViewBTR').modal('hide')
                                    BatteryRequest_tbl()
                                }
                            });
                        }
                        else{
                            Swal.fire({
                                title: 'Error',
                                text: 'Something went wrong',
                                icon: 'error',
                                timer: 1000, // Time in milliseconds
                                timerProgressBar: true,
                                showConfirmButton: false
                            })
                        }
                    }
                });
                
            } else if (result.dismiss === Swal.DismissReason.cancel) {
    
            }
        });
    }
    
})

function ViewBtrButtonControlChange(RequestID){
    var ID = RequestID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabManagerPhp_repository/index_repo.php",
        data: {
            action:'ViewBtrFooterBtnContent',
            ID:ID
        },
        success: function (data) {
            $('#viewBtrFooter').html(data)
        }
    });
}

function fetchPrioritization(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabManagerPhp_repository/index_repo.php",
        data: {
            action:'FetchPrio'
        },
        success: function (data) {
            $('#prioritization').html(data)
        }
    });
}

function renderWateBathMapping(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabManagerPhp_repository/index_repo.php",
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

function WaterBathStatusCounter(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LabManagerPhp_repository/index_repo.php",
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
            url: "LabManagerPhp_repository/index_repo.php",
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