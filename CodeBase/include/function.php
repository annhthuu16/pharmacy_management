<?php
require_once('config_session.inc.php');
require_once('databaseHandler.inc.php');

// Input field validation
function validate($inputData){
    global $conn;
    $validateData = mysqli_real_escape_string($conn, $inputData);
    return trim($validateData);
}

// Redirect from 1 page to another
function redirect($url, $status){
    $_SESSION['status'] = $status;
    header('Location: '.$url);
    exit(0);
}

//Display messages or status after any process
function alertMessage(){
    if (isset($_SESSION['status'])){
        // $_SESSION['status'];
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6>'.$_SESSION['status'].'</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

        unset ($_SESSION['status']);
    }
}

// Insert record using this function
function insert($tableName, $data){
    global $conn;
    $table = validate($tableName);

    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = "'" . implode("', '", $values) . "'";

    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($conn, $query);
    return $result;
}

//Update data using this function
function update($tableName, $Id, $data){
    global  $conn;
    $table = validate($tableName);
    $Id = validate($Id);

    $updateDataString = "";

    foreach ($data as $column => $value){
        $updateDataString .= $column. '='."'$value',";
    }
    $finalUpdateData = substr(trim($updateDataString),0,-1);

    $query = "UPDATE $table SET $finalUpdateData WHERE Id = '$Id'";
    $result = mysqli_query($conn, $query);
    return $result;
}


//function getAll
function getAll($tableName, $status = NULL){
    global $conn;
    $table = validate($tableName);
    $status = validate($status);

    if($status == 'status'){
        $query = "SELECT * FROM $table WHERE status='0'";
    }
    else {
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($conn, $query);

}

// function getById($tableName, $Id){
//     global $conn;
//     $table = validate($tableName);
//     $Id = validate($Id);

//     $query = "SELECT * FROM $table WHERE Id = '$Id' LIMIT 1";
//     $result = mysqli_query($conn, $query);

//     if ($result){
//         if (mysqli_num_rows($result) == 1){
//             $row = mysqli_fetch_array($result);
//             $response = [
//                 'status' => 404,
//                 'data' => $row,
//                 'message' => 'Record Found',
//             ];
//         } else {
//             $response = [
//                 'status' => 404,
//                 'message' => 'No Data Found'
//             ];
//         }
//     } else {
//         $response = [
//             'status' => 500,
//             'message' => 'Something went wrong'
//         ];
//         return $response;
//     }
// }

function getById($tableName, $Id){
    global $conn;
    $table = validate($tableName);
    $Id = validate($Id);

    $query = "SELECT * FROM $table WHERE Id = '$Id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result){
        if (mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            return null;
        }
    } else {
        return null;
    }
}


//Delete data from database using Id
function deleteFunction($tableName, $Id){
    global $conn;
    $table = validate($tableName);
    $Id = validate($Id);
    $query = "DELETE FROM $table WHERE Id='$Id'LIMIT 1";
    $result = mysqli_query($conn, $query);
    return $result;
}

//function checkParamId
function checkParamId($type){
    if (isset($_GET[$type])){
        if ($_GET[$type] != ''){
            return $_GET[$type];
        }  else {
            return '<h5>No ID Found</h5>';
        }
    } else {
        return '<h5>No ID Found</h5>';
    }
}

function getCount($tableName){
    global $conn;

    $table = validate($tableName);

    $query = "SELECT * FROM $table";
    $query_run = mysqli_query($conn, $query);
    if($query_run){

        $totalCount = mysqli_num_rows($query_run);
        return $totalCount;
    } else{
        return 'Something went wrong!';
    }
}

function getSearch($tableName, $searchQuery = '') {
    global $conn;

    $table = validate($tableName);

    $query = "SELECT * FROM $table" . $searchQuery;
    $result = mysqli_query($conn, $query);
    return $result;
}



// // Log out session
// function logoutSession(){
//     unset($_SESSION['loggedIn']);
//     unset($_SESSION['loggedInUser']);
// }

//Json Response
function jsonResponse($status, $status_type, $message){
    $response = [
        'status' => $status,
        'message' => $message,
        'status_type' => $status_type
    ];
    echo json_encode($response);
    return json_encode($response);
}