<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="noindex, nofollow" />
<title>Compare Task</title>
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
}
.fixed {
	font-family: Courier, monospace;
	white-space: pre;
	background-color:cornsilk;
}
</style>

<!--Imported AJAX for asynchronous transmission-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
	//Validation for countries' ISO ID inputs
	function validateCountries() {
		let first = document.getElementById("country_1").value;
		let second = document.getElementById("country_2").value;

		//Countries should not equal to each other
		if (first == second) {
			alert ("Cannot compare a country to itself.");
			return false;
		}else {
			//ISO IDs should be in the correct format
			if ((isoCheck(first))&&(isoCheck(second))){
				return true;
			}else {
				alert ("Please enter valid country codes.")
				return false;
			}
		}
	}

	//Function to check ISO IDs for validation using regex
	function isoCheck(code) {
		if ((/^[A-Z]+$/.test(code)) && (code.length == 3)) {
			return true;
		}
	}

</script>

</head>
<body>
<h3 class="center">COA123 - Web Programming</h3>
<h2 class="center">Individual Coursework - Olympic Cyclists</h2>
<h1 class="center">Task 4 - Compare (Compare.php)</h1>
  <table>
  <tr>
  <td>
<div class="fixed">~  __0
 _-\<,_
(*)/ (*)
</div>
  </td>
  </tr>
  </table>
  <br />
  <form action="compareHelper.php" onsubmit="return validateCountries()" method="get" id="compare">
    <table border="1">
      <tr>
        <td><label for="country_1">Country as ISO ID (country_1)</label></td>
        <td>
          <input name="country_1" type="text" class="larger" id="country_1" value="" size="12" />
	  <div id="suggestion_box_1"></div>
        </td>
      </tr>
      <tr>
        <td><label for="country_2">Country as ISO ID (country_2)</label></td>
        <td>
          <input name="country_2" type="text" class="larger" id="country_2" value="" size="12" />
	  <div id="suggestion_box_2"></div>
        </td>
      </tr>

      <tr>
	<!--Priority weighing will be included in the caluclation for ranking between the two countries-->
	<th><label for="priority_description">Priorities in consideration of comparison by weight (10=Heaviest, 1=Lightest)</label></th>
	<th><label for="priority_value">Priority Value</label></th>
      </tr>

       <tr>
        <td><label for="gold_order">Gold Medal</label></td>
        <td>
          <input name="gold_order" type="text" class="larger" id="gold_order" value="1" size="12" />
        </td>
      </tr>
      <tr>
        <td><label for="cyclist_order">Number of Cyclists</label></td>
        <td>
          <input name="cyclist_order" type="text" class="larger" id="cyclist_order" value="1" size="12" />
        </td>
      </tr>
      <tr>
        <td><label for="age_order">Age of Cyclists</label></td>
        <td>
          <input name="age_order" type="text" class="larger" id="age_order" value="1" size="12" />
        </td>
      </tr>

      <tr>
        <td>List comparisons between the two countries</td>
        <td><input type="submit" id="submit" class="larger" /></td>
      </tr>
    </table>
  </form>
</body>
<script>

	//Function to change the value in the text box to the value clicked on
	function selectFirstCountry(val) {
		$("#country_1").val(val);
		$("#suggestion_box_1").hide();
	}

	//AJAX code to get all possible ISO IDs based on the user's input
	$(document).ready(function() {
		$("#country_1").keyup(function() {
			var tempCountry = $.trim($("#country_1").val());
			if (tempCountry.length > 0) {
			$.ajax({
				type: "POST",
				url: "getIsoIds.php",
				//Passing user's input as a parameter
				data: 'keyword=' + $(this).val(),
				success: function(data) {
					$("#suggestion_box_1").show();
					let items = JSON.parse(data);
					let len = items.length;
					
					//Passing drop down menu to the text box
					let insertedHtml = "<ul>";
					for(let i=0; i<len; i++){
						insertedHtml += "<li onclick='selectFirstCountry(\"" + items[i].iso_id + "\")'>" + items[i].iso_id + "</li>";
					}
					insertedHtml += "</ul>";
					document.getElementById("suggestion_box_1").innerHTML = insertedHtml;
					
				}
			});
		//Make sure that when the text box is empty, the suggestion box should be empty too
		} else if (tempCountry.length == 0) {
			document.getElementById("suggestion_box_1").innerHTML = '';
		}
		});
	});

	//Repeated code for the second country

	function selectSecondCountry(val) {
		$("#country_2").val(val);
		$("#suggestion_box_2").hide();
	}

	$(document).ready(function() {
		$("#country_2").keyup(function() {
			var tempCountry = $.trim($("#country_2").val());
			if (tempCountry.length > 0) {
			$.ajax({
				type: "POST",
				url: "getIsoIds.php",
				data: 'keyword=' + $(this).val(),
				success: function(data) {
					$("#suggestion_box_2").show();
					let items = JSON.parse(data);
					let len = items.length;
					let insertedHtml = "<ul>";
					for(let i=0; i<len; i++){
					
					insertedHtml += "<li onclick='selectSecondCountry(\"" + items[i].iso_id + "\")'>" + items[i].iso_id + "</li>";
					
					}
					insertedHtml += "</ul>";
					document.getElementById("suggestion_box_2").innerHTML = insertedHtml;
					
				}
			});
		} else if (tempCountry.length == 0) {
			document.getElementById("suggestion_box_2").innerHTML = '';
		}
		});
	});




</script>
</html>
