$(document).ready(function(){
    
    $('#positivePlateCode').prop('disabled', true)
    $('#negativePlateCode').prop('disabled', true)
    $('#brandDiv').addClass('d-none')
    $('#ClassificationOtherDiv').addClass('d-none')

    // disposalSelectData()
    requestorSelectData()
    classificationSelectData()
    batterytypeSelectData()
    ApplicationSelection(0)
    BatterySizeAddRequest(0)
    checkIfOthersSelected();
    fetchPlateType()
    $('#batteryTest').fadeOut()
    Request_tbl()
    $('#BrandNameInputFields').addClass('d-none');

    if ($('#othersCheckBox').is(':checked')) {
        $('#OthersPower').prop('disabled', true)
    } else {
        $('#OthersPower').prop('disabled', false)
    }
})

function loadContent(id){
    var index = id
    // var storage = localStorage.setItem('activeIndex', index);
}
//---------validate each fields------------//

$('#disposalValue').on('change', function(){
    var isValid = true;
    $('.error').text('');

    var DisposalID = $('#disposalValue').val();
    if (DisposalID  == 0) {
      isValid = false;
      $('#disposalerror').text('Please select disposition.');
    }

    if (isValid) {
        $('.error').text('');
        console.log('disposition validated!');
    }
})

$('#requestor').on('change', function(){
    var isValid = true;
    $('.error').text('');

    var selectedrequestor = $('#requestor').val();
    if (selectedrequestor  == null) {
      isValid = false;
      $('#requestorerror').text('Requestor cannot be empty.');
    }

    if (isValid) {
        $('.error').text('');
        console.log('requestor validated!');
    }
})

//---------validate each fields end------------//
$("#Bsize").change(function() {
    checkIfOthersSelected();
});
function checkIfOthersSelected() {
    // Get the selected value of the select element
    var selectedValue = $("#Bsize").val();

    // If the selected value is "others," show the text input; otherwise, hide it
    if (selectedValue === "Others") {
      $("#othersInputContainer").removeClass('d-none');
    } else {
      $("#othersInputContainer").addClass('d-none');
    }
}

$('#PositivePlate').on('change', function(){
    var value = $(this).val()
    
    if(value=='Others'){
        $('#positivePlateCode').prop('disabled', false)
    }
    else{
        $('#positivePlateCode').prop('disabled', true)
    }

    if(value=='N/A'){
        $('#positivePlateQty').prop('disabled', true)
        $('#positivePlateQty').val(0)
    }
    else{
        $('#positivePlateQty').prop('disabled', false)
        $('#positivePlateQty').val('')
    }
})

$('#NegativePlate').on('change', function(){
    var value = $(this).val()
    if(value=='Others'){
        $('#negativePlateCode').prop('disabled', false)
    }
    else{
        $('#negativePlateCode').prop('disabled', true)
    }

    if(value=='N/A'){
        $('#negativePlateQty').prop('disabled', true)
        $('#negativePlateQty').val(0)
    }
    else{
        $('#negativePlateQty').prop('disabled', false)
        $('#negativePlateQty').val('')
    }
})

$('#Classification').on('change', function(){
    var isValid = true;
    $('.error').text('');

    if (isValid) {
        $('.error').text('');
        console.log('Classification validated!');
    }
    var value = $(this).val()
    $('#positivePlateCode').val('')
    $('#negativePlateCode').val('')
    $('#positivePlateQty').val('')
    $('#negativePlateQty').val('')
    $('#positivePlateCode').prop('disabled', true)
    $('#negativePlateCode').prop('disabled', true)
    fetchPlateType()
    if(value=='9'){
        $('#ClassificationOtherDiv').removeClass('d-none')
    }
    else{
        $('#ClassificationOtherDiv').addClass('d-none')
    }
    if(value=='6'){
        $('#BrandNameInputFields').removeClass('d-none');
        $('#BrandName').addClass('d-none');
    }
    else{
        $('#BrandNameInputFields').addClass('d-none');
        $('#BrandName').removeClass('d-none');
    }
    batteryBrandSelection(value)
})

//----Checboxes ------------
$('#RCCheckBox').change(function(){
    if($(this).is(':checked')){
        console.log("RC 'n/a' Checkbox is checked");
        $('#Rc').val('')
        $('#Rc').prop('disabled', true)
    } else {
        console.log("RC 'n/a' Checkbox is unchecked");
        $('#Rc').prop('disabled', false)
    }
});

$('#AHCheckBox').change(function(){
    if($(this).is(':checked')){
        console.log("AH 'n/a' Checkbox is checked");
        $('#Ah').val('')
        $('#Ah').prop('disabled', true)
    } else {
        console.log("AH 'n/a' Checkbox is unchecked");
        $('#Ah').prop('disabled', false)
    }
});

$('#CCACheckBox').change(function(){
    if($(this).is(':checked')){
        console.log("CCA 'n/a' Checkbox is checked");
        $('#Cca').val('')
        $('#Cca').prop('disabled', true)
    } else {
        console.log("CCA 'n/a' Checkbox is unchecked");
        $('#Cca').prop('disabled', false)
    }
});

$('#C5CheckBox').change(function(){
    if($(this).is(':checked')){
        console.log("C5 'n/a' Checkbox is checked");
        $('#C5').val('')
        $('#C5').prop('disabled', true)
    } else {
        console.log("C5 'n/a' Checkbox is unchecked");
        $('#C5').prop('disabled', false)
    }
});

$('#CACheckBox').change(function(){
    if($(this).is(':checked')){
        console.log("Ca 'n/a' Checkbox is checked");
        $('#Ca').val('')
        $('#Ca').prop('disabled', true)
    } else {
        console.log("Ca 'n/a' Checkbox is unchecked");
        $('#Ca').prop('disabled', false)
    }
});

$('#SGCheckBox').change(function(){
    if($(this).is(':checked')){
        console.log("Sg 'n/a' Checkbox is checked");
        $('#Sg').val('')
        $('#Sg').prop('disabled', true)
    } else {
        console.log("Sg 'n/a' Checkbox is unchecked");
        $('#Sg').prop('disabled', false)
    }
});

$('#othersCheckBox').change(function(){
    if($(this).is(':checked')){
        console.log("Others 'n/a' Checkbox is checked");
        $('#OthersPower').val('')
        $('#OthersPower').prop('disabled', true)
    } else {
        console.log("Others 'n/a' Checkbox is unchecked");
        $('#OthersPower').prop('disabled', false)
    }
});
//----Checboxes ------------

