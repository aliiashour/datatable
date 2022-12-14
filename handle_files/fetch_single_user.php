<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        include_once "./connect_data_base.php" ;
        extract($_REQUEST) ; 

        $q = "SELECT * FROM users WHERE user_id = ?" ; 
        $stmt = $con->prepare($q) ; 
        $stmt->execute(array($user_id)) ; 
        $data = '' ; 
        if($stmt->rowCount()){
            $res = $stmt->fetch() ; 
            $data = array(
                'status' => 'found',
                'data'=>array(
                    'user_id' => $res['user_id'],
                    'user_name' => $res['user_name'],
                    'user_email' => $res['user_email'],
                    'user_uname' => $res['user_uname'],
                    'user_password' => $res['user_password']
                )
            ) ; 
        }else{
            $data = array(
                'status' => 'notfound',
                'data'=>''
            ) ; 
        }
        echo json_encode($data) ; 
        
    }

?>