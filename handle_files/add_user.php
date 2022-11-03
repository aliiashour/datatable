<?php

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        include_once "./connect_data_base.php" ; 
        extract($_REQUEST) ; 
        $q = "INSERT INTO users SET 
            user_name = :user_name,
            user_email = :user_email,
            user_mobile = :user_mobile,
            user_city = :user_city" ; 
        $stmt = $con->prepare($q) ; 
        $res = $stmt->execute(array(
            ':user_name' => $user_name,
            ':user_email' => $user_email,
            ':user_mobile' => $user_mobile,
            ':user_city' => $user_city
        ));
        $data = '' ; 
        if($res){
            $data = array('status' => 'success') ; 
        }else{
            $data = array('status' => 'failed') ; 
        }
        
        echo json_encode($data) ; 

        
    }