$('#proceedTest').on('click', function(e){
    e.preventDefault()
    var isValid = true;
    $('.error').text('');

    var DisposalID = $('#disposalValue').val();
    if (DisposalID  == 0) {
      isValid = false;
      $('#disposalerror').text('Please select disposition.');
    }

    var selectedrequestor = $('#requestor').val();
    if (selectedrequestor  == null) {
      isValid = false;
      $('#requestorerror').text('Requestor cannot be empty.');
    }

    var selectedClassification = $('#Classification').val();
    if (selectedClassification  == null || selectedClassification  == 0) {
      isValid = false;
      $('#classificationError').text('Classification cannot be empty.');
    }

    var selectedProjectName = $('#ProjectName').val();
    if (selectedProjectName  == null || selectedProjectName  == '') {
      isValid = false;
      $('#projectnameerror').text('Project name cannot be empty.');
    }

    var selectedTestObjective = $('#Objective').val();
    if (selectedTestObjective  == null || selectedTestObjective  == '') {
      isValid = false;
      $('#objectiveerror').text('Objective cannot be empty.');
    }

    var selectedTestObjective = $('#Objective').val();
    if (selectedTestObjective  == null || selectedTestObjective  == '') {
      isValid = false;
      $('#objectiveerror').text('Objective cannot be empty.');
    }

    var selectedProductionCode = $('#PCode').val();
    if (selectedProductionCode  == null || selectedProductionCode  == '') {
      isValid = false;
      $('#productioncodeerror').text('Please input "N/A" if not applicable');
    }

    var selectedProductionCode = $('#PCode').val();
    if (selectedProductionCode  == null || selectedProductionCode  == '') {
      isValid = false;
      $('#productioncodeerror').text('Please input "N/A" if not applicable');
    }
    var selectedBrand = '';
    if(selectedClassification==6){
        selectedBrand = $('#BrandNameInputFields').val();
    }
    else{
        selectedBrand = $('#BrandName').val();
    }
    
    if (selectedBrand  == null || selectedBrand  == '') {
      isValid = false;
      $('#BrandNamecodeerror').text('Please input "N/A" if not applicable');
    }

    var selectedBType = $('#btype').val();
    if (selectedBType  == null || selectedBType  == 0) {
      isValid = false;
      $('#BTypeerror').text('Battery Type cannot be empty.');
    }

    var selectedBCode = $('#BCode').val();
    if (selectedBCode  == null || selectedBCode  == '') {
      isValid = false;
      $('#BCodeerror').text('Please input "N/A" if not applicable');
    }

    var selectedPositivePlate = $('#PositivePlate').val();
    if (selectedPositivePlate  == null || selectedPositivePlate  == 0) {
      isValid = false;
      $('#PositivePlateerror').text('Please input "N/A" if not applicable');
    }

    var selectedNegativePlate = $('#NegativePlate').val();
    if (selectedNegativePlate  == null || selectedNegativePlate  == 0) {
      isValid = false;
      $('#NegativePlateerror').text('Please input "N/A" if not applicable');
    }

    var selectedPositivePlateQty = $('#positivePlateQty').val();
    if (selectedPositivePlateQty  == null || selectedPositivePlateQty  == '') {
      isValid = false;
      $('#PositivePlateQtyerror').text('Quantity cannot be empty.');
    }

    var selectedNegativePlateQty = $('#negativePlateQty').val();
    if (selectedNegativePlateQty  == null || selectedNegativePlateQty  == '') {
      isValid = false;
      $('#NegativePlateQtyerror').text('Quantity cannot be empty.');
    }

    //----------With N/A checkboxes for optional null value
    var selectedRC = '';
    if ($('#RCCheckBox').is(':checked')) {
        selectedRC = $('#Rc_hidden').val();
    } else {
        selectedRC = $('#Rc').val();
        if (selectedRC  == null || selectedRC  == '') {
        isValid = false;
        $('#Rcerror').text('Please input "0" zero if not applicable.');
        }
    }

    var selectedAh = '';
    if ($('#AHCheckBox').is(':checked')) {
        selectedAh = $('#Ah_hidden').val();
    } else {
        selectedAh = $('#Ah').val();
        if (selectedAh  == null || selectedAh  == '') {
            isValid = false;
            $('#Aherror').text('Please input "0" zero if not applicable.');
        }
    }
    
    var selectedCca = '';
    if ($('#CCACheckBox').is(':checked')) {
        selectedCca = $('#Cca_hidden').val();
    } else {
        selectedCca = $('#Cca').val();
        if (selectedCca  == null || selectedCca  == '') {
            isValid = false;
            $('#Ccaerror').text('Please input "0" zero if not applicable.');
        }
    }
    
    var selectedC5 = '';
    if ($('#C5CheckBox').is(':checked')) {
        selectedC5 = $('#C5_hidden').val();
    } else {
        selectedC5 = $('#C5').val();
        if (selectedC5  == null || selectedC5  == '') {
            isValid = false;
            $('#C5error').text('Please input "0" zero if not applicable.');
        }
    }

    var selectedCa = '';
    if ($('#CACheckBox').is(':checked')) {
        selectedCa = $('#Ca_hidden').val();
    } else {
        selectedCa = $('#Ca').val();
        if (selectedCa  == null || selectedCa  == '') {
            isValid = false;
            $('#Caerror').text('Please input "0" zero if not applicable.');
        }
    }
    
    var selectedSg = '';
    if ($('#SGCheckBox').is(':checked')) {
        selectedSg = $('#Sg_hidden').val();
    } else {
        selectedSg = $('#Sg').val();
        if (selectedSg  == null || selectedSg  == '') {
        isValid = false;
        $('#Sgerror').text('Please input "0" zero if not applicable.');
        }
    }
    
    //---------With N/A checkboxes for optional null value End

    if (isValid) {
        $('.error').text('');
        Swal.fire({
        title: 'Test Plan?',
        text: 'Request details will save automatically',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Proceed',
        cancelButtonText: 'Cancel',
      }).then(result => {
            if (result.isConfirmed) {
            // Handle the case when the user clicks the "Save" button
            // This block will only execute if the condition is true and the action is successful
            AddRequestDetails()
            Swal.fire('Saved!', 'Your request details have been saved as draft.', 'success');
            $('#batteryDetails').slideUp();
            $('#batteryTest').slideDown();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Handle the case when the user clicks the "Cancel" button
            //   Swal.fire('Cancelled', 'Your request details have not been saved.', 'info');
            }
        });
        console.log('forms validated!');
    }
    else{
        Swal.fire('Data Validation', 'Please check empty field.', 'warning');
    }
    
})

function AddRequestDetails(){
    var DisposalID = $('.disposal').val() //Disposal Field value - required
    var ProjectName = $('#ProjectName').val() //ProjectName field value - required
    var Objective = $('#Objective').val() // Objective field value - required
    var Classification = $('#Classification').val() // Classifcation field value - required
    var RfsNo = $('#rfsNo').val()
    var ClassificationOther = $('#ClassificationOther').val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'addRequestDetails',
            DisposalID:DisposalID,
            ProjectName:ProjectName,
            Objective:Objective,
            Classification:Classification,
            ClassificationOther:ClassificationOther,
            RfsNo:RfsNo
        },
        success: function (data) {
            // alert(data.last_Id)
            var RequisitionId = data.last_Id
            AddRequest(RequisitionId)
            SpecialBatteryInstruction(RequisitionId)
            AddRequestorDetails(RequisitionId)
            AddBatteryDetails(RequisitionId, Classification)
            TestPlanDisplay(RequisitionId)
        }
        
    })
    // .done(function (data) {
    //     // Additional logic for successful completion
    //     var RequisitionId = data.last_Id
    //     AddBatteryPlateDetails(RequisitionId)
    //     AddBatteryPowerDetails(RequisitionId)
    // });
}

function AddRequest(lastInsertedID){
    var last_id = lastInsertedID
     $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'RequestGeneration',
            last_id:last_id
        },
        success: function (data) {
            
        }
     });
}

