<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Learn Datatables Plugin</title>
        <!-- add css library -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        
        <!-- add datatable css library -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/datatables.min.css"/>
    </head>
    <body>
    
    
    
        <h1 class="text-center">jQuery Datatable Plugin</h1>
        <div class="container-fluid">
            <div class="row">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 text-end mb-4">
                            <div class="row">
                                <div id="response_delete" class="col-12"></div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_user">
                                        add user
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-2"></div>
                        <div class="col-12 col-md-8">
                            <table id="datatable" class="table">
                                <thead>
                                    <th>ID</th>
                                    <th>name</th>
                                    <th>email</th>
                                    <th>mobile</th>
                                    <th>city</th>
                                    <th>Operation</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 col-md-2"></div>
                    </div>
                </div>
            </div>
        </div>



        <!-- add jquery library -->
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <!-- add datatables librart -->
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/datatables.min.js"></script>
        <!-- add bootstrap library js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        
        <!-- datatable main setup -->
        <script>
            $("#datatable").DataTable({
                "pagingType": 'full_numbers',
                "reponsive":true,
                "language":{
                    "search":"_INPUT_",
                    "searchPlaceholder":"Search..."
                },
                "serverSide" : true,
                "select" : true,
                "processing":true,
                "paging":true,
                "order":[],
                "ajax":{
                    "url":"./handle_files/fetch_users_data.php",
                    "type":"post",
                },
                "fnCreateRow":function(nRow, aData, iDataIndex){
                    $(nRow).attr('id', aData[0]) ; 
                },
                "columnDefs":[{
                    "target":[0,5],
                    "orderable":false,
                }]
            }) ; 
        </script>


        <script>
        
            // script of add user form 

            $(document).on("submit", "#add_user_form", function(event){
                event.preventDefault() ; 
                var user_name = $("#user_name").val() ; 
                var user_email = $("#user_email").val() ; 
                var user_mobile = $("#user_mobile").val() ; 
                var user_city = $("#user_city").val() ; 
                if(user_name != ''  && user_email != ''  && user_mobile != ''  && user_city != ''){
                    $.ajax({
                        url:"./handle_files/add_user.php",
                        method:"post",
                        data:{user_name:user_name, user_email:user_email, user_mobile:user_mobile, user_city:user_city},
                        success:function(data){
                            var json = JSON.parse(data) ; 
                            status = json.status ; 
                            if(status == 'success'){
                                $("#datatable").DataTable().draw() ; 
                                $("#response_add").html('<div class="alert alert-success">user successfully added</div>') ; 
                                $("#user_name").val('') ; 
                                $("#user_email").val('') ; 
                                $("#user_mobile").val('') ; 
                                $("#user_city").val('') ; 
                                setTimeout(function(){
                                    $("#response_add").html('');
                                }, 2000);
                            }else{
                                $("#response_add").html('<div class="alert alert-danger">user can not be added</div>') ; 
                                setTimeout(function(){
                                    $("#response_add").html('');
                                }, 2000);
                            }
                        }

                    }) ; 
                }else{
                    alert("please fill all fields") ; 
                }
            }) ;


            // Start Edit user
            
            // click on edit button to get data into edit modal
            $(document).on('click', '#edit_button', function(){
                var user_id = $(this).data('user_id') ; 
                // console.log(user_id) ; 

                if(user_id != ''){
                    // now fetch user data
                    $.ajax({
                        url:"./handle_files/fetch_single_user.php",
                        method:"POST",
                        data:{user_id:user_id},
                        success:function(data){
                            var json = JSON.parse(data) ;
                            if(json.status =='found'){
                                // exist user
                                $("#_user_id").val(json['data']['user_id']) ; 
                                $("#_user_name").val(json['data']['user_name']) ; 
                                $("#_user_email").val(json['data']['user_email']) ; 
                                $("#_user_mobile").val(json['data']['user_mobile']) ;
                                $("#_user_city").val(json['data']['user_city']) ; 
                                $("#edit_user").modal('show') ; 
                            }
                        }
                    }) ; 
                }

            }) ; 

            // script for edit user
            $(document).on("submit", "#edit_user_form", function(event){
                event.preventDefault() ; 
                var user_id = $("#_user_id").val() ; 
                var user_name = $("#_user_name").val() ; 
                var user_email = $("#_user_email").val() ; 
                var user_mobile = $("#_user_mobile").val() ; 
                var user_city = $("#_user_city").val() ; 
                if(user_name != ''  && user_email != ''  && user_mobile != ''  && user_city != ''){
                    $.ajax({
                        url:"./handle_files/edit_user.php",
                        method:"post",
                        data:{user_id:user_id, user_name:user_name, user_email:user_email, user_mobile:user_mobile, user_city:user_city},
                        success:function(data){
                            var json = JSON.parse(data) ; 
                            status = json.status ; 
                            if(status == 'success'){
                                $("#datatable").DataTable().draw() ; 
                                $("#response_edit").html('<div class="alert alert-success">user successfully updated</div>') ;  
                                setTimeout(function(){
                                    $("#response_edit").html(''); 
                                    $("#edit_user").modal('hide') ; 
                                }, 1000);
                            }else{
                                $("#response_edit").html('<div class="alert alert-danger">user can not be updated</div>') ; 
                                setTimeout(function(){
                                    $("#response_edit").html('');
                                }, 2000);
                            }
                        }

                    }) ; 
                }else{
                    alert("please fill all fields") ; 
                }
            }) ; 

            // script for delete user
            $(document).on('click', '#delete_button', function(){
                var user_id = $(this).data("user_id") ; 
                // now delete user directly
                if(confirm('are you sure?')){
                    $.ajax({
                        url:"./handle_files/delete_user.php",
                        method:"POST",
                        data:{user_id:user_id},
                        success:function(data){
                            json = JSON.parse(data) ; 
                            if(json['status'] == 'success'){
                                $("#datatable").DataTable().draw() ; 
                                $("#response_delete").html('<div class="alert alert-success text-start">user deleted</div>') ; 
                                setTimeout(function(){
                                    $("#response_delete").html('') ; 
                                }, 2000) ; 
                            }
                        }
                    });
                }
            }) ; 

        </script>


        <!-- Start modal to add user -->
        <div class="modal fade" id="add_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add user</h1>
                    <button id="close-modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="response_add" class="col-12 text-start"></div>
                    </div>
                    <form id="add_user_form">
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Username</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" aria-describedby="user_name_help">
                            <div id="user_name_help" class="form-text">Enter your user name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" aria-describedby="user_email_help">
                            <div id="user_email_help" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_mobile" class="form-label">user number</label>
                            <input type="text" class="form-control" id="user_mobile" name="user_mobile" aria-describedby="user_mobile_help">
                            <div id="user_mobile_help" class="form-text">Enter your number.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_city" class="form-label">city</label>
                            <input type="text" class="form-control" id="user_city" name="user_city" aria-describedby="user_city_help">
                            <div id="user_city_help" class="form-text">Enter your city.</div>
                        </div>
                </div>
                <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- End modal to add user -->


        <!-- Start EDIT User modal -->
        <div class="modal fade" id="edit_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit user</h1>
                    <button id="close-modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="response_edit" class="col-12 text-start"></div>
                    </div>
                    <form id="edit_user_form">
                        <input type="hidden" name="_user_id" id="_user_id">
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Username</label>
                            <input type="text" class="form-control" id="_user_name" name="_user_name" value="test" aria-describedby="user_name_help">
                            <div id="user_name_help" class="form-text">Enter your user name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="_user_email" name="_user_email" aria-describedby="user_email_help">
                            <div id="user_email_help" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_mobile" class="form-label">user number</label>
                            <input type="text" class="form-control" id="_user_mobile" name="_user_mobile" aria-describedby="user_mobile_help">
                            <div id="user_mobile_help" class="form-text">Enter your number.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_city" class="form-label">city</label>
                            <input type="text" class="form-control" id="_user_city" name="_user_city" aria-describedby="user_city_help">
                            <div id="user_city_help" class="form-text">Enter your city.</div>
                        </div>
                </div>
                <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- End EDIT user Modal -->
    </body>
</html>