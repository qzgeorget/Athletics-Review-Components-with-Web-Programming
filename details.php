<!DOCTYPE html>
<html lang ="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width"/>

<title>Details Table</title> 
</head>

<body>


  <?php
    #Function to validate that the date entered by the user is in the correct and usable format
    function validateDate($date, $format = 'Y-m-d'){
      
      #Using regex to check against the parameters
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) === $date;
    }

    #Getting information from HTML file
    $dateRawArray = array($_GET['date_1'], $_GET['date_2']);
    #Put information from HTML files into an array to loop through and prevent repeated code
    $dateArray = array();

    #Looping through each date 
    foreach ($dateRawArray as $rawDate) {

      #Converting date into a manipulatable format for the SQL query
      $tempRawArray = explode('/', $rawDate);
      $tempDate = $tempRawArray[2].'-'.$tempRawArray[1].'-'.$tempRawArray[0];
      
      #Validation function from above used
      if (validateDate($tempDate)){
        $dateArray[] = $tempDate;
      }else {
        #Validation catch
        echo '<script>alert("Invalid dates. Please try again")</script>';
        include ('details.html');
      }
    }

    #If validation is passed and both dates are entered
    if (count($dateArray) == 2) {

      #Getting database login credentials
      include "database_connection_info.php";

      #Initializing database connection
      $conn = mysqli_connect($servername, $username, $password, $dbname);

      #SQL query
      $sql = "SELECT name, country_name FROM cyclist, country WHERE dob <= '$dateArray[1]' AND dob >= '$dateArray[0]' AND cyclist.iso_id = country.iso_id ORDER BY dob DESC";

      #Entering query
      $result = mysqli_query($conn, $sql);

      #As long as there are results from the query, add these results to the result array
      $allDataArray = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    	    $allDataArray[] = $row;
        }
      }

      #Printing json data onto page
      echo json_encode($allDataArray);

      mysqli_close($conn);
    }

  ?>
</body>
</html>