function AddRequestorDetails(lastInsertedID){
    var last_id = lastInsertedID
    var selectedRequestor = $('#requestor').val() // Requestors field value - required

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'addRequestorDetails',
            last_id:last_id,
            selectedRequestor:selectedRequestor
        },
        success: function (data) {
            // alert(data)
        }
    });
}

function AddBatteryDetails(lastInsertedID, ClassificationID){
    var last_id = lastInsertedID
    var classificationID = ClassificationID
    var BrandName = "";
    if(classificationID == '6'){
        BrandName = $('#BrandNameInputFields').val() // Brand Name field value - optional
    }
    else{
        BrandName = $('#BrandName').val() // Brand Name field value - optional
    }
    
    var BatteryType = $('#btype').val() // Battery Type field value - optional
    var ApplicationType = $('#Application').val()
    var BatterySize = $('#Bsize').val() //Battery Size field value - optional
    var BatterySizeOther = $('#othersInputBatterySize').val() 
    var BatteryCode = $('#BCode').val() // Battery Code field value - optional
    var ProductionCode = $('#PCode').val() // Production Code field value - optional


    var PositivePlaeSelectVal = $('#PositivePlate').val() // Positive PLate selection field - optional
    var PositivePlateCodeVal = $('#positivePlateCode').val() // Positive plate code field value - optional
    var PositivePlateQty = $('#positivePlateQty').val() // Positive Qty field value - optional
    
    var NegativePlaeSelectVal = $('#NegativePlate').val() // Negative PLate selection field - optional
    var NegativePlateCodeVal = $('#negativePlateCode').val() // Negative plate code field value - optional
    var NegativePlateQty = $('#negativePlateQty').val() // Negative Qty field value - optional

    var RCVal = $('#Rc').val() // RC Rating field value - required
    var AHVal = $('#Ah').val() // AH Rating field value - required
    var CCAVal = $('#Cca').val() //CCA Rating field value - required
    var C5Val = $('#C5').val() //C5 Rating field value - required
    var CAVal = $('#Ca').val() //CA Rating field value - required
    var SGVal = $('#Sg').val() // SG Rsting field value - required
    var OtherVal = $('#OthersPower').val() // Others field value - optional

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'addRequestBatteryDetails',
            last_id:last_id,
            BrandName:BrandName,
            BatteryType:BatteryType,
            BatterySize:BatterySize,
            BatterySizeOther:BatterySizeOther,
            ApplicationType:ApplicationType,
            BatteryCode:BatteryCode,
            ProductionCode:ProductionCode,

            PositivePlaeSelectVal:PositivePlaeSelectVal,
            PositivePlateCodeVal:PositivePlateCodeVal,
            PositivePlateQty:PositivePlateQty,

            NegativePlaeSelectVal:NegativePlaeSelectVal,
            NegativePlateCodeVal:NegativePlateCodeVal,
            NegativePlateQty:NegativePlateQty,

            RCVal:RCVal,
            AHVal:AHVal,
            CCAVal:CCAVal,
            C5Val:C5Val,
            CAVal:CAVal,
            SGVal:SGVal,
            OtherVal:OtherVal

        },
        success: function (data) {
            // alert(data)
        }
    });

    
}

function SpecialBatteryInstruction(lastInsertedID){
    var last_id = lastInsertedID
    var InstructionField = $('#SpecialInstruction').val() // Special Instruction field value - optional

    if(InstructionField != null || InstructionField != ''){
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "CustomerPhp_repository/index_repo.php",
            data: {
                action:'AddSpecialInstruction',
                last_id:last_id,
                InstructionField:InstructionField
    
            },
            success: function (data) {
                
            }
        });
    }

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
            $.ajax({
                type: "POST",
                url: "CustomerPhp_repository/index_repo.php",
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
        }
    });
}

$('.disposal').on('change', function() {
    if ($(this).prop('checked')) {
      // Uncheck all other checkboxes in the group
      var checkboxId = $(this).attr('id');
      console.log('Checkbox with ID ' + checkboxId + ' is checked.');
      console.log($(this).val())
      $('.disposal').not(this).prop('checked', false);
    }
})
//------------disposal selection block end-----------//
//------------Battery Type selection--------------//
function batterytypeSelectData(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'batterytypeData'
        },
        success: function (data) {
            
            let choices = $('#btype').html(data)
            let initChoice;
                for(let i=0; i<choices.length;i++) {
                    if (choices[i].classList.contains("multiple-remove")) {
                        initChoice = new Choices(choices[i],
                        {
                            delimiter: ',',
                            editItems: true,
                            maxItemCount: -1,
                            removeItemButton: true,
                        });
                    }else{
                        initChoice = new Choices(choices[i]);
                }
            }
        }
    });
}
//------------Battery Type selection end--------------//
function batteryBrandSelection(ClassificationID){
    var classID = ClassificationID
    
    var setter = 0;

    if(classID==3){
        setter = 1;
    }

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action: 'fetchBrandName',
            setter: setter
        },
        success: function (data) {
            $('#BrandName').html(data)
        }
    });
}
//------------Application----------------------//
$('#btype').on('change', function(){
    var id = $(this).val()
    ApplicationSelection(id)
})

function ApplicationSelection(BtypeID){
    var id = BtypeID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'applicationSelection',
            id:id
        },
        success: function (data) {
            $('#Application').html(data)
            var text = $('#Application').find(':selected').text()
            BatterySizeAddRequest(text)
        }
    });
}

$('#Application').on('change', function(){
    var text = $(this).find(':selected').text()
    BatterySizeAddRequest(text)
})

function BatterySizeAddRequest(ApplicationTxt){
    var text = ApplicationTxt

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'BatterySizeAddRequest',
            text:text
        },
        success: function (data) {
            $('#Bsize').html(data)
        }
    });
}
//------------Application end----------------------//
//------------requestor------------//
function requestorSelectData(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'requestorData'
        },
        success: function (data) {
            let choices = $('#requestor').html(data)
            let initChoice;
                for(let i=0; i<choices.length;i++) {
                    if (choices[i].classList.contains("multiple-remove")) {
                        initChoice = new Choices(choices[i],
                        {
                            delimiter: ',',
                            editItems: true,
                            maxItemCount: -1,
                            removeItemButton: true,
                        });
                    }else{
                        initChoice = new Choices(choices[i]);
                }
            }
        }
    });
}

function requestorSelectUpdateData(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'requestorData'
        },
        success: function (data) {
            let choices = $('#requestorUpdate').html(data)
            let initChoice;
                for(let i=0; i<choices.length;i++) {
                    if (choices[i].classList.contains("multiple-remove")) {
                        initChoice = new Choices(choices[i],
                        {
                            delimiter: ',',
                            editItems: true,
                            maxItemCount: -1,
                            removeItemButton: true,
                        });
                    }else{
                        initChoice = new Choices(choices[i]);
                }
            }
        }
    });
}
//------------requestor end--------//

//------------classification-------//
function classificationSelectData(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'classificationData'
        },
        success: function (data) {
            let choices = $('#Classification').html(data)
            let initChoice;
                for(let i=0; i<choices.length;i++) {
                    if (choices[i].classList.contains("multiple-remove")) {
                        initChoice = new Choices(choices[i],
                        {
                            delimiter: ',',
                            editItems: true,
                            maxItemCount: -1,
                            removeItemButton: true,
                        });
                    }else{
                        initChoice = new Choices(choices[i]);
                }
            }
        }
    });
}
//------------classification end-------//
function fetchPlateType(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'fetchPlateType'
        },
        success: function (data) {
            $('#PositivePlate').html(data.PostivePlateData)

            $('#NegativePlate').html(data.NegativePlateData)
        }
    });
}
//----------for fetching selecttion data field block end---------------//

