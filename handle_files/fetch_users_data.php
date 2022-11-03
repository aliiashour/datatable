<?php
    include_once "./connect_data_base.php" ;  
    $q = "SELECT * FROM users " ; 
    $stmt = $con->prepare($q) ; 
    $stmt->execute() ; 
    $count_all_rows = $stmt->rowCount() ; 

    if(isset($_POST['search']['value'])){
        $search_value = $_POST['search']['value'] ; 
        $q .= "WHERE user_name LIKE '%" . $search_value . "%'" ; 
        $q .= " OR user_mobile LIKE '%" . $search_value . "%'" ; 
        $q .= " OR user_email LIKE '%" . $search_value . "%'" ; 
        $q .= " OR user_city LIKE '%" . $search_value . "%'" ; 
    }

    if(isset($_POST['order'])){
        $column = $_POST['order'][0]['column'] ; 
        $order = $_POST['order'][0]['dir'] ; 
        $q .= " order by " . $column . " " . $order ; 
    }else{
        $q .= " order by user_id ASC" ; 
    }

    if(isset($_POST['length']) && $_POST['length'] != -1){
        $start = $_POST['start'] ; 
        $length = $_POST['length'];
        $q .= ' LIMIT ' . $start . ', ' . $length ; 
    }

    $data =array() ; 
    $stmt = $con->prepare($q) ; 
    $stmt->execute() ; 
    $filtered_rows = $stmt->rowCount() ; 
    if($stmt->rowCount()){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sub_arr = array() ; 
            $sub_arr[] = $row['user_id'] ; 
            $sub_arr[] = $row['user_name'] ; 
            $sub_arr[] = $row['user_email'] ; 
            $sub_arr[] = $row['user_mobile'] ; 
            $sub_arr[] = $row['user_city'] ; 
            $sub_arr[] = '<button id="edit_button" class="btn btn-warning" data-user_id="' . $row['user_id'] .  '">Edit</button>
            <button id="delete_button" class="btn btn-danger" data-user_id="' . $row['user_id'] .  '">Delete</button>' ; 
            $data[] = $sub_arr ; 
        }
    }
   

    $output = array(
        'data'=>$data,
        'draw'=>intval($_POST['draw']),
        'recordsTotal'=>$count_all_rows,
        'recordsFiltered'=>$filtered_rows,
    ) ; 
    echo json_encode($output) ; 