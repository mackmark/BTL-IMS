<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTL-LIMS | Print Interface</title>
    <link rel="stylesheet" href="assets/css/main/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <link rel="shortcut icon" href="assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/png">

    <link rel="stylesheet" href="assets/extensions/sweetalert2/sweetalert2.min.css">
    <style>
        .rounded-rectangle {
            width: 80%; /* Set width in percentage */
            max-width: 400px; /* Set a maximum width if needed */
            height: 120px;

            border: 2px solid #000000;
            border-radius: 10px;

            margin: 0 auto; /* Center the rectangle horizontally */
            white-space: nowrap; /* Prevent text from wrapping onto a new line */
            overflow: hidden; /* Hide any content that overflows the container */
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <div class="content-wrapper container">  
                <div class="page-content">
                    <section class="row">
                        <div class="divider divider-center">
                            <div class="divider-text text-primary"><h6>Printing Samples Label</h6></div>
                            <div class="card">
                                <div class="card-body print-container" style="height:73vh;overflow-y:scroll;">
                                    <div class="row printing-container">
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-5"></div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-md btn-light-primary mt-3" onclick="PrintAllBtn()"><i class="bi bi-printer"></i> Print All</button>
                                        </div>
                                        <div class="col-lg-5"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer>
                            <div class="footer clearfix mb-0 text-muted">
                                <div class="float-start">
                                    <p>2023 &copy; Philippine Batteries, Inc.</p>
                                </div>
                                <div class="float-end">
                                    <p>MOTOLITE</p>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="assets_original/js/jquery.min.js"></script>
<script src="assets_original/js/print.min.js"></script>
<script src="assets/extensions/sweetalert2/sweetalert2.min.js"></script>

<script>
    $(document).ready(function () {
        checkForprint()
        setInterval(() => {
            checkForprint()
        }, 5000);
        
    });

    function checkForprint(){
        $.ajax({
            type: "POST",
            url: "printPhp_repo/print_repo.php",
            data: {
                action:'fetchAvailPrint'
            },
            dataType: "JSON",
            success: function (data) {
                $('.printing-container').html(data)
            }
        });
    }

    function PrintSamplesBtn(sampleID){
        var testSampleID = sampleID

        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "assets_original/FPDF/PrintSamples.php",
            data: {testSampleID:testSampleID},
            success: function (data) {
                PrintSamples(data)
            }
        });
    }

    function DeleteSamplesBtn(sampleID){
        var testSampleID = sampleID
        alert(testSampleID)
    }

    function PrintSamples(sampleID){
        // printJS('PBI-BTLMIS/SamplesFileSystem/'+sampleID+'.pdf')
        printJS({
            printable: 'PBI-BTLMIS/SamplesFileSystem/'+sampleID+'.pdf', // The path to your PDF file
            type: 'pdf',
            // header: 'Your Custom Header',
            showModal: true,
            honorMarginPadding: true,
            css: '@page { size: 67mm 20mm; margin: 0mm; }', // Set the page size and margin for the print dialog
        });
    }

    function PrintAllBtn(){
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "assets_original/FPDF/PrintAllSamples.php",
            success: function (data) {
                alert(data)
                PrintAllSamples(data)
            }
        });
    }

    function PrintAllSamples(allSample){
        printJS('PBI-BTLMIS/SamplesFileSystem/'+allSample+'.pdf')
    }
</script>

</html>