function TestPlanDisplay(requestID){
    var id = requestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'TestPlanDisplay',
            id:id
        },
        success: function (data) {
            $('#testPlanDisplay').html(data)
        }
    });
}

//-------Modal Test holder-------------
function modalPop(str){
    var test = str
    if(test=='test1'){
        $('#Mseries').modal('show')
        fetchMseries()
    }
    else if(test=='test2'){
        $('#UserTest').modal('show')
        testSelection()
    }
    else if(test=='test3'){
        testSelection2()
        $('#SelectTest').modal('show')
    }
    else if(test=='test4'){
        $('#benchMark').modal('show')
    }
}

//-------Modal Test holde endr-------------

//-----------M Series Test-------------

function fetchMseries(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'fetchMseries'
        },
        success: function (data) {
            $('#MTestLayOut').html(data)

            $('.form-check-input').change(function() {
                var isChecked = $(this).is(':checked');
                var field = $(this).closest('tr').find('input[type="number"]');
                var select = $(this).closest('tr').find('.form-select');
                var row = $(this).closest('tr');
                var rowIndex = row.index() + 1;
                
                if (isChecked) {
                  // Checkbox is checked
                //   alert("Checked and value")
                  // Perform any additional actions
                  field.prop('disabled', false);
                  select.prop('disabled', false);
            
                  
                } else {
                  // Checkbox is unchecked
                  // Perform any additional actions
                  field.prop('disabled', true);
                  select.prop('disabled', true);
                }
            
              });
        }
    });
}

$('#submitMTest').on('click', function(){
    var qtyHolder = []
    var IsNoZeroQty = true;
    var IsNotZero = false
    var CheckedData = new FormData();
    var remarks = $('#testMtestRemarks').val()
    CheckedData.append("action", "MtestData");
    CheckedData.append("remarks", remarks);
    $('.form-check-input:checked').each(function(){
        var row = $(this).closest('tr');
        var rowIndex = row.index() + 1;
        var rowDataStd;
        var rowDataQty;

        row.find('.form-select').each(function() {
            var data = $(this).val();
            rowDataStd = data;
        });

        // row.find('.qytfield').each(function() {
        //     var data = $(this).val();
        //     rowDataQty = data;
        // });
        row.find('.qytfield').each(function() {
            var data = $(this).val();
            // Check if the value is null or zero
            if (data === null || data === 0 || data.trim() === "") {
                // Handle the case where the value is null or zero
                console.log('Null or zero value in QTY[] at row ' + rowIndex);
                qtyHolder.push(data);
            }

            rowDataQty = data;
        });

        CheckedData.append("rowIndex[]", rowIndex);
        CheckedData.append("STD[]", rowDataStd);
        CheckedData.append("QTY[]", rowDataQty);
    })
    
    for(var i=0; i < qtyHolder.length; i++){
        if (qtyHolder[i] === null || qtyHolder[i] === 0 || qtyHolder[i].trim() === "") {
            IsNoZeroQty = false;
            break;
        }
    }
    // console.log("Checked Rows:");
    var checkDataCount = CheckedData.getAll("rowIndex[]").length;
    if(checkDataCount==0){
        console.log("Please select test series")
    }
    else{
        IsNotZero = true
    }

    if(IsNotZero){
        console.log('proceed')
        if(IsNoZeroQty){
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "CustomerPhp_repository/index_repo.php",
                data: {
                    action:'MtestGenerateTestPlan',
                },
                success: function (data) {
                    MtestDataInsert(data.TestPlanID, data.RequestID, CheckedData)
                }   
            });
        }
        else{
            Swal.fire('Data Validation', 'Quantity cannot be empty.', 'warning');
        }
    }
    else{
        console.log('cannot proceed at this time')
        Swal.fire('Data Validation', 'Please select test series.', 'warning');
    }

})

$('#submitMTest2').on('click', function(){
    var qtyHolder = []
    var IsNoZeroQty = true;
    var IsNotZero = false
    var requestID = $('#RequestID_Holder').val()
    var CheckedData = new FormData();
    var remarks = $('#testMtestRemarks').val()
    CheckedData.append("action", "MtestData2");
    CheckedData.append("remarks", remarks);
    $('.form-check-input:checked').each(function(){
        var row = $(this).closest('tr');
        var rowIndex = row.index() + 1;
        var rowDataStd;
        var rowDataQty;

        row.find('.form-select').each(function() {
            var data = $(this).val();
            rowDataStd = data;
        });

        // row.find('.qytfield').each(function() {
        //     var data = $(this).val();
        //     rowDataQty = data;
        // });
        row.find('.qytfield').each(function() {
            var data = $(this).val();
            // Check if the value is null or zero
            if (data === null || data === 0 || data.trim() === "") {
                // Handle the case where the value is null or zero
                console.log('Null or zero value in QTY[] at row ' + rowIndex);
                qtyHolder.push(data);
                IsNoZeroQty = false;
            }
            rowDataQty = data;
        });

        CheckedData.append("rowIndex[]", rowIndex);
        CheckedData.append("STD[]", rowDataStd);
        CheckedData.append("QTY[]", rowDataQty);
    })

    for(var i=0; i < qtyHolder.length; i++){
        if (qtyHolder[i] === null || qtyHolder[i] === 0 || qtyHolder[i].trim() === "") {
            IsNoZeroQty = false;
            break;
        }
    }
    // console.log("Checked Rows:");
    var checkDataCount = CheckedData.getAll("rowIndex[]").length;
    if(checkDataCount==0){
        console.log("Please select test series")
    }
    else{
        IsNotZero = true
    }


    // var qtyValues = CheckedData.getAll("QTY[]");

    // // Check if all qty values are either zero or null
    // var allZeroOrNull = qtyValues.every(function(val) {
    //     return val === null || val === '0';
    // });

    // if (allZeroOrNull) {
    //     console.log('All QTY[] values are either zero or null.');
    //     IsNoZeroQty = false
    // } else {
    //     console.log('QTY[] values contain non-zero or non-null values.');
    //     IsNoZeroQty = true
    // }

    if(IsNotZero){
        console.log('proceed')
        if(IsNoZeroQty){
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "CustomerPhp_repository/index_repo.php",
                data: {
                    action:'MtestGenerateTestPlan2',
                    requestID:requestID
                },
                success: function (data) {
                    MtestDataInsert(data.TestPlanID, data.RequestID, CheckedData)
                }   
            });
        }
        else{
            Swal.fire('Data Validation', 'Quantity cannot be empty.', 'warning');
        }
        
    }
    else{
        console.log('cannot proceed at this time')
        Swal.fire('Data Validation', 'Please select test series.', 'warning');
    }

})

function MtestDataInsert(TestPlanID, RequestID, CheckedData){
    var formData = new FormData();
    var testPlanID = TestPlanID
    var testrequestID = RequestID

    passFormData(CheckedData, formData)
    removeUndefinedFields(formData)

    formData.append("testPlanID", testPlanID);
    formData.append("requestID", testrequestID);


    $.ajax({
        type: "POST",
        dataType: "JSON",
        contentType: false,
        processData: false,
        url: "CustomerPhp_repository/index_repo.php",
        data: formData,
        success: function (data) {
            console.log(data.requestID)
            TestPlanDisplay(data.requestID)
            $('#Mseries').modal('hide')
        }   
    });

}

