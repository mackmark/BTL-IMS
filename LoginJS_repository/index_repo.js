$('#Login').on('click', function(){
    var username = $('#uname').val()
    var password = $('#password').val()

    // alert(username+' '+password)

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "LoginPhp_repository/index_repo.php",
        data: {
            username:username,
            password:password
        },
        success: function (data) {
            var result = JSON.parse(data.result)
            var UlevelID = JSON.parse(data.UserLevelID)

            // alert(UlevelID)

            if(result == 1){
                if(UlevelID==1){
                    location.href='Customer/index.php';
                }
                else if(UlevelID==3){
                    location.href='LabManager/index.php';
                }
                else{
                    window.location.reload();
                }
              
            }
            else if(result == 2){
                Swal.fire('Wrong Password', 'Credentials does not match', 'Warning');
            }
            else{
                Swal.fire('Not Exist', 'Username does not exist', 'Warning');
            }
        }
    });
})