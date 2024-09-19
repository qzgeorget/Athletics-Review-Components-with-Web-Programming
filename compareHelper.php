<!DOCTYPE html>
<html lang ="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width"/>

<title>Compare Results</title>
<style type="text/css">
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	background-color: bisque;
}
.center {
	text-align:center;
}
body,td,th {
	color: brown; 
}
.larger {
	font-size:larger;
	text-align:left;
}
table {
	margin-left:auto;
	margin-right:auto;
	font-size:medium;
	width:70%;
}
.fixed {
	font-family: Courier, monospace;
	white-space: pre;
	background-color:cornsilk;
}
h1 {
   position:absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}
.grid-container {
  display: grid;
  grid-template-columns: 50% 50%;
  background-color: brown;
  padding: 10px;
  text-align:center;
}
.grid-item {
  background-color: bisque;
  padding: 20px;
  font-size: 30px;
  text-align: center;
}
#doughnut {
  grid-column-start:1;
  grid-column-end:3;
  text-align:center;
}
</style>

<!--Imported Chart.js for doughnut chart display-->
<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

</head>

<body>
<main>
<!--Layout for the results page-->
<div class="grid-container">
  <div class="grid-item" id="doughnut"><canvas id="myChart"></canvas></div>
  <div class="grid-item" id="medals"></div>
  <div class="grid-item" id="comparison"></div>
  <div class="grid-item" id="cyclist1"></div>
  <div class="grid-item" id="cyclist2"></div>
</div>