function passFormData(sourceFormData, destinationFormData) {
    for (const [key, value] of sourceFormData.entries()) {
      destinationFormData.append(key, value);
    }
}

function removeUndefinedFields(formData) {
    for (const [key, value] of formData.entries()) {
        if (value === undefined || value === 0) {
          formData.delete(key);
        }
    }
}

function DeleteMtest(testplanID, TestRequestID){
    var id = testplanID
    var requestID = TestRequestID

    Swal.fire({
        title: 'Test Plan Deletion',
        text: 'Do you want to delete this test plan?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Proceed',
        cancelButtonText: 'Cancel',
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "CustomerPhp_repository/index_repo.php",
                data: {
                    action:'deleteTestPlan',
                    id:id,
                    requestID:requestID
                },
                success: function (data) {
                    TestPlanDisplay(requestID)
                    // alert(data)
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {

        }
    });

}
//-----------M Series Test End-------------

//-----------User Test-----------------
function testSelection(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'TestSelection'
        },
        success: function (data) {
            // Assuming UserTestSelect is the ID of your select element
            var userTestSelect = $('#UserTestSelect');
            // Populate the select element with data
            userTestSelect.html(data);
            // Remove the 'd-none' class from the alert
            $('#userTestAlert').removeClass('d-none');
        }
    });
}

var selectedTestArray = [];
var index = 0;
var indexArray = [];
var selectedStepTestArray = [];
var testCombination = [];
$('#UserTestSelect').on('change', function(){
    var selectedValue = $(this).val()
    
    if(selectedValue){
        selectedTestArray.push(selectedValue);
        indexArray.push(index)
        cyclesModal(selectedValue, index)

        index ++;
    }
    
})

function cyclesModal(selectedValue, index){
    var indexID = index
    var valueSelected = selectedValue
    $('#indexFormID').val(indexID)
    $('#selectedTestModalValue').val(valueSelected)
    $('#cyclesModal').modal('show')
}

function deleteTestOption(id){
    var indexId = id
    console.log(indexId)
}

$('#CyclesBtn').on('click', function(){
    var selectedValue = $('#selectedTestModalValue').val()
    var cyclesQty = $('#CycleQuantity').val()
    var indexVal = $('#indexFormID').val()
    console.log(indexVal)
    for(var x = 0; x<cyclesQty; x++){
        selectedStepTestArray.push({
            indexVal: indexVal,
            selectedValue: selectedValue
        })
    }

    console.log(indexArray)
    console.log(selectedTestArray)
    console.log(selectedStepTestArray)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'USerTestStepTable',
            selectedValue:selectedTestArray,
            selectedStepTestArray:selectedStepTestArray,
            cyclesQty:cyclesQty
        },
        success: function (data) {
            $('#UserTestSelectLayout').html(data.output)
            $('.UserSelectTestField').append(data.output1)
            console.log(data.output1)
            var result = JSON.parse(data.setter)
            if(result==1){
                $('#userTestAlert').addClass('d-none')
            }
            else{
                $('#userTestAlert').removeClass('d-none')
            }

            $('#cyclesModal').modal('hide')
        }
    });
})

$('#submitUserTest').on('click', function(){
    var rows = $('#table2 tbody tr');
    var remarks = $('#test2Remarks').val()
    var qty = $('#test2Qty').val()
    var setter = 0;
    var formData = new FormData();
    formData.append("action", "UsertestGenerateTestPlan");
    rows.each(function() {

        var indexVal = $(this).find('.indexVal').text();
        var hiddenValue = $(this).find('input[type="hidden"]').val();
        var notesValue = $(this).find('input[type="text"]').val();
        var tempValue = $(this).find('input[type="number"]').val();
        var selectValue = $(this).find('select').val();
        var fileInput =$(this).find('input[type="file"]')[0].files[0];

        if ($(this).find('input[type="file"]')[0].files.length === 0) {
            setter = 0;
        }
        else{
            console.log("having files in index = "+indexVal);
            setter = 1;
        }

        if (fileInput === undefined) {
            // Set a default value (e.g., an empty string) for the file input
            fileInput = ''; // Or you can set a placeholder file or any other default value you prefer
        }

        formData.append("index[]", indexVal);
        formData.append("TestID[]", hiddenValue);
        formData.append("Notes[]", notesValue);
        formData.append("temp[]", tempValue);
        formData.append("std[]", selectValue);
        formData.append("setter[]", setter);
        formData.append("files[]", fileInput);
    });

    formData.append("remarks", remarks);
    formData.append("qty", qty);

    var fileCount = formData.getAll("index[]").length;

    if(fileCount==0){
        console.log("Please select test series")
        Swal.fire('Data Validation', 'Please select test series.', 'warning');
    }
    else{
        console.log("formData has "+ fileCount + " Index Data")
        console.log("Proceed to insert")
        if(qty === null || qty === 0 || qty.trim() === ""){
            Swal.fire('Data Validation', 'Quantity cannot be empty or zero.', 'warning');
        }
        else{
            $.ajax({
                type: "POST",
                dataType: "JSON",
                contentType: false,
                processData: false,
                url: "CustomerPhp_repository/index_repo.php",
                data: formData,
                success: function (data) {
                    console.log(data.requestID)
                    TestPlanDisplay(data.requestID)
                    $('#UserTest').modal('hide')
                }
            })
        }
    }
  
})

$('#submitUserTestEdit').on('click', function(){
    var requestID = $('#RequestID_Holder').val()
    var rows = $('#table2 tbody tr');
    var remarks = $('#test2Remarks').val()
    var qty = $('#test2Qty').val()
    var setter = 0;
    var formData = new FormData();
    formData.append("action", "UsertestGenerateTestPlanEdit");
    rows.each(function() {

        var indexVal = $(this).find('.indexVal').text();
        var hiddenValue = $(this).find('input[type="hidden"]').val();
        var notesValue = $(this).find('input[type="text"]').val();
        var tempValue = $(this).find('input[type="number"]').val();
        var selectValue = $(this).find('select').val();
        var fileInput =$(this).find('input[type="file"]')[0].files[0];

        if ($(this).find('input[type="file"]')[0].files.length === 0) {
            setter = 0;
        }
        else{
            console.log("having files in index = "+indexVal);
            setter = 1;
        }

        if (fileInput === undefined) {
            // Set a default value (e.g., an empty string) for the file input
            fileInput = ''; // Or you can set a placeholder file or any other default value you prefer
        }

        formData.append("index[]", indexVal);
        formData.append("TestID[]", hiddenValue);
        formData.append("Notes[]", notesValue);
        formData.append("temp[]", tempValue);
        formData.append("std[]", selectValue);
        formData.append("setter[]", setter);
        formData.append("files[]", fileInput);
    });

    formData.append("remarks", remarks);
    formData.append("qty", qty);
    formData.append("RequestID", requestID);
    

    var fileCount = formData.getAll("index[]").length;

    if(fileCount==0){
        console.log("Please select test series")
        Swal.fire('Data Validation', 'Please select test series.', 'warning');
    }
    else{
        console.log("formData has "+ fileCount + " Index Data")
        console.log("Proceed to insert")
        if(qty === null || qty === 0 || qty.trim() === ""){
            Swal.fire('Data Validation', 'Quantity cannot be empty or zero.', 'warning');
        }
        else{
            $.ajax({
                type: "POST",
                dataType: "JSON",
                contentType: false,
                processData: false,
                url: "CustomerPhp_repository/index_repo.php",
                data: formData,
                success: function (data) {
                    console.log(data.requestID)
                    TestPlanDisplay(data.requestID)
                    $('#UserTest').modal('hide')
                }
            })
        }
    }
  
})

