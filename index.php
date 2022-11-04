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
                                <div class="col-12">
                                    <div class="row">
                                        <div id="response" class="col-12 text-start"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button id="add_user" data-title="Add User"  data-action="add" type="button" class="btn btn-primary">
                                        add user
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-2"></div>
                        <div class="col-12 col-md-8">
                            <table id="datatable" class="table hover">
                                <thead>
                                    <th>ID</th>
                                    <th>uername</th>
                                    <th>full name</th>
                                    <th>email</th>
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
    
        <script>
    
            
            
            // setting datatable
            $("#datatable").DataTable({
                "pagingType": 'full_numbers',
                // "processing":true,
                "reponsive":true,
                "language":{
                    "search":"_INPUT_",
                    "searchPlaceholder":"Search..."
                },
                "serverSide" : true,
                "select" : true,
                "lengthChange":true,
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
                    "target":[0,4],
                    "orderable":false,
                }]
            }) ; 

            // set datatable intervel to refresh
            // setInterval(function(){
            //     console.log("Sdf") ; 
            //     $("#datatable").DataTable().draw() ; 
            // }, 2500) ; 
            // script of add user form 
            $("#add_user").on("click", function(){
                $modal_title = $(this).data("title") ; 
                $modal_action = $(this).data("action") ; 
                $("#title").html($modal_title) ;
                $("#action").html($modal_action) ;
                $("#user_modal").modal('show') ; 
            }) ; 
            
            // click on edit button to get data into edit modal
            $(document).on('click', '#edit_button', function(){
                var user_id = $(this).data('user_id') ; 
                $modal_title = $(this).data("title") ; 
                $modal_action = $(this).data("action") ; 
                $("#title").html($modal_title) ;
                $("#action").html($modal_action) ;

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
                                $("#user_id").val(json['data']['user_id']) ; 
                                $("#user_name").val(json['data']['user_name']) ; 
                                $("#user_email").val(json['data']['user_email']) ; 
                                $("#user_uname").val(json['data']['user_uname']) ;
                                $("#user_password").val('') ;
                                $("#user_modal").modal('show') ; 
                            }
                        }
                    }) ; 
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
                                $("#response").html('<div class="alert alert-success text-start">user deleted</div>') ; 
                                setTimeout(function(){
                                    $("#response").html('') ; 
                                }, 2000) ; 
                            }
                        }
                    });
                }
            }) ; 

            // main modal
            $(document).on("submit", "#user_form", function(event){
                event.preventDefault() ;
                var user_id = $("#user_id").val() ; 
                var user_name = $("#user_name").val() ; 
                var user_email = $("#user_email").val() ; 
                var user_uname = $("#user_uname").val() ; 
                var user_password = $("#user_password").val() ; 
                var action = $("#action").html() ; 
                var url = "./handle_files/" + action + "_user.php" ; 
                if(user_name != ''  && user_email != ''  &&  user_uname != ''){
                    $.ajax({
                        url:url,
                        method:"post",
                        data:{user_id:user_id, user_name:user_name, user_email:user_email, user_password:user_password, user_uname:user_uname},
                        success:function(data){
                            var json = JSON.parse(data) ; 
                            if(json.status == "success"){
                                $("#datatable").DataTable().draw() ; 
                                $("#response").html('<div class="alert alert-success">' + json.msg + '</div>') ; 
                                $("#user_name").val('') ; 
                                $("#user_email").val('') ; 
                                $("#user_password").val('') ; 
                                $("#user_uname").val('') ; 
                                $("#user_modal").modal('hide') ;
                                setTimeout(function(){
                                    $("#response").html('');
                                }, 2000);
                            }else{
                                $("#response-form").html('<div class="alert alert-danger">' +json.msg + '</div>') ; 
                                setTimeout(function(){
                                    $("#response-form").html('');
                                }, 2000);
                            }
                        }

                    }) ; 
                }else{
                    $("#response-form").html('<div class="alert alert-danger">fill all fieds</div>') ; 
                    setTimeout(function(){
                        $("#response-form").html('') ; 
                    }, 2000) ; 
                
                } 
            }) ;


        </script>


        <!-- Start modal -->
        <div class="modal fade" id="user_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="title"></h1>
                    <button id="close-modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="user_form">
                        <input type="hidden" name="user_id" id="user_id">
                        <div id="response-form" class="col-12 text-start"></div>
                        <div class="mb-3">
                            <label for="user_uname" class="form-label">Username</label>
                            <input type="text" class="form-control" id="user_uname" name="user_uname" aria-describedby="user_uname_help">
                            <div id="user_uname_help" class="form-text">Enter your user name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_name" class="form-label">full name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" aria-describedby="user_name_help">
                            <div id="user_name_help" class="form-text">Enter your name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" aria-describedby="user_email_help">
                            <div id="user_email_help" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label for="user_password" class="form-label">user password</label>
                            <input type="password" class="form-control" id="user_password" name="user_password" aria-describedby="user_password_help">
                            <div id="user_password_help" class="form-text">Enter your password.</div>
                        </div>
                </div>
                <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="action"></button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- End modal -->

    </body>
</html>