</main>


  <?php

    #Get countries from the HTML page
    $countries = array($_GET['country_1'], $_GET['country_2']);
    sort($countries);

    #Get priority values from the HTML page
    $priorities = array($_GET['gold_order'], $_GET['cyclist_order'], $_GET['age_order']);

    #Get database connection credentials
    include "database_connection_info.php";

    #Initialize connection to database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    #Looping through each country to display their cyclists
    $html_array = array("","");
    for ($x = 0; $x < 2; $x++){
      
      #SQL query for all cyclists from that one country and their information
      $sql = "SELECT name, gender, dob FROM cyclist WHERE iso_id = '$countries[$x]' ORDER BY gender, name";
      $result = mysqli_query($conn, $sql);

      #Adding html elements to each of the countries' html elements
      if (mysqli_num_rows($result) > 0){
        $html_array[$x] = $html_array[$x] . '<h4>Cyclists from '.$countries[$x].'</h4>';
        $html_array[$x] = $html_array[$x] . '<table border=1px solid black>';
        $html_array[$x] = $html_array[$x] . '<th>Cyclist Name</th>';
        $html_array[$x] = $html_array[$x] . '<th>Gender</th>';
        $html_array[$x] = $html_array[$x] . '<th>Date Of Birth</th>';
        while ($row = mysqli_fetch_array($result)){
          $html_array[$x] = $html_array[$x] . '<tr>';
          $html_array[$x] = $html_array[$x] . '<td>'.$row[0].'</td>';
          $html_array[$x] = $html_array[$x] . '<td>'.$row[1].'</td>';
          $html_array[$x] = $html_array[$x] . '<td>'.$row[2].'</td>';
          $html_array[$x] = $html_array[$x] . '</tr>';
        }
        $html_array[$x] = $html_array[$x] . "</table>";
      }else {
        $html_array[$x] = $html_array[$x] . '<label>'. $countries[$x].' do not have olympic cyclists.</label>';
      }
    }
    
    #Placing the HTML displaying the cyclists into the layout grid
    echo '<script>
    document.getElementById("cyclist1").innerHTML ="'.$html_array[0].'";
    document.getElementById("cyclist2").innerHTML ="'.$html_array[1].'";
    </script>';

    #Displaying medals for each countries in an HTML table
    $medal_html = "";
    $medal_html = $medal_html . '<h3>Medal Overview</h3>';

    #SQL query for each country's medal count
    $sql = "SELECT country_name, gold, silver, bronze, total FROM country WHERE iso_id = '$countries[0]' OR iso_id = '$countries[1]'";
    $result = mysqli_query($conn, $sql);

    $medal_html = $medal_html . '<table border=1px solid black>';
    $medal_html = $medal_html . '<th>Country</th>';
    $medal_html = $medal_html . '<th>Gold</th>';
    $medal_html = $medal_html . '<th>Silver</th>';
    $medal_html = $medal_html . '<th>Bronze</th>';
    $medal_html = $medal_html . '<th>Total</th>';
    if (mysqli_num_rows($result) > 0){
      while ($row = mysqli_fetch_array($result)){
        $medal_html = $medal_html . '<tr>';
        $medal_html = $medal_html . '<td>'.$row[0].'</td>';
        $medal_html = $medal_html . '<td>'.$row[1].'</td>';
        $medal_html = $medal_html . '<td>'.$row[2].'</td>';
        $medal_html = $medal_html . '<td>'.$row[3].'</td>';
        $medal_html = $medal_html . '<td>'.$row[4].'</td>';
        $medal_html = $medal_html . '</tr>';
      }
    }
    $medal_html = $medal_html . "</table>";

    #Placing the HTML displaying the cyclists into the layout grid
    echo '<script>document.getElementById("medals").innerHTML ="'.$medal_html.'"</script>';

    #Displaying the consideration criterias for the comparison
    $comparison_html = "";
    $comparison_html = $comparison_html . '<h3>Comparison Criteria</h3>';

    #This table combines three different queries
    #SQL query for gold medal count
    $sql_gold = "SELECT country_name, gold FROM country WHERE iso_id = '$countries[0]' OR iso_id = '$countries[1]'";
    $result_gold = mysqli_query($conn, $sql_gold);

    #SQL query for cyclist count
    $sql_number = "SELECT country_name, COUNT(cyclist_id) FROM cyclist,country WHERE (cyclist.iso_id = '$countries[0]' OR cyclist.iso_id = '$countries[1]') AND (cyclist.iso_id = country.iso_id) GROUP BY country.iso_id ORDER BY country_name ";
    $result_number = mysqli_query($conn, $sql_number);

    #SQL query for average age of cyclists
    $sql_age = "SELECT country_name, AVG(DATEDIFF('2012-08-12', dob)) / 365.25 FROM cyclist, country WHERE (cyclist.iso_id = '$countries[0]' OR cyclist.iso_id = '$countries[1]') AND (cyclist.iso_id = country.iso_id) GROUP BY country.iso_id ORDER BY country_name";
    $result_age = mysqli_query($conn, $sql_age);

    #Combining queries into a main table
    if ((mysqli_num_rows($result_gold) > 0) && (mysqli_num_rows($result_number) > 0) && (mysqli_num_rows($result_age) > 0)) {
      $comparison_html = $comparison_html . '<table border=1px solid black>';
      $comparison_html = $comparison_html . '<th>Country</th>';
      $comparison_html = $comparison_html . '<th>Gold Medals ('.$priorities[0].')</th>';
      $comparison_html = $comparison_html . '<th>Number of Cyclists ('.$priorities[1].')</th>';
      $comparison_html = $comparison_html . '<th>Average Age of Cyclists at London 2012 ('.$priorities[2].')</th>';
      $results_array = array(array(),array(),array());
      while (($row_gold = mysqli_fetch_array($result_gold)) &&  ($row_number = mysqli_fetch_array($result_number)) && ($row_age = mysqli_fetch_array($result_age))) {
        $comparison_html = $comparison_html . '<tr>';
        $comparison_html = $comparison_html . '<td>'.$row_gold[0].'</td>';
        $comparison_html = $comparison_html . '<td>'.$row_gold[1].'</td>';
        $result_array[0][] = $row_gold[1];
        $comparison_html = $comparison_html . '<td>'.$row_number[1].'</td>';
        $result_array[1][] = $row_number[1];
        $comparison_html = $comparison_html . '<td>'.$row_age[1].'</td>';
        $result_array[2][] = $row_age[1];
        $comparison_html = $comparison_html . '</tr>';
      }
      $comparison_html = $comparison_html . "</table>";

      #Making sure that both countries have valid data to make a comparison
      #If one countries doesn't have any cyclists, the comparison would not be possible and it should be caught 
      if (count($result_array[0]) == 2) {

        #Calculations for the countries' comparative ranking according to priority weighing
        $country_ratios = array(0,0);
        for ($x = 0; $x<3; $x++){
          $temp_sum = $result_array[$x][0] + $result_array[$x][1];
          $country_ratios[0] = $country_ratios[0] + (($result_array[$x][0]/$temp_sum) * $priorities[$x]);
          $country_ratios[1] = $country_ratios[1] + (($result_array[$x][1]/$temp_sum) * $priorities[$x]);
        }

        #Deciding which country ranks higher
        if ($country_ratios[0] > $country_ratios[1]){
          echo '<script>document.getElementById("doughnut").insertAdjacentHTML("beforeend", "<h1>'.$countries[0].' ranks higher than '.$countries[1].'</h1>")</script>';
        } else {
          echo '<script>document.getElementById("doughnut").insertAdjacentHTML("beforeend","<h1>'.$countries[1].' ranks higher than '.$countries[0].'</h1>")</script>';
        }

        #Creates doughnut chart to show result of calculations
        echo '<script>new Chart("myChart", {
          type: "doughnut",
          data: {
            labels: ["'.implode('","', $countries).'"],
            datasets: [{
              backgroundColor: ["#e5a877", "#f27209"],
              data:['. implode(',', $country_ratios) .']
            }]
          },
          options: {
              tooltips: {
                    bodyAlign:"center",
                titleAlign:"center"
            },
              title: {
                    display: true,
              }
            }
          });</script>';
      }
    } else {
      #Catch for when comparison is not possible
      $comparison_html = $comparison_html . '<label>These two countries do not have olympic cyclists.</label><br>';
    }

    #Passing comparison table and doughnut chart to HTML
    echo '<script>document.getElementById("comparison").innerHTML ="'.$comparison_html.'"</script>';



    mysqli_close($conn);
  ?>



</body>
</html>