//-----------User Test End--------------

//-----------Select Test-----------------
function testSelection2(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'TestSelection'
        },
        success: function (data) {
            // Assuming UserTestSelect is the ID of your select element
            var userTestSelect2 = $('#UserTestSelect2');

            // Populate the select element with data
            userTestSelect2.html(data);

            // Remove the 'd-none' class from the alert
            $('#userTestAlert').removeClass('d-none');

            // Check if Select2 is already initialized
            if (!userTestSelect2.hasClass("select2-hidden-accessible")) {
                // Initialize Select2
                userTestSelect2.select2({
                    width: 'resolve',
                    theme: "classic",
                    multiple: true
                });
            } else {
                // If already initialized, trigger an update
                userTestSelect2.trigger('change');
            }
        }
    });
    
}

$('#UserTestSelect2').on('change', function(){
    var selectedValue = $(this).val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'USerTestStepTable2',
            selectedValue:selectedValue
        },
        success: function (data) {
            $('#UserTestSelectLayout3').html(data.output)
            var result = JSON.parse(data.setter)
            if(result==1){
                $('#userTestAlert2').addClass('d-none')
            }
            else{
                $('#userTestAlert2').removeClass('d-none')
            }
        }
    });
})

$('#submitUserTest2').on('click', function(){
    var rows = $('#table3 tbody tr');
    var remarks = $('#test3Remarks').val()
    var qty = $('#test3Qty').val()
    var setter = 0;
    var formData = new FormData();
    formData.append("action", "UsertestGenerateTestPlan2");
    rows.each(function() {
       
        var hiddenValue = $(this).find('input[type="hidden"]').val();
        formData.append("TestID[]", hiddenValue);
  
    });

    formData.append("remarks", remarks);
    formData.append("qty", qty);

    var Count = formData.getAll("TestID[]").length;

    if(Count==0){
        console.log("Please select test series")
        Swal.fire('Data Validation', 'Please select test series.', 'warning');
    }
    else{
        console.log("formData has "+ Count + " Data")
        console.log("Proceed to insert")
        if(qty === null || qty === 0 || qty.trim() === ""){
            Swal.fire('Data Validation', 'Quantity cannot be empty or zero.', 'warning');
        }
        else{
            $.ajax({
                type: "POST",
                dataType: "JSON",
                contentType: false,
                processData: false,
                url: "CustomerPhp_repository/index_repo.php",
                data: formData,
                success: function (data) {
                    console.log(data.requestID)
                    TestPlanDisplay(data.requestID)
                    $('#SelectTest').modal('hide')
                }
            })
        }
    }
  
})

$('#submitUserTest2Edit').on('click', function(){
    var rows = $('#table3 tbody tr');
    var remarks = $('#test3Remarks').val()
    var requestID = $('#RequestID_Holder').val()
    var qty = $('#test3Qty').val()
    var setter = 0;
    var formData = new FormData();
    formData.append("action", "UsertestGenerateTestPlan2Edit");
    rows.each(function() {
       
        var hiddenValue = $(this).find('input[type="hidden"]').val();
        formData.append("TestID[]", hiddenValue);
  
    });

    formData.append("remarks", remarks);
    formData.append("qty", qty);
    formData.append("RequestID", requestID);

    var Count = formData.getAll("TestID[]").length;

    if(Count==0){
        console.log("Please select test series")
        Swal.fire('Data Validation', 'Please select test series.', 'warning');
    }
    else{
        console.log("formData has "+ Count + " Data")
        console.log("Proceed to insert")
        if(qty === null || qty === 0 || qty.trim() === ""){
            Swal.fire('Data Validation', 'Quantity cannot be empty or zero.', 'warning');
        }
        else{
            $.ajax({
                type: "POST",
                dataType: "JSON",
                contentType: false,
                processData: false,
                url: "CustomerPhp_repository/index_repo.php",
                data: formData,
                success: function (data) {
                    console.log(data.requestID)
                    TestPlanDisplay(data.requestID)
                    $('#SelectTest').modal('hide')
                }
            })
        }
    }
  
})
//-----------Select Test end-----------------

//-----------Benchmarking-----------------
$('#submitUserTest3').on('click', function(){

    var remarks = $('#test4Remarks').val()
    var qty = $('#benchmarkQty').val()
    var setter = 0;
    var formData = new FormData();
    formData.append("action", "UsertestGenerateTestPlan3");

    formData.append("remarks", remarks);
    formData.append("qty", qty);

    if(qty === null || qty === 0 || qty.trim() === ""){
        Swal.fire('Data Validation', 'Quantity cannot be empty or zero.', 'warning');
    }
    else{
        console.log("Proceed to insert")

        $.ajax({
            type: "POST",
            dataType: "JSON",
            contentType: false,
            processData: false,
            url: "CustomerPhp_repository/index_repo.php",
            data: formData,
            success: function (data) {
                console.log(data.requestID)
                TestPlanDisplay(data.requestID)
                $('#benchMark').modal('hide')
            }
        })
        
    }
  
})

$('#submitUserTest3Edit').on('click', function(){
    var requestID = $('#RequestID_Holder').val()
    var remarks = $('#test4Remarks').val()
    var qty = $('#benchmarkQty').val()
    var setter = 0;
    var formData = new FormData();
    formData.append("action", "UsertestGenerateTestPlan3Edit");

    formData.append("remarks", remarks);
    formData.append("qty", qty);
    formData.append("RequestID", requestID);

    if(qty === null || qty === 0 || qty.trim() === ""){
        Swal.fire('Data Validation', 'Quantity cannot be empty or zero.', 'warning');
    }
    else{
        console.log("Proceed to insert")

        $.ajax({
            type: "POST",
            dataType: "JSON",
            contentType: false,
            processData: false,
            url: "CustomerPhp_repository/index_repo.php",
            data: formData,
            success: function (data) {
                console.log(data.requestID)
                TestPlanDisplay(data.requestID)
                $('#benchMark').modal('hide')
            }
        })
        
    }
  
})
//-----------Boostcharging end-------------

//----------Review request-----------------
$('#review_request').on('click', function(){
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'ReviewRequest'
        },
        success: function (data) {
            $('#ReviewBody').html(data)
            $('#ReviewModal').modal('show')
        }
    });
})
//----------Review request end-------------

