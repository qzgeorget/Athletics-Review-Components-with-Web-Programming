<?php
    #File to get possible countries' ISO IDs for drop down menu in compare.php
    #Getting database credentials
    include "database_connection_info.php";

    #Initialize connection to database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    #Getting the partial ISO ID from compare.php
    $search = "{$_POST['keyword']}%";

    #SQL query
    $sql = "SELECT iso_id FROM country  WHERE iso_id LIKE '$search'";
    $result = mysqli_query($conn, $sql);

    #Returning the results as JSON array
    $allDataArray = array();
    if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $allDataArray[] = $row;
        }
    }
    echo json_encode($allDataArray);
?>