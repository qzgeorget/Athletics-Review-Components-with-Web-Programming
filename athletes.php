<!DOCTYPE html>
<html lang ="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width"/>

<title>Athletes Table</title> 
</head>

<body>
  <?php
   #Getting information from HTML file
   $country_id = $_GET['country_id'];
   $part_name = $_GET['part_name'];

   #Validation for ISO ID for entered country
   if ((strtoupper($country_id) == $country_id) && (strlen($country_id) == 3) && ctype_alpha($part_name)) {

    #Getting database login credentials
    include "database_connection_info.php";

    #Initializing database connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    #SQL query
    $sql = "SELECT name, COUNT(name) FROM cyclist,event WHERE cyclist.cyclist_id = event.cyclist_id AND iso_id LIKE '%$country_id%' AND name LIKE '%$part_name%' GROUP BY cyclist.cyclist_id";

    #Entering query
    $result = mysqli_query($conn, $sql);

    #Passing html table element
    echo "<table border=1px solid black>";
    echo '<th>Name</th>';
    echo '<th>Participation counts</th>';

    #As long as there are results from the query, output them in table entries of 2 columns
    if (mysqli_num_rows($result) > 0){
      while ($row = mysqli_fetch_array($result)){
        echo '<tr>';
        echo '<td>'.$row[0].'</td>';
        echo '<td>'.$row[1].'</td>';
        echo '</tr>';
      }
    }
    echo "</table>";

    #Ending connection with datatbase
    mysqli_close($conn);

  #Validation catch
  }else {
  echo '<script>alert ("Invalid ISO ID or partial name.")</script>';
  include 'athletes.html';
  }
  ?>
</body>
</html>