//---------Save request and update RequestID to Request Format
$('#SaveRequest').on('click', function(){
    $(this).prop('disabled', true)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'SaveRequest'
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire({
                    title: 'Succesfull',
                    text: 'Request successfully submitted',
                    icon: 'success',
                    timer: 1000, // Time in milliseconds
                    timerProgressBar: true,
                    showConfirmButton: false
                  }).then((result) => {
                    // This code will run when the timer completes
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $(this).prop('disabled', false)
                        $(this).html('<i class="bx bx-check d-block d-sm-none"></i><i class="d-none d-sm-block fa fa-spinner fa-spin"></i>')
                        location.href='index.php';
                    }
                  });
            }
            else if(result==2){
                Swal.fire('Test Plan', 'Please provide test plan for this request', 'warning');
            }
            else{
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        }
    });
})
//---------Save request and update RequestID to Request Format end

//---------Save draft request
$('#SaveDraft').on('click', function(){
    Swal.fire({
        title: 'Save as draft?',
        // text: 'Request details will save automatically',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',
        }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Succesfull',
                text: 'Request successfully save',
                icon: 'success',
                timer: 1000, // Time in milliseconds
                timerProgressBar: true,
                showConfirmButton: false
            }).then((result) => {
                // This code will run when the timer completes
                if (result.dismiss === Swal.DismissReason.timer) {
                    location.href='index.php';
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {

        }
    });
})
//---------Save draft request end

$('#CloseTestPlan').on('click', function(){
    alert("alert for closing the test plan")
})

function Request_tbl(){
    $('#Request_tbl').DataTable().destroy()
    var dataTable = $('#Request_tbl').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "bSort": true,
        "bInfo":true,
        "searching":true,
        "order" : [],

        "ajax" : {
            url: "CustomerPhp_repository/index_repo.php",
            type: "POST",
            data:{
                action:'Request_tbl'
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
                "targets": 7, // Target the first column (index 0)
                "orderable": false // Enable sorting for the first column
            }
        ],
    })
}

function EditDraftRequest(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'editRequest',
        },
        success: function (data) {
            
        }
    });
    fetchDisposal(requestID)
    fetchEditFieldDetail(requestID)
    fetchRequestor(requestID)
    fetchClassification(requestID)
    fetchEditBType(requestID)
    fetchEditApplication(requestID)
    fetchEditBatterySize(requestID)
    fetchPositivePlate(requestID)
    fetchNegativePlate(requestID)
    TestPlanDisplay(requestID)
    $('#RequestID_Holder').val(requestID)
    $('#EditApplication').prop('disabled', true)
    $('#EditBsize').prop('disabled', true)
    $('#EditRequestModal').modal('show')
}

function fetchDisposal(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'FetchEditDisposal',
            requestID:requestID
        },
        success: function (data) {
            var disposalID = JSON.parse(data)
            if(disposalID==1){
                $('#checkbox1').prop('checked', true)
                $('#checkbox2').prop('checked', false)
            }
            else{
                $('#checkbox2').prop('checked', true)
                $('#checkbox1').prop('checked', false)
            }
        }
    });
}

function fetchEditFieldDetail(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'FetchFieldEditDetails',
            requestID:requestID
        },
        success: function (data) {
            $('#ProjectNameBadges').html(data.projectName)
            $('#ObjectiveBadges').html(data.testObjective)
            $('#ProdCodeBadges').html(data.productionCode)
            $('#BrandBadges').html(data.batteryBrand)
            $('#BCodeBadges').html(data.batteryCode)
            $('#positivePlateQtyBadges').html(data.positivePlateQty)
            $('#negativePlateQtyBadges').html(data.negativePlateQty)
            $('#RCBadges').html(data.RC)
            $('#AHBadges').html(data.AH)
            $('#CCABadges').html(data.CCA)
            $('#C5Badges').html(data.C5)
            $('#CABadges').html(data.CA)
            $('#SGBadges').html(data.SG)
            $('#OthersBadges').html(data.others)
        }
    });
}

function ChangeField(RequestID, FieldTxt, ModalTitle, IsInt){
    var requestID = RequestID
    var fieldTxt =  FieldTxt
    var modalTitle = ModalTitle
    var DataType = IsInt

    if(DataType!=0){
        $('#FieldValueString').addClass('d-none')
        $('#FieldValueInt').removeClass('d-none')
    }
    else{
        $('#FieldValueInt').addClass('d-none')
        $('#FieldValueString').removeClass('d-none')
    }

    $('#selectedFormTitle').text(modalTitle)
    $('#editFieldRequestID_holder').val(requestID)
    $('#fieldTxt').val(fieldTxt)
    $('#IsInt').val(DataType)
    $('#EditChangeField').modal('show')
}

$('#EditFieldValueBtn').on('click', function(){
    var type = $('#IsInt').val()
    var requestID = $('#editFieldRequestID_holder').val()
    var fieldTxt = $('#fieldTxt').val()
    var value = '';
    if(type !=0){
        value = $('#FieldValueInt').val()
    }
    else{
        value = $('#FieldValueString').val()
    }
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'SaveEditField',
            requestID:requestID,
            fieldTxt:fieldTxt,
            value:value
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                if(type !=0){
                    $('#FieldValueInt').val('')
                }
                else{
                    $('#FieldValueString').val('')
                }
                fetchEditFieldDetail(requestID)
                $('#EditChangeField').modal('hide')
            }
        }
    });
})

function fetchRequestor(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'FetchEditRequestor',
            requestID:requestID
        },
        success: function (data) {
            $('#requestorBadges').html(data)
        }
    });
}

function RemoveRequestor(RequestorID, RequestID){
    var requestorID = RequestorID
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'RemoveRequestor',
            requestorID:requestorID
        },
        success: function (data) {
            console.log(data)
        }
    });
    fetchRequestor(requestID)
}

function AddRequestorModal(RequisiitonID){
    var requiID = RequisiitonID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'fetchRequestorEditData'
        },
        success: function (data) {
            $('#formCheckEdit').html(data)
        }
    });
    $('#requiID').val(requiID)
    $('#EditAddRequestor').modal('show')
}

$('#AddEditedRequestor').on('click', function(){
    var checkedValues = [];
    var requestID = $('#requiID').val()
    $(".form-check-input2:checked").each(function () {
        var checkboxValue = $(this).val();
        console.log("Processing checkbox with value:", checkboxValue);
        checkedValues.push(checkboxValue);
    });
    console.log("Checked Values: ", checkedValues);

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'EditAddSelectedRequestor',
            requestID:requestID,
            checkedValues:checkedValues
        },
        success: function (data){
            // var result = JSON.parse(data);
            // if(result==0){
            //     alert('No requestor selected')
            // }
            console.log(data)
            $('#EditAddRequestor').modal('hide')
            fetchRequestor(requestID)
        }
    });
    
})

function fetchClassification(RequestID){
    var requestID = RequestID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'FetchClassification',
            requestID:requestID
        },
        success: function (data) {
            $('#ClassificationBadges').html(data)
        }
    });
}

function ChangeClassfication(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {action:'ChangeClassification'},
        success: function (data) {
            $('#formCheckEditClass').html(data)
            $(".formCheck2").change(function () {
                // Get the value and set the checked property accordingly
                var isChecked = $(this).prop("checked");
                
                // Uncheck the other checkbox
                $(".formCheck2").not(this).prop("checked", !isChecked);
            });
        }
        
    });
    $('#RequestID_holderForClassification').val(requestID)
    $('#EditChangeClass').modal('show')
}

