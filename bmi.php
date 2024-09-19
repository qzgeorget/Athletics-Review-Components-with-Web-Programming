<!DOCTYPE html>
<html lang ="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width"/>

<title>BMI Table</title> 
</head>

<body>


	<?php
	
	#Getting information from HTML file
	$min_weight = $_GET['min_weight'];
	$max_weight = $_GET['max_weight'];
	$min_height = $_GET['min_height'];
	$max_height = $_GET['max_height'];

	#Passing html table element
	echo "<table border=1px solid black>";
	echo '<th>Weight v \ Height ></th>';
	for ($x=$min_height;$x<=$max_height;$x=$x+5) {
		echo '<th>'.$x.'</th>';
	}

	#For each incremented value of weight,
	for($i=$min_weight;$i<=$max_weight;$i=$i+5){
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		#For each incremented value of height,
		for($j=$min_height; $j<=$max_height; $j=$j+5) {
			#Converting height to the correct unit 
			$y=$j/100;
			#Make the necessary BMI calculation
			echo '<td>'.round($i/$y/$y,3).'</td>';
		}
		echo '</tr>';
	}
	echo "</table>";
	?>

</body>
</html>