$('#EditClassification').on('click', function(){
    var checkedValue = $(".formCheck2:checked").val();
    var requestID = $('#RequestID_holderForClassification').val()
    console.log("Checked Value: ", checkedValue);

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'SelectedClassification',
            checkedValue:checkedValue,
            requestID:requestID
        },
        success: function (data) {
            console.log(data)
            fetchClassification(data.ID)
            $('#EditChangeClass').modal('hide')
        }
    });
})

function fetchEditBType(RequestID){
    var requestID = RequestID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'EditBType',
            requestID:requestID
        },
        success: function (data) {
            $('#EditBType').html(data)
        }
    });
}

function fetchEditApplication(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'fetchApplication',
            requestID:requestID
        },
        success: function (data) {
            $('#EditApplication').html(data)
        }
    });
}

$('#EditBType').on('change', function(){
    $('#EditApplication').prop('disabled', false)
    var BattTypeID = $(this).val()
    var requestID = $('#RequestID_Holder').val()
    
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'UpdateAndRefetchApplication',
            BattTypeID:BattTypeID,
            requestID:requestID
        },
        success: function (data) {
            $('#EditApplication').html(data.output)
        }
    });
})

function fetchEditBatterySize(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'fetchBatterySizesData',
            requestID:requestID
        },
        success: function (data) {
            $('#EditBsize').html(data)
        }
    });
}

$('#EditApplication').on('change', function(){
    $('#EditBsize').prop('disabled', false)
    var ApplicationID = $(this).val()
    var requestID = $('#RequestID_Holder').val()
    
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'UpdateAndRefetchBattSize',
            ApplicationID:ApplicationID,
            requestID:requestID
        },
        success: function (data) {
            $('#EditBsize').html(data.output)
        }
    });
})

$('#EditBsize').on('change', function(){
    var BatterySizeID = $(this).val()
    var requestID = $('#RequestID_Holder').val()

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'EditBatterySize',
            BatterySizeID:BatterySizeID,
            requestID:requestID
        },
        success: function (data) {
            console.log(data)
        }
    });
})

function fetchPositivePlate(RequestID){
    var requestID = RequestID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'FetchPositivePlate',
            requestID:requestID
        },
        success: function (data) {
            $('#PositivePlateCodeBadges').html(data)
        }
    });
}

function fetchNegativePlate(RequestID){
    var requestID = RequestID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'FetchNegativePlate',
            requestID:requestID
        },
        success: function (data) {
            $('#NegativePlateCodeBadges').html(data)
        }
    });
}

function ChangePlateType(RequestID, PolarityID){
    var requestID = RequestID
    var polarityID = PolarityID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'ChangePlateType',
            polarityID:polarityID
        },
        success: function (data) {
            $('#selectTitle').text(data.Title)
            $('#formCheckEditPlateType').html(data.output)
            $(".formCheck3").change(function () {
                // Get the value and set the checked property accordingly
                var isChecked = $(this).prop("checked");
                
                // Uncheck the other checkbox
                $(".formCheck3").not(this).prop("checked", !isChecked);
            });
        }
        
    });
    $('#PolarityID_holder').val(polarityID)
    $('#RequestID_holderForPlateType').val(requestID)
    $('#EditChangePlateType').modal('show')
}

$('#EditPlateTypeBtn').on('click', function(){
    var checkedValue = $(".formCheck3:checked").val();
    var requestID = $('#RequestID_holderForPlateType').val()
    var PolarityID = $('#PolarityID_holder').val()
    console.log("Checked Value: ", checkedValue);

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'SelectedPlateType',
            checkedValue:checkedValue,
            requestID:requestID,
            PolarityID:PolarityID
        },
        success: function (data) {
            console.log(data)
            if(PolarityID==1){
                fetchNegativePlate(data.ID)
            }
            else{
                fetchPositivePlate(data.ID)
            }
            $('#EditChangePlateType').modal('hide')
        }
    });
})

function DeleteDraftRequest(RequestID){
    var requestID = RequestID
    Swal.fire({
        title: 'Do you want to delete?',
        // text: 'Request details will save automatically',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "CustomerPhp_repository/index_repo.php",
                data: {
                    action:'DeleteDraftRequest',
                    requestID:requestID
                },
                dataType: "JSON",
                success: function (data) {
                    var result = JSON.parse(data)
                    if(result==1){
                        Request_tbl()
                    }
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
        }
    });
}

function ViewRequest(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'BTR_ViewRequest',
            requestID:requestID
        },
        success: function (data) {
            $('#BTRViewBody').html(data)
            ViewBtrButtonControlChange(requestID)
            $('#ViewBTR').modal('show')
        }
    });
}

function ViewEditRequest(RequestID){
    var requestID = RequestID
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'BTR_ViewRequest',
            requestID:requestID
        },
        success: function (data) {
            $('#ReviewBody').html(data)
            $('#ReviewModal').modal('show')
            $('#EditRequestModal').modal('hide')
        }
    });
}

$('#reviewEditTest').on('click', function(){
    var requestID = $('#RequestID_Holder').val()
    ViewEditRequest(requestID)
})

$('#SaveDraftEdit').on('click', function(){
    var requestID = $('#RequestID_ref').val()
    EditDraftRequest(requestID)
    $('#ReviewModal').modal('hide')
})

$('#SaveRequestEdit').on('click', function(){
    var RequestID = $('#RequestID_ref').val()

    $.ajax({
        type: "POST",
        url: "CustomerPhp_repository/index_repo.php",
        dataType: "JSON",
        data: {
            action:'SaveDraftEditRequest',
            RequestID:RequestID
        },
        success: function (data) {
            var result = JSON.parse(data)
            if(result==1){
                Swal.fire({
                    title: 'Succesfull',
                    text: 'Request successfully submitted',
                    icon: 'success',
                    timer: 1000, // Time in milliseconds
                    timerProgressBar: true,
                    showConfirmButton: false
                  }).then((result) => {
                    // This code will run when the timer completes
                    if (result.dismiss === Swal.DismissReason.timer) {
                        Request_tbl()
                        $('#ReviewModal').modal('hide')
                        $('#EditRequestModal').modal('hide')
                    }
                  });
            }
            else if(result==2){
                Swal.fire('Test Plan', 'Please provide test plan for this request', 'warning');
            }
            else{
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        }
    });
})

function ViewBtrButtonControlChange(RequestID){
    var ID = RequestID

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'ViewBtrFooterBtnContent',
            ID:ID
        },
        success: function (data) {
            $('#viewBtrFooter').html(data)
        }
    });
}

function GenerateQR(RequestSysID){
    var SystemBtrID = RequestSysID
    $('#qr').empty();

    $('#qr').qrcode({
        width: 175,
        height: 175,
        text: SystemBtrID
    })

    var canvas = document.querySelector("#qr canvas");
    var dataURL = canvas.toDataURL("image/png");
    console.log(dataURL);

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "CustomerPhp_repository/index_repo.php",
        data: {
            action:'qr',
            SystemBtrID:SystemBtrID,
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
            $('#my_qr').attr('src',data.imageData);
            $('#requestID').val(data.requestID)
            $('#RequestSysID').text(SystemBtrID)
            $('#QrViewer').modal('show')
            $('#ViewBTR').modal('hide')
        }
    });
}

$('#QrModalclose').on('click', function(){
    var requestID = $('#requestID').val()
    ViewRequest(requestID)
    $('#QrViewer').modal('hide')
})

function printBTR(){

}
